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

use Cloudinary\Api\Exception\ApiError;
use Cloudinary\Api\Exception\NotFound;

/**
 * Class ProvisioningSubAccountTest
 */
final class ProvisioningSubAccountTest extends ProvisioningIntegrationTestCase
{
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

    private static $SUB_ACCOUNTS = [];

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        if (self::$skipAllTests === true) {
            return;
        }

        self::$PREFIX = 'prefix_' . self::$UNIQUE_TEST_ID;

        self::$SUB_ACCOUNT_CREATE_NAME           = 'provisioning_sub_account_name_create_' . self::$UNIQUE_TEST_ID;
        self::$SUB_ACCOUNT_CREATE_2_NAME         = 'provisioning_sub_account_name_create_2_' . self::$UNIQUE_TEST_ID;
        self::$SUB_ACCOUNT_CREATE_3_NAME         = 'provisioning_sub_account_name_create_3_' . self::$UNIQUE_TEST_ID;
        self::$SUB_ACCOUNT_CREATE_CLOUD_NAME     = 'cloud' . str_replace('_', '-', self::$SUB_ACCOUNT_CREATE_NAME);
        self::$SUB_ACCOUNT_DELETE_NAME           = 'provisioning_sub_account_name_delete_' . self::$UNIQUE_TEST_ID;
        self::$SUB_ACCOUNT_GET_NAME              = self::$PREFIX . 'provisioning_user_name_get_' .
                                                   self::$UNIQUE_TEST_ID;
        self::$SUB_ACCOUNT_UPDATE_NAME           = 'provisioning_user_name_update_' . self::$UNIQUE_TEST_ID;
        self::$SUB_ACCOUNT_UPDATE_NEW_NAME       = 'new_' . self::$SUB_ACCOUNT_UPDATE_NAME;
        self::$SUB_ACCOUNT_UPDATE_NEW_CLOUD_NAME = 'cloud' . str_replace('_', '-', self::$SUB_ACCOUNT_UPDATE_NEW_NAME);
        self::$SUB_ACCOUNT_CUSTOM_ATTRIBUTES     = [
            'key_1_' . self::$UNIQUE_TEST_ID => 'value_1_' . self::$UNIQUE_TEST_ID,
            'key_2_' . self::$UNIQUE_TEST_ID => 'value_2_' . self::$UNIQUE_TEST_ID,
        ];
        self::$SUB_ACCOUNT_NEW_CUSTOM_ATTRIBUTES = [
            'key_3_' . self::$UNIQUE_TEST_ID => 'value_3_' . self::$UNIQUE_TEST_ID
        ];

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
    }

    public static function tearDownAfterClass()
    {
        foreach (self::$SUB_ACCOUNTS as $subAccountId) {
            self::cleanupSubAccount($subAccountId);
        }

        parent::tearDownAfterClass();
    }

    /**
     * Tests getting all user sub accounts.
     */
    public function testGetSubAccountsWithoutParameters()
    {
        $result = self::$accountApi->subAccounts();

        self::assertNotEmpty($result['sub_accounts'][0]);
        self::assertValidSubAccountUser($result['sub_accounts'][0]);
    }

    /**
     * Tests getting user sub accounts by ids.
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
     * Tests getting user sub accounts by prefix.
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
     * Tests getting a single user sub account by id.
     */
    public function testGetSubAccountById()
    {
        $subAccount = self::$accountApi->subAccount(self::$SUB_ACCOUNT_GET_ID);

        self::assertValidSubAccountUser(
            $subAccount,
            [
                'id'   => self::$SUB_ACCOUNT_GET_ID,
                'name' => self::$SUB_ACCOUNT_GET_NAME,
            ]
        );
    }

    /**
     * Tests creating a user sub account.
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
                'name'              => self::$SUB_ACCOUNT_CREATE_NAME,
                'cloud_name'        => self::$SUB_ACCOUNT_CREATE_CLOUD_NAME,
                'custom_attributes' => self::$SUB_ACCOUNT_CUSTOM_ATTRIBUTES,
                'enabled'           => true
            ]
        );
    }

    /**
     * Tests creating a new sub account based on an existing account.
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
                'name'       => self::$SUB_ACCOUNT_CREATE_2_NAME,
                'cloud_name' => str_replace('_', '-', self::$SUB_ACCOUNT_CREATE_2_NAME),
            ]
        );
    }

    /**
     * Tests creating a new sub account based on an existing account with custom attributes.
     */
    public function testCreateSubAccountBasedOnExistingAccountWithCustomAttributes()
    {
        self::markTestSkipped('The custom attributes is not copied from a base sub account');

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
     * Tests deleting a single user sub account by id.
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
     * Tests updating a single user sub account.
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
                'name'              => self::$SUB_ACCOUNT_UPDATE_NEW_NAME,
                'cloud_name'        => self::$SUB_ACCOUNT_UPDATE_NEW_CLOUD_NAME,
                'custom_attributes' => self::$SUB_ACCOUNT_NEW_CUSTOM_ATTRIBUTES,
                'enabled'           => false
            ]
        );
    }
}
