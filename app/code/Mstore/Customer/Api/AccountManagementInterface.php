<?php

namespace Mstore\Customer\Api;

/**
 * Interface AccountManagementInterface
 * @package Mstore\Customer\Api
 * @api
 */
interface AccountManagementInterface {

    /**
     * @param int $customerId
     * @param string $newEmail
     * @param string $currentPassword
     * @return bool true on success
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\InvalidEmailOrPasswordException
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\State\InputMismatchException
     * @throws \Magento\Framework\Exception\State\UserLockedException
     */
    public function changeEmail($customerId, $newEmail, $currentPassword);

    /**
     * Change customer password.
     *
     * @param int $customerId
     * @param string $currentPassword
     * @param string $newPassword
     * @param string $confirmPassword
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function changePasswordById($customerId, $currentPassword, $newPassword, $confirmPassword);

    /**
     * Change customer password.
     *
     * @param int $customerId
     * @param string $newPassword
     * @param string $confirmPassword
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function resetPasswordById($customerId, $newPassword, $confirmPassword);
	  
	/**
     * Change customer password.
     *
     * @param string $mobilenumber
     * @param string $code
	  * @param string $otp
	 * @param string $newPassword
     * @param string $confirmPassword
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function resetPasswordByOtp($mobilenumber,$code,$otp, $newPassword, $confirmPassword);	
	
}
