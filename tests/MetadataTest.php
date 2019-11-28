<?php

namespace Cloudinary;

use Exception;
use Cloudinary;
use Cloudinary\Test\CloudinaryTestCase;
use Cloudinary\Api\NotFound;
use Cloudinary\Api\BadRequest;

/**
 * Class MetadataTest
 * @package Cloudinary
 */
class MetadataTest extends CloudinaryTestCase
{
    private static $UNIQUE_EXTERNAL_ID_GENERAL;
    private static $UNIQUE_EXTERNAL_ID_STRING;
    private static $UNIQUE_EXTERNAL_ID_INT;
    private static $UNIQUE_EXTERNAL_ID_DATE;
    private static $UNIQUE_EXTERNAL_ID_ENUM;
    private static $UNIQUE_EXTERNAL_ID_ENUM_2;
    private static $UNIQUE_EXTERNAL_ID_SET;
    private static $UNIQUE_EXTERNAL_ID_SET_2;
    private static $DATASOURCE_SINGLE = [
        [
            'value' => 'v1',
            'external_id' => 'externalId1',
        ]
    ];
    private static $DATASOURCE_MULTIPLE = [
        [
            'value' => 'v2',
            'external_id' => 'externalId1',
        ],
        [
            'value' => 'v3'
        ],
        [
            'value' => 'v4'
        ],
    ];

    /**
     * @var  \Cloudinary\Api $api
     */
    private $api;

    public static function setUpBeforeClass()
    {
        if (!Cloudinary::config_get("api_secret")) {
            self::markTestSkipped('Please setup environment for Api test to run');
        }
        self::$UNIQUE_EXTERNAL_ID_GENERAL = 'general-' . UNIQUE_TEST_TAG;
        self::$UNIQUE_EXTERNAL_ID_STRING = 'string-' . UNIQUE_TEST_TAG;
        self::$UNIQUE_EXTERNAL_ID_INT = 'int-' . UNIQUE_TEST_TAG;
        self::$UNIQUE_EXTERNAL_ID_DATE = 'date-' . UNIQUE_TEST_TAG;
        self::$UNIQUE_EXTERNAL_ID_ENUM = 'enum-' . UNIQUE_TEST_TAG;
        self::$UNIQUE_EXTERNAL_ID_ENUM_2 = 'enum-2-' . UNIQUE_TEST_TAG;
        self::$UNIQUE_EXTERNAL_ID_SET = 'set-' . UNIQUE_TEST_TAG;
        self::$UNIQUE_EXTERNAL_ID_SET_2 = 'set-2' . UNIQUE_TEST_TAG;

        try {
            (new Api())->add_metadata_field([
                'external_id' => self::$UNIQUE_EXTERNAL_ID_GENERAL,
                'label' => self::$UNIQUE_EXTERNAL_ID_GENERAL,
                'type' => 'string'
            ]);
        } catch (Exception $e) {
            self::fail(
                'Exception thrown while adding metadata field in MetadataFieldsTest::setUpBeforeClass() - ' .
                $e->getMessage()
            );
        }
    }

    protected function setUp()
    {
        $this->api = new Api();
    }

    /**
     * @throws \Cloudinary\Api\GeneralError
     */
    public static function tearDownAfterClass()
    {
        $api = new Api();

        try {
            $api->delete_metadata_field(self::$UNIQUE_EXTERNAL_ID_GENERAL);
            $api->delete_metadata_field(self::$UNIQUE_EXTERNAL_ID_STRING);
            $api->delete_metadata_field(self::$UNIQUE_EXTERNAL_ID_INT);
            $api->delete_metadata_field(self::$UNIQUE_EXTERNAL_ID_DATE);
            $api->delete_metadata_field(self::$UNIQUE_EXTERNAL_ID_ENUM);
            $api->delete_metadata_field(self::$UNIQUE_EXTERNAL_ID_ENUM_2);
            $api->delete_metadata_field(self::$UNIQUE_EXTERNAL_ID_SET);
            $api->delete_metadata_field(self::$UNIQUE_EXTERNAL_ID_SET_2);
        } catch (Exception $e) {
//            self::fail(
//                'Exception thrown while deleting metadata fields in MetadataFieldsTest::tearDownAfterClass() - ' .
//                $e->getMessage()
//            );
        }

        $api->delete_resources_by_tag(UNIQUE_TEST_TAG);
    }

    /**
     * @param $metadataField
     * @param string $type
     */
    private function assertMetadataField($metadataField, $type = null)
    {
        if ($type) {
            $this->assertEquals($type, $metadataField['type']);
        }
        $this->assertNotEmpty($metadataField['type']);
        $this->assertNotEmpty($metadataField['external_id']);
        $this->assertNotEmpty($metadataField['label']);
        $this->assertArrayHasKey('mandatory', $metadataField);
        $this->assertArrayHasKey('default_value', $metadataField);
        $this->assertArrayHasKey('validation', $metadataField);
        if (in_array($metadataField['type'], ['enum', 'set'])) {
            $this->assertMetadataFieldDataSource($metadataField['datasource']);
        }
    }

    /**
     * @param $dataSource
     */
    private function assertMetadataFieldDataSource($dataSource)
    {
        $this->assertNotEmpty($dataSource);
        $this->assertArrayHasKey('values', $dataSource);
        if (!empty($values)) {
            $this->assertArrayHasKey('value', $dataSource['values'][0]);
            $this->assertArrayHasKey('external_id', $dataSource['values'][0]);
        }
    }

    /**
     * Get metadata fields
     *
     * @throws \Cloudinary\Api\GeneralError
     */
    public function testListMetadataFields()
    {
        $result = $this->api->list_metadata_fields();

        $this->assertNotEmpty($result['metadata_fields']);
        $this->assertMetadataField($result['metadata_fields'][0]);
    }

    /**
     * Get a metadata field by external id
     *
     * @throws \Cloudinary\Api\GeneralError
     */
    public function testGetMetadataField()
    {
        $result = $this->api->metadata_field_by_field_id(self::$UNIQUE_EXTERNAL_ID_GENERAL);

        $this->assertMetadataField($result, 'string');
    }

    /**
     * Create a string metadata field
     *
     * @throws \Cloudinary\Api\GeneralError
     */
    public function testCreateStringMetadataField()
    {
        $result = $this->api->add_metadata_field([
            'external_id' => self::$UNIQUE_EXTERNAL_ID_STRING,
            'label' => self::$UNIQUE_EXTERNAL_ID_STRING,
            'type' => 'string'
        ]);

        $this->assertMetadataField($result, 'string');
        $this->assertEquals(self::$UNIQUE_EXTERNAL_ID_STRING, $result['label']);
        $this->assertEquals(self::$UNIQUE_EXTERNAL_ID_STRING, $result['external_id']);
        $this->assertFalse($result['mandatory']);
    }

    /**
     * Create an int metadata field
     *
     * @throws \Cloudinary\Api\GeneralError
     */
    public function testCreateIntMetadataField()
    {
        $result = $this->api->add_metadata_field([
            'external_id' => self::$UNIQUE_EXTERNAL_ID_INT,
            'label' => self::$UNIQUE_EXTERNAL_ID_INT,
            'type' => 'integer'
        ]);

        $this->assertMetadataField($result,'integer');
        $this->assertEquals(self::$UNIQUE_EXTERNAL_ID_INT, $result['label']);
        $this->assertEquals(self::$UNIQUE_EXTERNAL_ID_INT, $result['external_id']);
        $this->assertFalse($result['mandatory']);
    }

    /**
     * Create a date metadata field
     *
     * @throws \Cloudinary\Api\GeneralError
     */
    public function testCreateDateMetadataField()
    {
        $result = $this->api->add_metadata_field([
            'external_id' => self::$UNIQUE_EXTERNAL_ID_DATE,
            'label' => self::$UNIQUE_EXTERNAL_ID_DATE,
            'type' => 'date'
        ]);

        $this->assertMetadataField($result, 'date');
        $this->assertEquals(self::$UNIQUE_EXTERNAL_ID_DATE, $result['label']);
        $this->assertEquals(self::$UNIQUE_EXTERNAL_ID_DATE, $result['external_id']);
        $this->assertFalse($result['mandatory']);
    }

    /**
     * Create an Enum metadata field
     *
     * @throws \Cloudinary\Api\GeneralError
     */
    public function testCreateEnumMetadataField()
    {
        $result = $this->api->add_metadata_field([
            'datasource' => [
                'values' => self::$DATASOURCE_SINGLE
            ],
            'external_id' => self::$UNIQUE_EXTERNAL_ID_ENUM,
            'label' => self::$UNIQUE_EXTERNAL_ID_ENUM,
            'type' => 'enum'
        ]);

        $this->assertMetadataField($result, 'enum');
        $this->assertEquals(self::$UNIQUE_EXTERNAL_ID_ENUM, $result['label']);
        $this->assertEquals(self::$UNIQUE_EXTERNAL_ID_ENUM, $result['external_id']);
        $this->assertFalse($result['mandatory']);
    }

    /**
     * Create a set metadata field
     *
     * @throws \Cloudinary\Api\GeneralError
     */
    public function testCreateSetMetadataField()
    {
        $result = $this->api->add_metadata_field([
            'datasource' => [
                'values' => self::$DATASOURCE_MULTIPLE
            ],
            'external_id' => self::$UNIQUE_EXTERNAL_ID_SET,
            'label' => self::$UNIQUE_EXTERNAL_ID_SET,
            'type' => 'set'
        ]);

        $this->assertMetadataField($result, 'set');
        $this->assertEquals(self::$UNIQUE_EXTERNAL_ID_SET, $result['label']);
        $this->assertEquals(self::$UNIQUE_EXTERNAL_ID_SET, $result['external_id']);
        $this->assertFalse($result['mandatory']);
    }

    /**
     * Update a metadata field by external id
     *
     * @throws \Cloudinary\Api\GeneralError
     */
    public function testUpdateMetadataField()
    {
        $newLabel = 'updating-' . self::$UNIQUE_EXTERNAL_ID_GENERAL;
        $newDefaultValue = 'updating-' . self::$UNIQUE_EXTERNAL_ID_GENERAL;

        $result = $this->api->update_metadata_field(
            self::$UNIQUE_EXTERNAL_ID_GENERAL,
            [
                'external_id' => self::$UNIQUE_EXTERNAL_ID_SET,
                'label' => $newLabel,
                'type' => 'string',
                'mandatory' => true,
                'default_value' => $newDefaultValue
            ]
        );

        $this->assertMetadataField($result, 'string');
        $this->assertEquals(self::$UNIQUE_EXTERNAL_ID_GENERAL, $result['external_id']);
        $this->assertEquals($newLabel, $result['label']);
        $this->assertEquals($newDefaultValue, $result['default_value']);
        $this->assertTrue($result['mandatory']);
    }

    /**
     * Update a metadata field datasource
     *
     * @throws \Cloudinary\Api\GeneralError
     */
    public function testUpdateMetadataFieldDataSource()
    {
        $result = $this->api->add_metadata_field([
            'datasource' => [
                'values' => self::$DATASOURCE_MULTIPLE
            ],
            'external_id' => self::$UNIQUE_EXTERNAL_ID_ENUM_2,
            'label' => self::$UNIQUE_EXTERNAL_ID_ENUM_2,
            'type' => 'enum'
        ]);

        $this->assertMetadataField($result, 'enum');

        $result = $this->api->update_metadata_field_datasource(
            self::$UNIQUE_EXTERNAL_ID_ENUM_2,
            self::$DATASOURCE_SINGLE
        );

        $this->assertMetadataFieldDataSource($result);
        $this->assertArrayContainsArray($result['values'], self::$DATASOURCE_SINGLE[0]);
        $this->assertCount(count(self::$DATASOURCE_MULTIPLE), $result['values']);
        $this->assertEquals(self::$DATASOURCE_SINGLE[0]['value'], $result['values'][0]['value']);
    }

    /**
     * Delete a metadata field by external id
     *
     * @throws \Cloudinary\Api\GeneralError
     */
    public function testDeleteMetadataField()
    {
        $tempMetadataFieldId = 'deletion-' . self::$UNIQUE_EXTERNAL_ID_INT;

        $result = $this->api->add_metadata_field([
            'external_id' => $tempMetadataFieldId,
            'label' => $tempMetadataFieldId,
            'type' => 'integer'
        ]);

        $this->assertMetadataField($result, 'integer');

        $this->api->delete_metadata_field($tempMetadataFieldId);

        $hasException = false;
        try {
            $this->api->metadata_field_by_field_id($tempMetadataFieldId);
        } catch (NotFound $e) {
            $hasException = true;
        }

        $this->assertTrue($hasException, "The metadata field {$tempMetadataFieldId} was not deleted");
    }

    /**
     * Delete entries in a metadata field datasource
     *
     * @throws \Cloudinary\Api\GeneralError
     */
    public function testDeleteMetadataFieldDataSource()
    {
        $result = $this->api->add_metadata_field([
            'datasource' => [
                'values' => self::$DATASOURCE_MULTIPLE
            ],
            'external_id' => self::$UNIQUE_EXTERNAL_ID_SET_2,
            'label' => self::$UNIQUE_EXTERNAL_ID_SET_2,
            'type' => 'set'
        ]);

        $this->assertMetadataField($result, 'set');

        $result = $this->api->delete_datasource_entries(
            self::$UNIQUE_EXTERNAL_ID_SET_2,
            [
                self::$DATASOURCE_MULTIPLE[0]['external_id']
            ]
        );

        $this->assertMetadataFieldDataSource($result);
        $this->assertCount(count(self::$DATASOURCE_MULTIPLE) - 1, $result['values']);

        $values = array_column($result['values'], 'value');

        $this->assertContains(self::$DATASOURCE_MULTIPLE[1]['value'], $values);
        $this->assertContains(self::$DATASOURCE_MULTIPLE[2]['value'], $values);
    }

    /**
     * Test date field validation
     *
     * @throws \Cloudinary\Api\GeneralError
     */
    public function testDateFieldDefaultValueValidation()
    {
        $validation = [
            'rules' => [
                [
                    'type' => 'greater_than',
                    'equals' => false,
                    'value' => date('Y-m-d', time() - 60*60*24*3)
                ],
                [
                    'type' => 'less_than',
                    'equals' => false,
                    'value' => date('Y-m-d')
                ],
            ],
            'type' => 'and'
        ];
        $metadata_field = [
            'label' => 'date-validation-' . self::$UNIQUE_EXTERNAL_ID_DATE,
            'type' => 'date',
            'default_value' => date('Y-m-d', time() - 60*60*24),
            'validation' => $validation
        ];

        $result = $this->api->add_metadata_field($metadata_field);

        $this->assertMetadataField($result, 'date');
        $this->assertEquals($result['validation'], $validation);
        $this->assertEquals($result['default_value'], $metadata_field['default_value']);

        $this->api->delete_metadata_field($result['external_id']);

        $hasException = false;
        try {
            $metadata_field['default_value'] = date('Y-m-d', time() + 60*60*24*3);
            $this->api->add_metadata_field($metadata_field);
        } catch (BadRequest $e) {
            $hasException = true;
        }

        $this->assertTrue($hasException, "The metadata field with illegal value was added");
    }

    /**
     * Test integer field single validation
     *
     * @throws \Cloudinary\Api\GeneralError
     */
    public function testIntegerFieldSingleValidation()
    {
        $validation = ['type' => 'less_than', 'equals' => true, 'value' => 5];
        $metadata_field = [
            'label' => 'validation-' . self::$UNIQUE_EXTERNAL_ID_INT,
            'type' => 'integer',
            'default_value' => 5,
            'validation' => $validation
        ];

        $result = $this->api->add_metadata_field($metadata_field);

        $this->assertMetadataField($result, 'integer');
        $this->assertEquals($result['validation'], $validation);
        $this->assertEquals($result['default_value'], $metadata_field['default_value']);

        $this->api->delete_metadata_field($result['external_id']);

        $hasException = false;
        try {
            $metadata_field['default_value'] = 6;
            $this->api->add_metadata_field($metadata_field);
        } catch (BadRequest $e) {
            $hasException = true;
        }

        $this->assertTrue($hasException, "The metadata field with illegal value was added");
    }
}
