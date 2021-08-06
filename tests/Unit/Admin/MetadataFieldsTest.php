<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Test\Unit\Admin;

use Cloudinary\Api\Exception\ApiError;
use Cloudinary\Api\Metadata\EnumMetadataField;
use Cloudinary\Api\Metadata\IntMetadataField;
use Cloudinary\Api\Metadata\MetadataFieldType;
use Cloudinary\Api\Metadata\StringMetadataField;
use Cloudinary\Test\Helpers\MockAdminApi;
use Cloudinary\Test\Helpers\RequestAssertionsTrait;
use Cloudinary\Test\Unit\UnitTestCase;

/**
 * Class MetadataFieldsTest
 */
class MetadataFieldsTest extends UnitTestCase
{
    use RequestAssertionsTrait;

    const EXTERNAL_ID_STRING = 'metadata_external_id_string';
    const EXTERNAL_ID_INT    = 'metadata_external_id_int';
    const EXTERNAL_ID_ENUM   = 'metadata_external_id_enum';
    const EXTERNAL_ID_DELETE = 'metadata_deletion_test';
    const DATASOURCE_SINGLE  = [
        [
            'value'       => 'v1',
            'external_id' => 'metadata_datasource_entry_external_id'
        ]
    ];

    /**
     * Test getting a list of all metadata fields.
     *
     * @see https://cloudinary.com/documentation/admin_api#get_metadata_fields
     */
    public function testListMetadataFields()
    {
        $mockAdminApi = new MockAdminApi();
        $mockAdminApi->listMetadataFields();

        $lastRequest = $mockAdminApi->getMockHandler()->getLastRequest();

        self::assertRequestUrl($lastRequest, '/metadata_fields');
        self::assertRequestGet($lastRequest);
        self::assertRequestFields($lastRequest);
    }

    /**
     * Test creating a string metadata field.
     *
     * @see https://cloudinary.com/documentation/admin_api#create_a_metadata_field
     */
    public function testCreateStringMetadataField()
    {
        $mockAdminApi = new MockAdminApi();

        $stringMetadataField = new StringMetadataField(self::EXTERNAL_ID_STRING);
        $stringMetadataField->setExternalId(self::EXTERNAL_ID_STRING);

        $mockAdminApi->addMetadataField($stringMetadataField);
        $lastRequest = $mockAdminApi->getMockHandler()->getLastRequest();

        self::assertRequestUrl($lastRequest, '/metadata_fields');
        self::assertRequestPost($lastRequest);
        self::assertRequestFields(
            $lastRequest,
            [
                'type'        => MetadataFieldType::STRING,
                'external_id' => self::EXTERNAL_ID_STRING,
                'label'       => self::EXTERNAL_ID_STRING
            ]
        );
    }

    /**
     * Test creating an integer metadata field.
     *
     * @see https://cloudinary.com/documentation/admin_api#create_a_metadata_field
     */
    public function testCreateIntMetadataField()
    {
        $mockAdminApi = new MockAdminApi();

        $intMetadataField = new IntMetadataField(self::EXTERNAL_ID_INT);
        $intMetadataField->setExternalId(self::EXTERNAL_ID_INT);

        $mockAdminApi->addMetadataField($intMetadataField);
        $lastRequest = $mockAdminApi->getMockHandler()->getLastRequest();

        self::assertRequestUrl($lastRequest, '/metadata_fields');
        self::assertRequestPost($lastRequest);
        self::assertRequestFields(
            $lastRequest,
            [
                'type'        => MetadataFieldType::INTEGER,
                'external_id' => self::EXTERNAL_ID_INT,
                'label'       => self::EXTERNAL_ID_INT
            ]
        );
    }

    /**
     * Test creating an enum metadata field.
     *
     * @see https://cloudinary.com/documentation/admin_api#create_a_metadata_field
     */
    public function testCreateEnumMetadataField()
    {
        $mockAdminApi = new MockAdminApi();

        $enumMetadataField = new EnumMetadataField(self::EXTERNAL_ID_ENUM, self::DATASOURCE_SINGLE);
        $enumMetadataField->setDataSource(self::DATASOURCE_SINGLE);
        $enumMetadataField->setExternalId(self::EXTERNAL_ID_ENUM);

        $mockAdminApi->addMetadataField($enumMetadataField);
        $lastRequest = $mockAdminApi->getMockHandler()->getLastRequest();

        self::assertRequestUrl($lastRequest, '/metadata_fields');
        self::assertRequestPost($lastRequest);
        self::assertRequestFields(
            $lastRequest,
            [
                'datasource' => [
                    'values' => self::DATASOURCE_SINGLE
                ],
                'external_id' => self::EXTERNAL_ID_ENUM,
                'label' => self::EXTERNAL_ID_ENUM,
                'type' => MetadataFieldType::ENUM
            ]
        );
    }

    /**
     * Test deleting a metadata field definition by its external id.
     *
     * @see https://cloudinary.com/documentation/admin_api#delete_a_metadata_field_by_external_id
     *
     * @throws ApiError
     */
    public function testDeleteMetadataField()
    {
        $mockAdminApi = new MockAdminApi();

        $mockAdminApi->deleteMetadataField(self::EXTERNAL_ID_DELETE);
        $lastRequest = $mockAdminApi->getMockHandler()->getLastRequest();

        self::assertRequestUrl(
            $lastRequest,
            '/metadata_fields/' . self::EXTERNAL_ID_DELETE
        );
        self::assertRequestDelete($lastRequest);
        self::assertRequestFields($lastRequest);
    }
}
