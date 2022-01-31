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

use Cloudinary\Api\Provisioning\UserRole;
use Cloudinary\Api\Exception\ApiError;
use Cloudinary\Api\Exception\NotFound;

/**
 * Class ProvisioningAccountTest
 */
final class ProvisioningAccountTest extends ProvisioningIntegrationTestCase
{
    private static $USER_CREATE_NAME;
    private static $USER_CREATE_EMAIL;
    private static $USER_DELETE_NAME;
    private static $USER_DELETE_EMAIL;
    private static $USER_DELETE_ID;
    private static $USER_GET_NAME;
    private static $USER_GET_EMAIL;
    private static $USER_GET_ID;
    private static $USER_UPDATE_NAME;
    private static $USER_UPDATE_NEW_NAME;
    private static $USER_UPDATE_EMAIL;
    private static $USER_UPDATE_NEW_EMAIL;
    private static $USER_UPDATE_ID;

    private static $SUB_ACCOUNT_GET_NAME;
    private static $SUB_ACCOUNT_GET_ID;
    private static $SUB_ACCOUNT_UPDATE_NAME;
    private static $SUB_ACCOUNT_UPDATE_ID;
    private static $PREFIX;
    private static $PREFIX_NON_EXISTENT;

    private static $USER_GROUP_CREATE_NAME;
    private static $USER_GROUP_GET_NAME;
    private static $USER_GROUP_GET_ID;

    private static $USERS        = [];
    private static $SUB_ACCOUNTS = [];
    private static $USERS_GROUPS = [];

    private static $SUB_ACCOUNT_ID_NON_EXISTENT;

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        if (self::$skipAllTests === true) {
            return;
        }

        self::$PREFIX                      = 'prefix_' . self::$UNIQUE_TEST_ID;
        self::$PREFIX_NON_EXISTENT         = self::$PREFIX . '_non_existent';
        self::$SUB_ACCOUNT_ID_NON_EXISTENT = 'sub_account_non_existent_' . self::$UNIQUE_TEST_ID;

        self::$USER_CREATE_NAME      = 'provisioning_user_name_create_' . self::$UNIQUE_TEST_ID;
        self::$USER_CREATE_EMAIL     = 'provisioning_user_create_' . self::$UNIQUE_TEST_ID . '@cloudinary.com';
        self::$USER_DELETE_NAME      = 'provisioning_user_name_delete_' . self::$UNIQUE_TEST_ID;
        self::$USER_DELETE_EMAIL     = 'provisioning_user_delete_' . self::$UNIQUE_TEST_ID . '@cloudinary.com';
        self::$USER_GET_NAME         = self::$PREFIX . 'provisioning_user_name_get_' . self::$UNIQUE_TEST_ID;
        self::$USER_GET_EMAIL        = 'provisioning_user_get_' . self::$UNIQUE_TEST_ID . '@cloudinary.com';
        self::$USER_UPDATE_NAME      = 'provisioning_user_name_update_' . self::$UNIQUE_TEST_ID;
        self::$USER_UPDATE_NEW_NAME  = 'new_' . self::$USER_UPDATE_NAME;
        self::$USER_UPDATE_EMAIL     = 'provisioning_user_update_' . self::$UNIQUE_TEST_ID . '@cloudinary.com';
        self::$USER_UPDATE_NEW_EMAIL = 'new_' . self::$USER_UPDATE_EMAIL;

        self::$SUB_ACCOUNT_GET_NAME    = self::$PREFIX . 'provisioning_user_name_get_' . self::$UNIQUE_TEST_ID;
        self::$SUB_ACCOUNT_UPDATE_NAME = 'provisioning_user_name_update_' . self::$UNIQUE_TEST_ID;

        self::$USER_GROUP_CREATE_NAME = 'provision_user_group_create_name_' . self::$UNIQUE_TEST_ID;
        self::$USER_GROUP_GET_NAME    = 'provision_user_group_get_name_' . self::$UNIQUE_TEST_ID;

        $subAccount = self::$accountApi->createSubAccount(self::$SUB_ACCOUNT_GET_NAME);
        self::$SUB_ACCOUNTS[] = self::$SUB_ACCOUNT_GET_ID = $subAccount['id'];

        self::assertValidSubAccountUser($subAccount, ['name' => self::$SUB_ACCOUNT_GET_NAME]);

        $subAccount = self::$accountApi->createSubAccount(self::$SUB_ACCOUNT_UPDATE_NAME);
        self::$SUB_ACCOUNTS[] = self::$SUB_ACCOUNT_UPDATE_ID = $subAccount['id'];

        self::assertValidSubAccountUser($subAccount, ['name' => self::$SUB_ACCOUNT_UPDATE_NAME]);

        $userGroup = self::$accountApi->createUserGroup(self::$USER_GROUP_GET_NAME);
        self::$USERS_GROUPS[] = self::$USER_GROUP_GET_ID = $userGroup['id'];

        self::assertValidUserGroup($userGroup);

        $user = self::$accountApi->createUser(
            self::$USER_DELETE_NAME,
            self::$USER_DELETE_EMAIL,
            UserRole::BILLING
        );
        self::$USERS[] = self::$USER_DELETE_ID = $user['id'];

        $user = self::$accountApi->createUser(
            self::$USER_GET_NAME,
            self::$USER_GET_EMAIL,
            UserRole::REPORTS
        );
        self::$USERS[] = self::$USER_GET_ID = $user['id'];

        $user = self::$accountApi->createUser(
            self::$USER_UPDATE_NAME,
            self::$USER_UPDATE_EMAIL,
            UserRole::REPORTS
        );
        self::$USERS[] = self::$USER_UPDATE_ID = $user['id'];

        self::$accountApi->addUserToGroup(self::$USER_GROUP_GET_ID, self::$USER_GET_ID);
    }

    public static function tearDownAfterClass()
    {
        foreach (self::$USERS as $userId) {
            self::cleanupUser($userId);
        }
        foreach (self::$SUB_ACCOUNTS as $subAccountId) {
            self::cleanupSubAccount($subAccountId);
        }
        foreach (self::$USERS_GROUPS as $userGroupId) {
            self::cleanupUserGroup($userGroupId);
        }

        parent::tearDownAfterClass();
    }

    /**
     * Tests getting all user accounts
     */
    public function testGetUsersWithoutParameters()
    {
        $result = self::$accountApi->users();

        self::assertNotEmpty($result['users'][0]);
        self::assertValidAccountUser($result['users'][0]);
    }

    /**
     * Tests getting user accounts by ids
     */
    public function testGetUsersByIds()
    {
        $result = self::$accountApi->users(null, [self::$USER_GET_ID, self::$USER_UPDATE_ID]);

        self::assertCount(2, $result['users']);
        self::assertValidAccountUser($result['users'][0]);
        self::assertValidAccountUser($result['users'][1]);
    }

    /**
     * Tests getting pending user accounts by id
     */
    public function testGetPendingUsersById()
    {
        $result = self::$accountApi->users(true, [self::$USER_GET_ID]);

        self::assertCount(1, $result['users']);
        self::assertValidAccountUser($result['users'][0], ['id' => self::$USER_GET_ID]);
    }

    /**
     * Tests getting non-pending user accounts by id
     */
    public function testGetNonPendingUsersById()
    {
        $result = self::$accountApi->users(false, [self::$USER_GET_ID]);

        self::assertCount(0, $result['users']);
    }

    /**
     * Tests getting pending and non-pending user accounts by id
     */
    public function testGetUsersById()
    {
        $result = self::$accountApi->users(null, [self::$USER_GET_ID]);

        self::assertCount(1, $result['users']);
        self::assertValidAccountUser($result['users'][0], ['id' => self::$USER_GET_ID]);
    }

    /**
     * Tests getting user accounts by prefix
     */
    public function testGetUsersByPrefix()
    {
        $result = self::$accountApi->users(true, [], self::$PREFIX);

        self::assertCount(1, $result['users']);
        self::assertValidAccountUser($result['users'][0], ['id' => self::$USER_GET_ID]);

        $result = self::$accountApi->users(true, [], self::$PREFIX_NON_EXISTENT);

        self::assertCount(0, $result['users']);
    }

    /**
     * Tests getting users by subAccountId
     */
    public function testGetUsersBySubAccountId()
    {
        $result = self::$accountApi->users(true, [], self::$USER_GET_NAME, self::$SUB_ACCOUNT_GET_ID);

        self::assertCount(1, $result['users']);
    }

    /**
     * Gets users by a non-existent sub account id
     */
    public function testGetUsersByNonExistentSubAccountId()
    {
        $this->expectException(NotFound::class);
        $this->expectExceptionMessage('Cannot find sub account with id ' . self::$SUB_ACCOUNT_ID_NON_EXISTENT);

        self::$accountApi->users(true, [], null, self::$SUB_ACCOUNT_ID_NON_EXISTENT);
    }

    /**
     * Tests getting a single user account by id
     */
    public function testGetUserById()
    {
        $user = self::$accountApi->user(self::$USER_GET_ID);

        self::assertValidAccountUser(
            $user,
            [
                'name'   => self::$USER_GET_NAME,
                'email'  => self::$USER_GET_EMAIL,
                'role'   => UserRole::REPORTS,
                'groups' => [
                    [
                        'id'   => self::$USER_GROUP_GET_ID,
                        'name' => self::$USER_GROUP_GET_NAME,
                    ]
                ],
            ]
        );
    }

    /**
     * Tests creating a user account
     */
    public function testCreateUser()
    {
        $subAccountIds = [self::$SUB_ACCOUNT_GET_ID, self::$SUB_ACCOUNT_UPDATE_ID];

        $user = self::$accountApi->createUser(
            self::$USER_CREATE_NAME,
            self::$USER_CREATE_EMAIL,
            UserRole::ADMIN,
            $subAccountIds
        );
        self::$USERS[] = $user['id'];

        self::assertValidAccountUser(
            $user,
            [
                'name'            => self::$USER_CREATE_NAME,
                'email'           => self::$USER_CREATE_EMAIL,
                'role'            => UserRole::ADMIN,
                'sub_account_ids' => implode(',', $subAccountIds)
            ]
        );
    }

    /**
     * Tests updating a user account
     *
     * @throws ApiError
     */
    public function testUpdateUser()
    {
        $subAccountIds = [self::$SUB_ACCOUNT_GET_ID, self::$SUB_ACCOUNT_UPDATE_ID];

        $user = self::$accountApi->updateUser(
            self::$USER_UPDATE_ID,
            self::$USER_UPDATE_NEW_NAME,
            self::$USER_UPDATE_NEW_EMAIL,
            UserRole::TECHNICAL_ADMIN,
            $subAccountIds
        );

        self::assertValidAccountUser(
            $user,
            [
                'name'            => self::$USER_UPDATE_NEW_NAME,
                'email'           => self::$USER_UPDATE_NEW_EMAIL,
                'role'            => UserRole::TECHNICAL_ADMIN,
                'sub_account_ids' => implode(',', $subAccountIds)
            ]
        );
    }

    /**
     * Tests creating a user account
     *
     * @throws ApiError
     */
    public function testDeleteUser()
    {
        $result = self::$accountApi->deleteUser(self::$USER_DELETE_ID);

        self::assertUserDeleted($result);
    }
}
