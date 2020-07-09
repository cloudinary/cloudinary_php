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
use PHPUnit_Framework_Constraint_IsType as IsType;

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

    private static $SUB_ACCOUNT_CREATE_NAME;
    private static $SUB_ACCOUNT_CREATE_CLOUD_NAME;
    private static $SUB_ACCOUNT_CREATE_2_NAME;
    private static $SUB_ACCOUNT_CREATE_3_NAME;
    private static $SUB_ACCOUNT_DELETE_NAME;
    private static $SUB_ACCOUNT_DELETE_ID;
    private static $SUB_ACCOUNT_GET_NAME;
    private static $SUB_ACCOUNT_GET_ID;
    private static $SUB_ACCOUNT_UPDATE_NAME;
    private static $SUB_ACCOUNT_UPDATE_NEW_NAME;
    private static $SUB_ACCOUNT_UPDATE_NEW_CLOUD_NAME;
    private static $SUB_ACCOUNT_UPDATE_ID;
    private static $SUB_ACCOUNT_CUSTOM_ATTRIBUTES;
    private static $SUB_ACCOUNT_NEW_CUSTOM_ATTRIBUTES;
    private static $PREFIX;

    private static $USER_GROUP_CREATE_NAME;
    private static $USER_GROUP_DELETE_NAME;
    private static $USER_GROUP_DELETE_ID;
    private static $USER_GROUP_GET_NAME;
    private static $USER_GROUP_GET_ID;
    private static $USER_GROUP_UPDATE_NAME;
    private static $USER_GROUP_UPDATE_NEW_NAME;
    private static $USER_GROUP_UPDATE_ID;

    private static $USERS = [];
    private static $SUB_ACCOUNTS = [];
    private static $USERS_GROUPS = [];

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        if (static::$skipAllTests === true) {
            return;
        }

        self::$PREFIX = 'prefix_' . self::$UNIQUE_TEST_ID;

        self::$USER_CREATE_NAME = 'provisioning_user_name_create_' . self::$UNIQUE_TEST_ID;
        self::$USER_CREATE_EMAIL = 'provisioning_user_create_' . self::$UNIQUE_TEST_ID . '@cloudinary.com';
        self::$USER_DELETE_NAME = 'provisioning_user_name_delete_' . self::$UNIQUE_TEST_ID;
        self::$USER_DELETE_EMAIL = 'provisioning_user_delete_' . self::$UNIQUE_TEST_ID . '@cloudinary.com';
        self::$USER_GET_NAME = self::$PREFIX . 'provisioning_user_name_get_' . self::$UNIQUE_TEST_ID;
        self::$USER_GET_EMAIL = 'provisioning_user_get_' . self::$UNIQUE_TEST_ID . '@cloudinary.com';
        self::$USER_UPDATE_NAME = 'provisioning_user_name_update_' . self::$UNIQUE_TEST_ID;
        self::$USER_UPDATE_NEW_NAME = 'new_' . self::$USER_UPDATE_NAME;
        self::$USER_UPDATE_EMAIL = 'provisioning_user_update_' . self::$UNIQUE_TEST_ID . '@cloudinary.com';
        self::$USER_UPDATE_NEW_EMAIL = 'new_' . self::$USER_UPDATE_EMAIL;

        self::$SUB_ACCOUNT_CREATE_NAME = 'provisioning_sub_account_name_create_' . self::$UNIQUE_TEST_ID;
        self::$SUB_ACCOUNT_CREATE_2_NAME = 'provisioning_sub_account_name_create_2_' . self::$UNIQUE_TEST_ID;
        self::$SUB_ACCOUNT_CREATE_3_NAME = 'provisioning_sub_account_name_create_3_' . self::$UNIQUE_TEST_ID;
        self::$SUB_ACCOUNT_CREATE_CLOUD_NAME = 'cloud' . str_replace('_', '-', self::$SUB_ACCOUNT_CREATE_NAME);
        self::$SUB_ACCOUNT_DELETE_NAME = 'provisioning_sub_account_name_delete_' . self::$UNIQUE_TEST_ID;
        self::$SUB_ACCOUNT_GET_NAME = self::$PREFIX . 'provisioning_user_name_get_' . self::$UNIQUE_TEST_ID;
        self::$SUB_ACCOUNT_UPDATE_NAME = 'provisioning_user_name_update_' . self::$UNIQUE_TEST_ID;
        self::$SUB_ACCOUNT_UPDATE_NEW_NAME = 'new_' . self::$SUB_ACCOUNT_UPDATE_NAME;
        self::$SUB_ACCOUNT_UPDATE_NEW_CLOUD_NAME = 'cloud' . str_replace('_', '-', self::$SUB_ACCOUNT_UPDATE_NEW_NAME);
        self::$SUB_ACCOUNT_CUSTOM_ATTRIBUTES = [
            'key_1_' . self::$UNIQUE_TEST_ID => 'value_1_' . self::$UNIQUE_TEST_ID,
            'key_2_' . self::$UNIQUE_TEST_ID => 'value_2_' . self::$UNIQUE_TEST_ID,
        ];
        self::$SUB_ACCOUNT_NEW_CUSTOM_ATTRIBUTES = [
            'key_3_' . self::$UNIQUE_TEST_ID => 'value_3_' . self::$UNIQUE_TEST_ID
        ];

        self::$USER_GROUP_CREATE_NAME = 'provision_user_group_create_name_' . self::$UNIQUE_TEST_ID;
        self::$USER_GROUP_DELETE_NAME = 'provision_user_group_delete_name_' . self::$UNIQUE_TEST_ID;
        self::$USER_GROUP_GET_NAME = 'provision_user_group_get_name_' . self::$UNIQUE_TEST_ID;
        self::$USER_GROUP_UPDATE_NAME = 'provision_user_group_update_name_' . self::$UNIQUE_TEST_ID;
        self::$USER_GROUP_UPDATE_NEW_NAME = 'new_' . self::$USER_GROUP_UPDATE_NAME;

        $subAccount = self::$accountApi->createSubAccount(self::$SUB_ACCOUNT_GET_NAME);
        self::$SUB_ACCOUNTS[] = self::$SUB_ACCOUNT_GET_ID = $subAccount['id'];

        self::assertValidSubAccountUser($subAccount, ['name' => self::$SUB_ACCOUNT_GET_NAME]);

        $subAccount = self::$accountApi->createSubAccount(self::$SUB_ACCOUNT_DELETE_NAME);
        self::$SUB_ACCOUNTS[] = self::$SUB_ACCOUNT_DELETE_ID = $subAccount['id'];

        self::assertValidSubAccountUser($subAccount, ['name' => self::$SUB_ACCOUNT_DELETE_NAME]);

        $subAccount = self::$accountApi->createSubAccount(
            self::$SUB_ACCOUNT_UPDATE_NAME,
            null,
            self::$SUB_ACCOUNT_CUSTOM_ATTRIBUTES
        );
        self::$SUB_ACCOUNTS[] = self::$SUB_ACCOUNT_UPDATE_ID = $subAccount['id'];

        self::assertValidSubAccountUser($subAccount, ['name' => self::$SUB_ACCOUNT_UPDATE_NAME]);

        $userGroup = self::$accountApi->createUserGroup(self::$USER_GROUP_DELETE_NAME);
        self::$USERS_GROUPS[] = self::$USER_GROUP_DELETE_ID = $userGroup['id'];

        self::assertValidUserGroup($userGroup);

        $userGroup = self::$accountApi->createUserGroup(self::$USER_GROUP_GET_NAME);
        self::$USERS_GROUPS[] = self::$USER_GROUP_GET_ID = $userGroup['id'];

        self::assertValidUserGroup($userGroup);

        $userGroup = self::$accountApi->createUserGroup(self::$USER_GROUP_UPDATE_NAME);
        self::$USERS_GROUPS[] = self::$USER_GROUP_UPDATE_ID = $userGroup['id'];

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
     * Tests getting user accounts by id
     */
    public function testGetUsersListById()
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
        $result = self::$accountApi->users(null, [], self::$PREFIX);

        self::assertCount(1, $result['users']);
        self::assertValidAccountUser($result['users'][0], ['id' => self::$USER_GET_ID]);
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
                'name' => self::$USER_GET_NAME,
                'email' => self::$USER_GET_EMAIL,
                'role' => UserRole::REPORTS,
                'groups' => [
                    [
                        'id' => self::$USER_GROUP_GET_ID,
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
                'name' => self::$USER_CREATE_NAME,
                'email' => self::$USER_CREATE_EMAIL,
                'role' => UserRole::ADMIN,
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
                'name' => self::$USER_UPDATE_NEW_NAME,
                'email' => self::$USER_UPDATE_NEW_EMAIL,
                'role' => UserRole::TECHNICAL_ADMIN,
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

    /**
     * Tests getting all user sub accounts
     */
    public function testGetSubAccountsWithoutParameters()
    {
        $result = self::$accountApi->subAccounts();

        self::assertNotEmpty($result['sub_accounts'][0]);
        self::assertValidSubAccountUser($result['sub_accounts'][0]);
    }

    /**
     * Tests getting user sub accounts by ids
     */
    public function testGetSubAccountsByIDs()
    {
        $result = self::$accountApi->subAccounts(
            null,
            [
                self::$SUB_ACCOUNTS[0],
                self::$SUB_ACCOUNTS[1]
            ]
        );

        self::assertCount(2, $result['sub_accounts']);
        self::assertValidSubAccountUser($result['sub_accounts'][0]);
    }

    /**
     * Tests getting user sub accounts by prefix
     */
    public function testGetSubAccountsByPrefix()
    {
        $result = self::$accountApi->subAccounts(null, null, self::$PREFIX);

        self::assertCount(1, $result['sub_accounts']);
        self::assertValidSubAccountUser(
            $result['sub_accounts'][0],
            [
                'name' => self::$SUB_ACCOUNT_GET_NAME
            ]
        );
    }

    /**
     * Tests getting a single user sub account by id
     */
    public function testGetSubAccountById()
    {
        $subAccount = self::$accountApi->subAccount(self::$SUB_ACCOUNT_GET_ID);

        self::assertValidSubAccountUser(
            $subAccount,
            [
                'id' => self::$SUB_ACCOUNT_GET_ID,
                'name' => self::$SUB_ACCOUNT_GET_NAME,
            ]
        );
    }

    /**
     * Tests creating a user sub account
     */
    public function testCreateSubAccount()
    {
        $subAccount = self::$accountApi->createSubAccount(
            self::$SUB_ACCOUNT_CREATE_NAME,
            self::$SUB_ACCOUNT_CREATE_CLOUD_NAME,
            self::$SUB_ACCOUNT_CUSTOM_ATTRIBUTES
        );
        self::$SUB_ACCOUNTS[] = $subAccount['id'];

        self::assertValidSubAccountUser(
            $subAccount,
            [
                'name' => self::$SUB_ACCOUNT_CREATE_NAME,
                'cloud_name' => self::$SUB_ACCOUNT_CREATE_CLOUD_NAME,
                'custom_attributes' => self::$SUB_ACCOUNT_CUSTOM_ATTRIBUTES,
                'enabled' => true
            ]
        );
    }

    /**
     * Tests creating a new sub account based on an existing account
     */
    public function testCreateSubAccountBasedOnExistingAccount()
    {
        $subAccount = self::$accountApi->createSubAccount(
            self::$SUB_ACCOUNT_CREATE_2_NAME,
            null,
            null,
            null,
            self::$SUB_ACCOUNT_UPDATE_ID
        );
        self::$SUB_ACCOUNTS[] = $subAccount['id'];

        self::assertNotEquals(self::$SUB_ACCOUNT_UPDATE_ID, $subAccount['id']);
        self::assertValidSubAccountUser(
            $subAccount,
            [
                'name' => self::$SUB_ACCOUNT_CREATE_2_NAME,
                'cloud_name' => str_replace('_', '-', self::$SUB_ACCOUNT_CREATE_2_NAME),
            ]
        );
    }

    /**
     * Tests creating a new sub account based on an existing account with custom attributes
     */
    public function testCreateSubAccountBasedOnExistingAccountWithCustomAttributes()
    {
        $this->markTestSkipped('The custom attributes is not copied from a base sub account');

        $subAccount = self::$accountApi->createSubAccount(
            self::$SUB_ACCOUNT_CREATE_3_NAME,
            null,
            null,
            null,
            self::$SUB_ACCOUNT_UPDATE_ID
        );
        self::$SUB_ACCOUNTS[] = $subAccount['id'];

        self::assertValidSubAccountUser(
            $subAccount,
            [
                'custom_attributes' => self::$SUB_ACCOUNT_CUSTOM_ATTRIBUTES
            ]
        );
    }

    /**
     * Tests deleting a single user sub account by id
     *
     * @throws ApiError
     */
    public function testDeleteSubAccount()
    {
        $result = self::$accountApi->deleteSubAccount(self::$SUB_ACCOUNT_DELETE_ID);

        self::assertSubAccountDeleted($result);

        $this->expectException(NotFound::class);
        self::$accountApi->subAccount(self::$SUB_ACCOUNT_DELETE_ID);
    }

    /**
     * Tests updating a single user sub account
     *
     * @throws ApiError
     */
    public function testUpdateSubAccount()
    {
        $subAccount = self::$accountApi->updateSubAccount(
            self::$SUB_ACCOUNT_UPDATE_ID,
            self::$SUB_ACCOUNT_UPDATE_NEW_NAME,
            self::$SUB_ACCOUNT_UPDATE_NEW_CLOUD_NAME,
            self::$SUB_ACCOUNT_NEW_CUSTOM_ATTRIBUTES,
            false
        );

        self::assertValidSubAccountUser(
            $subAccount,
            [
                'name' => self::$SUB_ACCOUNT_UPDATE_NEW_NAME,
                'cloud_name' => self::$SUB_ACCOUNT_UPDATE_NEW_CLOUD_NAME,
                'custom_attributes' => self::$SUB_ACCOUNT_NEW_CUSTOM_ATTRIBUTES,
                'enabled' => false
            ]
        );
    }

    /**
     * Tests getting all user groups
     */
    public function testGetUserGroups()
    {
        $result = self::$accountApi->userGroups();

        self::assertNotEmpty($result['user_groups'][0]);
        self::assertValidUserGroup($result['user_groups'][0]);
    }

    /**
     * Tests getting a single user group by id
     */
    public function testGetUserGroupById()
    {
        $userGroup = self::$accountApi->userGroup(self::$USER_GROUP_GET_ID);

        self::assertValidUserGroup($userGroup);
    }

    /**
     * Tests creating a user group
     */
    public function testCreateUserGroup()
    {
        $userGroup = self::$accountApi->createUserGroup(self::$USER_GROUP_CREATE_NAME);
        self::$USERS_GROUPS[] = $userGroup['id'];

        self::assertValidUserGroup(
            $userGroup,
            [
                'name' => self::$USER_GROUP_CREATE_NAME
            ]
        );
    }

    /**
     * Tests updating a user group
     *
     * @throws ApiError
     */
    public function testUpdatingUserGroup()
    {
        $userGroup = self::$accountApi->updateUserGroup(self::$USER_GROUP_UPDATE_ID, self::$USER_GROUP_UPDATE_NEW_NAME);

        self::assertValidUserGroup(
            $userGroup,
            [
                'name' => self::$USER_GROUP_UPDATE_NEW_NAME
            ]
        );
    }

    /**
     * Tests deleting a single user group
     *
     * @throws ApiError
     */
    public function testDeleteUserGroup()
    {
        $result = self::$accountApi->deleteUserGroup(self::$USER_GROUP_DELETE_ID);

        self::assertUserGroupDeleted($result);
    }

    /**
     * Tests adding a user to a group
     */
    public function testAddUserToGroup()
    {
        $result = self::$accountApi->addUserToGroup(self::$USER_GROUP_UPDATE_ID, self::$USER_GET_ID);

        self::assertCount(1, $result['users']);
        self::assertValidUserGroupUsers(
            $result['users'][0],
            [
                'id' => self::$USER_GET_ID,
                'name' => self::$USER_GET_NAME,
                'email' => self::$USER_GET_EMAIL
            ]
        );
    }

    /**
     * Tests removing a user from a group
     *
     * @throws ApiError
     */
    public function testRemoveUserFromGroup()
    {
        $result = self::$accountApi->removeUserFromGroup(self::$USER_GROUP_UPDATE_ID, self::$USER_GET_ID);

        self::assertCount(0, $result['users']);
    }

    /**
     * Tests getting a list of users belonging to a user group
     */
    public function testUserGroupUsers()
    {
        $result = self::$accountApi->userGroupUsers(self::$USER_GROUP_GET_ID);

        self::assertCount(1, $result['users']);
        self::assertValidUserGroupUsers(
            $result['users'][0],
            [
                'id' => self::$USER_GET_ID,
                'name' => self::$USER_GET_NAME,
                'email' => self::$USER_GET_EMAIL
            ]
        );
    }

    /**
     * Assert that a given object is a valid user account.
     * Optionally checks it against given values.
     *
     * @param mixed $user
     * @param array $values
     */
    private static function assertValidAccountUser($user, $values = [])
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
            self::assertInternalType(IsType::TYPE_STRING, $user['sub_account_ids']);
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
    private static function assertValidSubAccountUser($user, $values = [])
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
    private static function assertValidUserGroup($userGroup, $values = [])
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
    private static function assertValidUserGroupUsers($user, $values = [])
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
    private static function assertSubAccountDeleted($result)
    {
        self::assertEquals('ok', $result['message']);
    }

    /**
     * Assert that a user was deleted.
     *
     * @param $result
     */
    private static function assertUserDeleted($result)
    {
        self::assertEquals('ok', $result['message']);
    }

    /**
     * Assert that a user group was deleted.
     *
     * @param $result
     */
    private static function assertUserGroupDeleted($result)
    {
        self::assertTrue($result['ok']);
    }
}
