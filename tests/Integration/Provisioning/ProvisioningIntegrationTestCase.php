<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Test\Integration\Provisioning;

use Cloudinary\Api\Provisioning\AccountApi;
use Cloudinary\Exception\ConfigurationException;
use Cloudinary\Test\CloudinaryTestCase;
use Exception;
use RuntimeException;

/**
 * Class ProvisioningIntegrationTestCase
 */
abstract class ProvisioningIntegrationTestCase extends CloudinaryTestCase
{
    /**
     * @var AccountApi
     */
    protected static $accountApi;

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        try {
            self::$accountApi = new AccountApi();
        } catch (ConfigurationException $ce) {
            static::$skipAllTests = true;
            static::$skipReason  = $ce->getMessage();
        }
    }

    public function setUp()
    {
        parent::setUp();

        if (static::$skipAllTests === true) {
            $this->markTestSkipped("Skipping provisioning API test, reason: " . static::$skipReason);
        }
    }


    /**
     * Delete an account user
     *
     * Try to delete an account user if deletion fails log the error
     *
     * @param string $userId
     */
    protected static function cleanupUser($userId)
    {
        self::cleanupSoftly(
            [self::$accountApi, 'deleteUser'],
            'An account user ' . $userId . ' deletion failed during teardown',
            static function ($result) {
                return ! isset($result['message']) || $result['message'] !== 'ok';
            },
            $userId
        );
    }

    /**
     * Delete a sub account
     *
     * Try to delete a sub account if deletion fails log the error
     *
     * @param string $subAccountId
     */
    protected static function cleanupSubAccount($subAccountId)
    {
        self::cleanupSoftly(
            [self::$accountApi, 'deleteSubAccount'],
            'A sub account ' . $subAccountId . ' deletion failed during teardown',
            static function ($result) {
                return ! isset($result['message']) || $result['message'] !== 'ok';
            },
            $subAccountId
        );
    }

    /**
     * Delete a user group
     *
     * Try to delete a user group if deletion fails log the error
     *
     * @param string $userGroupId
     */
    protected static function cleanupUserGroup($userGroupId)
    {
        self::cleanupSoftly(
            [self::$accountApi, 'deleteUserGroup'],
            'A user group ' . $userGroupId . ' deletion failed during teardown',
            static function ($result) {
                return ! isset($result['ok']) || $result['ok'] === true;
            },
            $userGroupId
        );
    }

    /**
     * @param string|array $function
     * @param string       $message
     * @param callable     $invalidResult
     */
    private static function cleanupSoftly($function, $message, $invalidResult)
    {
        $args = array_slice(func_get_args(), 3);
        try {
            $result = call_user_func_array(
                is_array($function) ? $function : [self::$accountApi, $function],
                $args
            );
            if ($invalidResult($result)) {
                throw new RuntimeException($message);
            }
        } catch (Exception $e) {
            //@TODO: Use logger to print ERROR message
        }
    }
}
