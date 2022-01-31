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

/**
 * Class ProvisioningUserGroupTest
 */
final class ProvisioningUserGroupTest extends ProvisioningIntegrationTestCase
{
    private static $USER_GET_NAME;
    private static $USER_GET_EMAIL;
    private static $USER_GET_ID;

    private static $USER_GROUP_CREATE_NAME;
    private static $USER_GROUP_DELETE_NAME;
    private static $USER_GROUP_DELETE_ID;
    private static $USER_GROUP_GET_NAME;
    private static $USER_GROUP_GET_ID;
    private static $USER_GROUP_UPDATE_NAME;
    private static $USER_GROUP_UPDATE_NEW_NAME;
    private static $USER_GROUP_UPDATE_ID;

    private static $USERS        = [];
    private static $USERS_GROUPS = [];

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        if (self::$skipAllTests === true) {
            return;
        }

        self::$USER_GET_NAME  = 'provisioning_user_name_get_' . self::$UNIQUE_TEST_ID;
        self::$USER_GET_EMAIL = 'provisioning_user_get_' . self::$UNIQUE_TEST_ID . '@cloudinary.com';

        self::$USER_GROUP_CREATE_NAME     = 'provision_user_group_create_name_' . self::$UNIQUE_TEST_ID;
        self::$USER_GROUP_DELETE_NAME     = 'provision_user_group_delete_name_' . self::$UNIQUE_TEST_ID;
        self::$USER_GROUP_GET_NAME        = 'provision_user_group_get_name_' . self::$UNIQUE_TEST_ID;
        self::$USER_GROUP_UPDATE_NAME     = 'provision_user_group_update_name_' . self::$UNIQUE_TEST_ID;
        self::$USER_GROUP_UPDATE_NEW_NAME = 'new_' . self::$USER_GROUP_UPDATE_NAME;

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
            self::$USER_GET_NAME,
            self::$USER_GET_EMAIL,
            UserRole::REPORTS
        );
        self::$USERS[] = self::$USER_GET_ID = $user['id'];

        self::$accountApi->addUserToGroup(self::$USER_GROUP_GET_ID, self::$USER_GET_ID);
    }

    public static function tearDownAfterClass()
    {
        foreach (self::$USERS as $userId) {
            self::cleanupUser($userId);
        }
        foreach (self::$USERS_GROUPS as $userGroupId) {
            self::cleanupUserGroup($userGroupId);
        }

        parent::tearDownAfterClass();
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
                'id'    => self::$USER_GET_ID,
                'name'  => self::$USER_GET_NAME,
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
                'id'    => self::$USER_GET_ID,
                'name'  => self::$USER_GET_NAME,
                'email' => self::$USER_GET_EMAIL
            ]
        );
    }
}
