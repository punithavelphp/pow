<?php

declare(strict_types = 1);

namespace Mstore\Customer\Model;

use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Model\AuthenticationInterface;
use Magento\Customer\Model\EmailNotificationInterface;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\LocalizedException;
use Mstore\Customer\Api\AccountManagementInterface;
use Magento\Customer\Model\CustomerRegistry;
use Magento\Customer\Model\AddressRegistry;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Encryption\EncryptorInterface as Encryptor;
 use Magento\Customer\Model\ResourceModel\Customer\CollectionFactory as customerCollectionFactory;

class AccountManagement implements AccountManagementInterface {

    /**
     * @var Encryptor
     */
    private $encryptor;

    /**
     * @var AuthenticationInterface
     */
    private $authentication;

    /**
     * @var CustomerRepositoryInterface
     */
    private $customerRepository;

    /**
     * @var EmailNotificationInterface
     */
    private $emailNotification;

    /**
     * @var \Magento\Customer\Api\AccountManagementInterface
     */
    private $accountManagement;

    /**
     * @param AuthenticationInterface $authentication
     * @param CustomerRepositoryInterface $customerRepository
     * @param \Magento\Customer\Api\AccountManagementInterface $accountManagement
     * @param EmailNotificationInterface $emailNotification
     */
    public function __construct(
    AuthenticationInterface $authentication, CustomerRepositoryInterface $customerRepository, \Magento\Customer\Api\AccountManagementInterface $accountManagement
    , EmailNotificationInterface $emailNotification, CustomerRegistry $customerRegistry, AddressRegistry $addressRegistry = null
    , Encryptor $encryptor
 		,customerCollectionFactory $customerCollectionFactory
    ) {
        $this->authentication = $authentication;
        $this->customerRepository = $customerRepository;
        $this->emailNotification = $emailNotification;
        $this->accountManagement = $accountManagement;
        $this->customerRegistry = $customerRegistry;
        $objectManager = ObjectManager::getInstance();
        $this->addressRegistry = $addressRegistry ?: $objectManager->get(AddressRegistry::class);
        $this->encryptor = $encryptor;
 		$this->customerCollectionFactory = $customerCollectionFactory;

    }

    /**
     * @inheritDoc
     */
    public function changeEmail($customerId, $newEmail, $currentPassword) {
        $this->authentication->authenticate($customerId, $currentPassword);
        $customer = $this->customerRepository->getById($customerId);
        $origEmail = $customer->getEmail();
        if ($origEmail == $newEmail) {
            throw new LocalizedException(__('Cannot change the same email.'));
        }
        $customer->setEmail($newEmail);
        $savedCustomer = $this->customerRepository->save($customer);
        //Sending notify email to customer
        $this->sendEmailNotification($savedCustomer, $origEmail);
        return true;
    }

    /**
     * @inheritDoc
     */
    public function changePasswordById($customerId, $currentPassword, $newPassword, $confirmPassword) {
        $customer = $this->customerRepository->getById($customerId);
        if ($newPassword != $confirmPassword) {
            throw new InputException(__('Password confirmation doesn\'t match entered password.'));
        }
        $this->accountManagement->changePasswordById($customerId, $currentPassword, $newPassword);
        //Sending notify email to customer
        $this->sendEmailNotification($customer, $customer->getEmail(), true);
        return true;
    }

    /**
     * @param $savedCustomer
     * @param $origCustomerEmail
     * @param bool $isPasswordChanged
     */
    private function sendEmailNotification($savedCustomer, $origCustomerEmail, $isPasswordChanged = false) {
        $this->emailNotification->credentialsChanged(
                $savedCustomer, $origCustomerEmail, $isPasswordChanged
        );
    }
	 

	

    public function resetPasswordById($customerId, $newPassword, $confirmPassword) {

        if ($newPassword != $confirmPassword) {
            throw new InputException(__('Password confirmation doesn\'t match entered password.'));
        }

        try {
            $customer = $this->customerRepository->getById($customerId);
        } catch (Exception $ex) {
            throw new InputException(__('Some Error occurs. Contact Menahub Team'));
        }

        // No need to validate customer and customer address while saving customer reset password token
        $this->disableAddressValidation($customer);
        $this->setIgnoreValidationFlag($customer);

        //Validate Token and new password strength
        // $this->accountManagement->checkPasswordStrength($newPassword);
        //Update secure data
        $customerSecure = $this->customerRegistry->retrieveSecureData($customer->getId());
        $customerSecure->setRpToken(null);
        $customerSecure->setRpTokenCreatedAt(null);
        $customerSecure->setPasswordHash($this->createPasswordHash($newPassword));
        $this->customerRepository->save($customer);

        return true;
    }

    private function disableAddressValidation($customer) {
        foreach ($customer->getAddresses() as $address) {
            $addressModel = $this->addressRegistry->retrieve($address->getId());
            $addressModel->setShouldIgnoreValidation(true);
        }
    }

    private function setIgnoreValidationFlag($customer) {
        $customer->setData('ignore_validation_flag', true);
    }

    protected function createPasswordHash($password) {
        return $this->encryptor->getHash($password, true);
    }

}
