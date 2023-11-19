<?php

namespace Cloudinary\Test\Unit\Provisioning;

use Cloudinary\Test\Helpers\MockAccountApi;
use Cloudinary\Test\Helpers\RequestAssertionsTrait;

/**
 * Class AccessKeysTest
 */
class AccessKeysTest extends ProvisioningUnitTestCase
{
    use RequestAssertionsTrait;

    const SUB_ACCOUNT_ID = 'sub_account123';
    const API_KEY        = 'key';

    /**
     * Should allow listing access keys.
     */
    public function testAccessKeys()
    {
        $mockAccApi = new MockAccountApi();

        $mockAccApi->accessKeys(
            self::SUB_ACCOUNT_ID,
            ['page_size' => 2, 'page' => 1, 'sort_by' => 'name', 'sort_order' => 'asc']
        );

        $lastRequest = $mockAccApi->getMockHandler()->getLastRequest();

        self::assertAccountRequestUrl($lastRequest, '/sub_accounts/' . self::SUB_ACCOUNT_ID . '/access_keys');

        self::assertRequestGet($lastRequest);

        self::assertRequestQueryStringSubset($lastRequest, ['page_size' => '2']);
        self::assertRequestQueryStringSubset($lastRequest, ['page' => '1']);
        self::assertRequestQueryStringSubset($lastRequest, ['sort_by' => 'name']);
        self::assertRequestQueryStringSubset($lastRequest, ['sort_order' => 'asc']);
    }

    /**
     * Should allow generating access keys.
     */
    public function testGenerateAccessKey()
    {
        $mockAccApi = new MockAccountApi();

        $mockAccApi->generateAccessKey(
            self::SUB_ACCOUNT_ID,
            ['enabled' => true, 'name' => 'test_key']
        );

        $lastRequest = $mockAccApi->getMockHandler()->getLastRequest();

        self::assertAccountRequestUrl($lastRequest, '/sub_accounts/' . self::SUB_ACCOUNT_ID . '/access_keys');
        self::assertRequestPost($lastRequest);

        self::assertRequestJsonBodySubset($lastRequest, ['enabled' => true, 'name' => 'test_key']);
    }

    /**
     * Should allow updating access keys.
     */
    public function testUpdateAccessKey()
    {
        $mockAccApi = new MockAccountApi();

        $mockAccApi->updateAccessKey(
            self::SUB_ACCOUNT_ID,
            self::API_KEY,
            ['enabled' => false, 'name' => 'updated_key']
        );

        $lastRequest = $mockAccApi->getMockHandler()->getLastRequest();

        self::assertAccountRequestUrl(
            $lastRequest,
            '/sub_accounts/' . self::SUB_ACCOUNT_ID . '/access_keys/' . self::API_KEY
        );
        self::assertRequestPut($lastRequest);

        self::assertRequestJsonBodySubset($lastRequest, ['enabled' => false, 'name' => 'updated_key']);
    }
}
