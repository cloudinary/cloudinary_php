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
use PHPUnit\Framework\Constraint\IsType;
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
            self::$skipAllTests = true;
            self::$skipReason  = $ce->getMessage();
        }
    }

    public function setUp()
    {
        parent::setUp();

        if (self::$skipAllTests === true) {
            self::markTestSkipped("Skipping provisioning API test, reason: " . static::$skipReason);
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

    /**
     * Assert that a given object is a valid user account.
     * Optionally checks it against given values.
     *
     * @param mixed $user
     * @param array $values
     */
    protected static function assertValidAccountUser($user, $values = [])
    {
        self::assertObjectStructure(
            $user,
            [
                'id' => IsType::TYPE_STRING,
                'name' => IsType::TYPE_STRING,
                'role' => IsType::TYPE_STRING,
                'email' => IsType::TYPE_STRING,
                'pending' => IsType::TYPE_BOOL,
                'enabled' => IsType::TYPE_BOOL,
                'created_at' => IsType::TYPE_STRING,
                'all_sub_accounts' => IsType::TYPE_BOOL
            ]
        );

        if (isset($user['groups'])) {
            foreach ($user['groups'] as $group) {
                self::assertValidUserGroup($group);
            }
        }

        if (isset($user['sub_account_ids'])) {
            self::assertIsString($user['sub_account_ids']);
        }

        foreach ($values as $key => $value) {
            self::assertEquals($value, $user[$key]);
        }
    }

    /**
     * Assert that a given object is a valid user sub account.
     * Optionally checks it against given values.
     *
     * @param mixed $user
     * @param array $values
     */
    protected static function assertValidSubAccountUser($user, $values = [])
    {
        self::assertObjectStructure(
            $user,
            [
                'id' => IsType::TYPE_STRING,
                'name' => IsType::TYPE_STRING,
                'description' => [IsType::TYPE_STRING, IsType::TYPE_NULL],
                'cloud_name' => IsType::TYPE_STRING,
                'api_access_keys' => IsType::TYPE_ARRAY,
                'enabled' => IsType::TYPE_BOOL,
                'created_at' => IsType::TYPE_STRING,
                'custom_attributes' => [IsType::TYPE_ARRAY, IsType::TYPE_NULL]
            ]
        );

        foreach ($user['api_access_keys'] as $api_access_keys) {
            self::assertObjectStructure(
                $api_access_keys,
                [
                    'key' => IsType::TYPE_STRING,
                    'secret' => IsType::TYPE_STRING,
                    'enabled' => IsType::TYPE_BOOL
                ]
            );
        }

        foreach ($values as $key => $value) {
            self::assertEquals($value, $user[$key]);
        }
    }

    /**
     * Assert that a given object is a valid user group.
     * Optionally checks it against given values.
     *
     * @param mixed $userGroup
     * @param array $values
     */
    protected static function assertValidUserGroup($userGroup, $values = [])
    {
        self::assertObjectStructure(
            $userGroup,
            [
                'id' => IsType::TYPE_STRING,
                'name' => IsType::TYPE_STRING
            ]
        );

        foreach ($values as $key => $value) {
            self::assertEquals($value, $userGroup[$key]);
        }
    }

    /**
     * Assert that a given object is a valid user in a group.
     * Optionally checks it against given values.
     *
     * @param mixed $user
     * @param array $values
     */
    protected static function assertValidUserGroupUsers($user, $values = [])
    {
        self::assertObjectStructure(
            $user,
            [
                'id' => IsType::TYPE_STRING,
                'name' => IsType::TYPE_STRING,
                'email' => IsType::TYPE_STRING
            ]
        );

        foreach ($values as $key => $value) {
            self::assertEquals($value, $user[$key]);
        }
    }

    /**
     * Assert that a sub account was deleted.
     *
     * @param $result
     */
    protected static function assertSubAccountDeleted($result)
    {
        self::assertEquals('ok', $result['message']);
    }

    /**
     * Assert that a user was deleted.
     *
     * @param $result
     */
    protected static function assertUserDeleted($result)
    {
        self::assertEquals('ok', $result['message']);
    }

    /**
     * Assert that a user group was deleted.
     *
     * @param $result
     */
    protected static function assertUserGroupDeleted($result)
    {
        self::assertTrue($result['ok']);
    }
}
