<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Api\Provisioning;

use Cloudinary\Api\ApiResponse;
use Cloudinary\Api\ApiUtils;
use Cloudinary\Api\Exception\ApiError;
use Cloudinary\ArrayUtils;
use Cloudinary\Configuration\Provisioning\ProvisioningConfiguration;

/**
 * Class AccountApi
 *
 * Entry point class for all account and provisioning API actions: Manage users, cloud names and user groups.
 *
 * @api
 */
class AccountApi
{
    protected AccountApiClient $accountApiClient;

    /**
     * AccountApi constructor.
     *
     */
    public function __construct(?ProvisioningConfiguration $configuration = null)
    {
        $this->accountApiClient = new AccountApiClient($configuration);
    }

    /**
     * Gets details of a specific user.
     *
     * @param string $userId The id of the user to fetch.
     *
     * @return ApiResponse Details of the user.
     *
     * @api
     */
    public function user(string $userId): ApiResponse
    {
        $uri = [AccountEndPoint::USERS, $userId];

        return $this->accountApiClient->get($uri);
    }

    /**
     * Gets a list of the users according to filters.
     *
     * @param bool|null   $pending      Whether to fetch pending users. Default all.
     * @param array       $userIds      List of user IDs. Up to 100.
     * @param string|null $prefix       Search by prefix of the user's name or email. Case-insensitive.
     * @param string|null $subAccountId Return only users who have access to the given sub-account.
     *
     * @return ApiResponse List of users associated with the account.
     *
     * @api
     */
    public function users(
        ?bool $pending = null,
        array $userIds = [],
        ?string $prefix = null,
        ?string $subAccountId = null
    ): ApiResponse {
        $uri = [AccountEndPoint::USERS];

        $params = [
            'pending'        => $pending,
            'ids'            => ApiUtils::serializeSimpleApiParam($userIds),
            'prefix'         => $prefix,
            'sub_account_id' => $subAccountId,
        ];

        return $this->accountApiClient->get($uri, $params);
    }

    /**
     * Creates a new user.
     *
     * @param string $name            Username.
     * @param string $email           User's email.
     * @param string $role            User's role.
     * @param array  $subAccountIds   Sub-accounts for which the user should have access.
     *                                If not provided or empty, user should have access to all accounts.
     *
     * @return ApiResponse Details of created user.
     */
    public function createUser(string $name, string $email, string $role, array $subAccountIds = []): ApiResponse
    {
        $uri = [AccountEndPoint::USERS];

        $params = [
            'name'            => $name,
            'email'           => $email,
            'role'            => $role,
            'sub_account_ids' => $subAccountIds,
        ];

        return $this->accountApiClient->postJson($uri, $params);
    }

    /**
     * Updates an existing user.
     *
     * @param string $userId          The id of the user to update.
     * @param string $name            Username.
     * @param string $email           User's email.
     * @param string $role            User's role.
     * @param array  $subAccountIds   Sub-accounts for which the user should have access.
     *                                If not provided or empty, user should have access to all accounts.
     *
     * @return ApiResponse The updated user details.
     *
     * @api
     */
    public function updateUser(
        string $userId,
        string $name,
        string $email,
        string $role,
        array $subAccountIds = []
    ): ApiResponse {
        $uri = [AccountEndPoint::USERS, $userId];

        $params = [
            'name'            => $name,
            'email'           => $email,
            'role'            => $role,
            'sub_account_ids' => $subAccountIds,
        ];

        return $this->accountApiClient->putJson($uri, $params);
    }

    /**
     * Deletes a user.
     *
     * @param string $userId Id of the user to delete.
     *
     * @return ApiResponse Result message.
     *
     * @api
     */
    public function deleteUser(string $userId): ApiResponse
    {
        $uri = [AccountEndPoint::USERS, $userId];

        return $this->accountApiClient->delete($uri);
    }

    /**
     * Lists all sub-accounts.
     *
     * @param bool|null   $enabled Whether to only return enabled sub-accounts (true) or disabled accounts (false).
     *                             Default: all accounts are returned (both enabled and disabled).
     * @param array|null  $ids     List of sub-account IDs. Up to 100. When provided, other filters are ignored.
     * @param string|null $prefix  Search by prefix of the sub-account name. Case-insensitive.
     *
     * @return ApiResponse A list of sub accounts
     *
     * @api
     */
    public function subAccounts(?bool $enabled = null, ?array $ids = [], ?string $prefix = null): ApiResponse
    {
        $uri = [AccountEndPoint::SUB_ACCOUNTS];

        $params = [
            'enabled' => $enabled,
            'ids'     => ApiUtils::serializeSimpleApiParam($ids),
            'prefix'  => $prefix,
        ];

        return $this->accountApiClient->get($uri, $params);
    }

    /**
     * Creates a new sub account.
     *
     * @param string      $name             Name of the new sub account.
     * @param string|null $cloudName        A case-insensitive cloud name comprised of alphanumeric and underscore.
     *                                      characters. Generates an error if the cloud name is not unique across all
     *                                      Cloudinary accounts.
     * @param array|null  $customAttributes Any custom attributes you want to associate with the sub-account.
     * @param bool|null   $enabled          Whether to create the account as enabled (default is enabled).
     * @param string|null $baseAccount      ID of sub-account from which to copy settings.
     *
     * @return ApiResponse The created sub account.
     *
     * @api
     */
    public function createSubAccount(
        string $name,
        ?string $cloudName = null,
        ?array $customAttributes = null,
        ?bool $enabled = null,
        ?string $baseAccount = null
    ): ApiResponse {
        $uri = [AccountEndPoint::SUB_ACCOUNTS];

        $params = [
            'name'                => $name,
            'cloud_name'          => $cloudName,
            'custom_attributes'   => $customAttributes,
            'enabled'             => $enabled,
            'base_sub_account_id' => $baseAccount,
        ];

        return $this->accountApiClient->postJson($uri, $params);
    }

    /**
     * Deletes a sub account.
     *
     * @param string $subAccountId The id of the sub account.
     *
     * @return ApiResponse The message.
     *
     * @api
     */
    public function deleteSubAccount(string $subAccountId): ApiResponse
    {
        $uri = [AccountEndPoint::SUB_ACCOUNTS, $subAccountId];

        return $this->accountApiClient->delete($uri);
    }

    /**
     * Gets information of a sub account.
     *
     * @param string $subAccountId The id of the sub account.
     *
     * @return ApiResponse A sub account.
     *
     * @api
     */
    public function subAccount(string $subAccountId): ApiResponse
    {
        $uri = [AccountEndPoint::SUB_ACCOUNTS, $subAccountId];

        return $this->accountApiClient->get($uri);
    }

    /**
     * Updates a sub account.
     *
     * @param string      $subAccountId     The id of the sub account.
     * @param string|null $name             The name displayed in the management console.
     * @param string|null $cloudName        The cloud name to set.
     * @param array|null  $customAttributes Custom attributes associated with the sub-account, as a map of key/value
     *                                      pairs.
     * @param bool|null   $enabled          Set the sub-account as enabled or not.
     *
     *
     * @api
     */
    public function updateSubAccount(
        string $subAccountId,
        ?string $name = null,
        ?string $cloudName = null,
        ?array $customAttributes = null,
        ?bool $enabled = null
    ): ApiResponse {
        $uri = [AccountEndPoint::SUB_ACCOUNTS, $subAccountId];

        $params = [
            'name'              => $name,
            'cloud_name'        => $cloudName,
            'custom_attributes' => $customAttributes,
            'enabled'           => $enabled,
        ];

        return $this->accountApiClient->putJson($uri, $params);
    }

    /**
     * Creates a new user group.
     *
     * @param string $name Name for the group.
     *
     * @return ApiResponse The newly created group.
     *
     * @api
     */
    public function createUserGroup(string $name): ApiResponse
    {
        $uri = [AccountEndPoint::USER_GROUPS];

        return $this->accountApiClient->postJson($uri, ['name' => $name]);
    }

    /**
     * Updates an existing user group.
     *
     * @param string $groupId The id of the group to update.
     * @param string $name    The name of the group.
     *
     * @return ApiResponse The updated group.
     *
     * @api
     */
    public function updateUserGroup(string $groupId, string $name): ApiResponse
    {
        $uri = [AccountEndPoint::USER_GROUPS, $groupId];

        return $this->accountApiClient->putJson($uri, ['name' => $name]);
    }

    /**
     * Deletes a user group.
     *
     * @param string $groupId The group id to delete.
     *
     * @return ApiResponse A result message.
     *
     * @api
     */
    public function deleteUserGroup(string $groupId): ApiResponse
    {
        $uri = [AccountEndPoint::USER_GROUPS, $groupId];

        return $this->accountApiClient->delete($uri);
    }

    /**
     * Gets details of a group.
     *
     * @param string $groupId The group id to fetch.
     *
     * @return ApiResponse Details of the group.
     *
     * @api
     */
    public function userGroup(string $groupId): ApiResponse
    {
        $uri = [AccountEndPoint::USER_GROUPS, $groupId];

        return $this->accountApiClient->get($uri);
    }

    /**
     * Gets a list of all the user groups.
     *
     *
     * @return ApiResponse The list of the groups.
     *
     * @api
     */
    public function userGroups(): ApiResponse
    {
        $uri = [AccountEndPoint::USER_GROUPS];

        return $this->accountApiClient->get($uri);
    }

    /**
     * Adds an existing user to a group.
     *
     * @param string $groupId The group id.
     * @param string $userId  The user id to add.
     *
     * @return ApiResponse A list of users in the group.
     *
     * @api
     */
    public function addUserToGroup(string $groupId, string $userId): ApiResponse
    {
        $uri = [AccountEndPoint::USER_GROUPS, $groupId, AccountEndPoint::USERS, $userId];

        return $this->accountApiClient->post($uri);
    }

    /**
     * Removes a user from a group.
     *
     * @param string $groupId The group id.
     * @param string $userId  The id of the user to remove.
     *
     * @return ApiResponse A list of users in the group.
     *
     * @api
     */
    public function removeUserFromGroup(string $groupId, string $userId): ApiResponse
    {
        $uri = [AccountEndPoint::USER_GROUPS, $groupId, AccountEndPoint::USERS, $userId];

        return $this->accountApiClient->delete($uri);
    }

    /**
     * Gets a user list belonging to a user group.
     *
     * @param string $groupId The id of the user group.
     *
     * @return ApiResponse A list of users in that group.
     *
     * @api
     */
    public function userGroupUsers(string $groupId): ApiResponse
    {
        $uri = [AccountEndPoint::USER_GROUPS, $groupId, AccountEndPoint::USERS];

        return $this->accountApiClient->get($uri);
    }

    /**
     * Gets sub account access keys.
     *
     * @param string $subAccountId The id of the sub account.
     * @param array  $options      Additional options.
     *
     * @return ApiResponse A list of access keys.
     *
     * @api
     */
    public function accessKeys(string $subAccountId, array $options = []): ApiResponse
    {
        $uri = [AccountEndPoint::SUB_ACCOUNTS, $subAccountId, AccountEndPoint::ACCESS_KEYS];

        $params = ArrayUtils::whitelist($options, ['page_size', 'page', 'sort_by', 'sort_order']);

        return $this->accountApiClient->get($uri, $params);
    }

    /**
     * Generates a new access key.
     *
     * @param string $subAccountId The id of the sub account.
     * @param array  $options      Additional options.
     *
     * @return ApiResponse Generated access key.
     *
     * @api
     */
    public function generateAccessKey(string $subAccountId, array $options = []): ApiResponse
    {
        $uri = [AccountEndPoint::SUB_ACCOUNTS, $subAccountId, AccountEndPoint::ACCESS_KEYS];

        $params = ArrayUtils::whitelist($options, ['name', 'enabled']);

        return $this->accountApiClient->postJson($uri, $params);
    }

    /**
     * Updates the access key.
     *
     * @param string $subAccountId The id of the sub account.
     * @param string $apiKey       The Api Key.
     * @param array  $options      Additional options.
     *
     * @return ApiResponse Updated access key.
     *
     * @api
     */
    public function updateAccessKey(string $subAccountId, string $apiKey, array $options = []): ApiResponse
    {
        $uri = [AccountEndPoint::SUB_ACCOUNTS, $subAccountId, AccountEndPoint::ACCESS_KEYS, $apiKey];

        $params = ArrayUtils::whitelist($options, ['name', 'enabled']);

        return $this->accountApiClient->putJson($uri, $params);
    }
}
