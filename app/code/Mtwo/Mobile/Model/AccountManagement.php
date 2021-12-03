<?php
namespace Mtwo\Mobile\Model;

use Magento\Customer\Api\AddressRepositoryInterface;
use Magento\Customer\Api\CustomerMetadataInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\ValidationResultsInterfaceFactory;
use Magento\Customer\Helper\View as CustomerViewHelper;
use Magento\Customer\Model\Config\Share as ConfigShare;
use Magento\Customer\Model\Customer as CustomerModel;
use Magento\Customer\Model\CustomerFactory;
use Magento\Customer\Model\CustomerRegistry;
use Magento\Customer\Model\Metadata\Validator;
use Magento\Framework\Api\ExtensibleDataObjectConverter;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\DataObjectFactory as ObjectFactory;
use Magento\Framework\Encryption\EncryptorInterface as Encryptor;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\Exception\EmailNotConfirmedException;
use Magento\Framework\Exception\InvalidEmailOrPasswordException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\State\UserLockedException;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Math\Random;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Framework\Registry;
use Magento\Framework\Stdlib\DateTime;
use Magento\Framework\Stdlib\StringUtils as StringHelper;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface as PsrLogger;
use Magento\Framework\App\State;
use Magento\Customer\Model\Customer\CredentialsValidator;
use Magento\Framework\Intl\DateTimeFactory;
use Magento\Customer\Model\AccountConfirmation;
use Magento\Framework\Session\SessionManagerInterface;
use Magento\Framework\Session\SaveHandlerInterface;
use Magento\Customer\Model\ResourceModel\Visitor\CollectionFactory;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Customer\Model\AddressRegistry;
use Magento\Customer\Model\SessionCleanerInterface;
use Magento\Customer\Model\ForgotPasswordToken\GetCustomerByToken;
use Magento\Directory\Model\AllowedCountries;
use Magento\Framework\App\ObjectManager;

class AccountManagement extends \Magento\Customer\Model\AccountManagement
{
    private $customerRepository;
    private $customerFactory;
    private $eventManager;
    private $helperData;
    private $emailNotification;

    public function __construct(
        CustomerFactory $customerFactory,
        ManagerInterface $eventManager,
        StoreManagerInterface $storeManager,
        Random $mathRandom,
        Validator $validator,
        ValidationResultsInterfaceFactory $validationResultsDataFactory,
        AddressRepositoryInterface $addressRepository,
        CustomerMetadataInterface $customerMetadataService,
        CustomerRegistry $customerRegistry,
        PsrLogger $logger,
        Encryptor $encryptor,
        ConfigShare $configShare,
        StringHelper $stringHelper,
        CustomerRepositoryInterface $customerRepository,
        ScopeConfigInterface $scopeConfig,
        TransportBuilder $transportBuilder,
        DataObjectProcessor $dataProcessor,
        Registry $registry,
        CustomerViewHelper $customerViewHelper,
        DateTime $dateTime,
        CustomerModel $customerModel,
        ObjectFactory $objectFactory,
        ExtensibleDataObjectConverter $extensibleDataObjectConverter,
		//CredentialsValidator $credentialsValidator = null,
       // DateTimeFactory $dateTimeFactory = null,
      // AccountConfirmation $accountConfirmation = null,
      //  SessionManagerInterface $sessionManager = null,
     //   SaveHandlerInterface $saveHandler = null,
      //  CollectionFactory $visitorCollectionFactory = null,
      //  SearchCriteriaBuilder $searchCriteriaBuilder = null,
        AddressRegistry $addressRegistry = null,
       // GetCustomerByToken $getByToken = null,
      //  AllowedCountries $allowedCountriesReader = null,
      //  SessionCleanerInterface $sessionCleaner = null,
		State $state
      ) {
        parent::__construct(
            $customerFactory,
            $eventManager,
            $storeManager,
            $mathRandom,
            $validator,
            $validationResultsDataFactory,
            $addressRepository,
            $customerMetadataService,
            $customerRegistry,
            $logger,
            $encryptor,
            $configShare,
            $stringHelper,
            $customerRepository,
            $scopeConfig,
            $transportBuilder,
            $dataProcessor,
            $registry,
            $customerViewHelper,
            $dateTime,
            $customerModel,
            $objectFactory,
            $extensibleDataObjectConverter
        );
        $this->customerRepository = $customerRepository;
        $this->customerFactory = $customerFactory;
        $this->eventManager = $eventManager;
      //  $this->helperData = $helperData;
				$this->state = $state;
		$this->code =   $this->state->getAreaCode();
	 $objectManager = ObjectManager::getInstance();
      
        $this->addressRegistry = $addressRegistry
            ?: $objectManager->get(AddressRegistry::class);
               $this->mathRandom = $mathRandom;

    }
	
	public function initiatePasswordReset($email, $template, $websiteId = null)
    {
		  
		if($this->code !='webapi_rest'){
			 return  parent::initiatePasswordReset($email, $template, $websiteId);
		}
        if ($websiteId === null) {
            $websiteId = $this->storeManager->getStore()->getWebsiteId();
        }
        // load customer by email
        $customer = $this->customerRepository->get($email, $websiteId);

        // No need to validate customer address while saving customer reset password token
        $this->disableAddressValidation($customer);

 		$newPasswordToken = $this->mathRandom->getRandomString(6,$this->mathRandom::CHARS_DIGITS);
        $this->changeResetPasswordLinkToken($customer, $newPasswordToken);

        try {
            switch ($template) {
                case AccountManagement::EMAIL_REMINDER:
                    $this->getEmailNotification()->passwordReminder($customer);
                    break;
                case AccountManagement::EMAIL_RESET:
                    $this->getEmailNotification()->passwordResetConfirmation($customer);
                    break;
                default:
                    $this->handleUnknownTemplate($template);
                    break;
            }
            return true;
        } catch (MailException $e) {
            // If we are not able to send a reset password email, this should be ignored
            $this->logger->critical($e);
        }
        return false;
    }

	 private function disableAddressValidation($customer)
    {
        foreach ($customer->getAddresses() as $address) {
            $addressModel = $this->addressRegistry->retrieve($address->getId());
            $addressModel->setShouldIgnoreValidation(true);
        }
    }
	 private function getEmailNotification()
    {
        if (!($this->emailNotification instanceof \Magento\Customer\Model\EmailNotificationInterface)) {
            return \Magento\Framework\App\ObjectManager::getInstance()->get(
                 \Magento\Customer\Model\EmailNotificationInterface::class
            );
        } else {
            return $this->emailNotification;
        }
    }
    
}
