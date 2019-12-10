<?php

namespace Cloudinary;

use Exception;
use Cloudinary;
use PHPUnit\Framework\TestCase;

/**
 * Class MetadataTest
 * @package Cloudinary
 */
class MetadataTest extends TestCase
{
    private static $metadata_fields = [];
    private static $unique_external_id_general;
    private static $unique_external_id_string;
    private static $unique_external_id_int;
    private static $unique_external_id_date;
    private static $unique_external_id_enum;
    private static $unique_external_id_enum_2;
    private static $unique_external_id_set;
    private static $unique_external_id_set_2;
    private static $unique_external_id_set_3;
    private static $unique_external_id_for_deletion;
    private static $unique_external_id_for_deletion_2;
    private static $unique_external_id_for_testing_date_validation;
    private static $unique_external_id_for_testing_date_validation_2;
    private static $unique_external_id_for_testing_integer_validation;
    private static $unique_external_id_for_testing_integer_validation_2;
    private static $unique_datasource_entry_external_id;
    private static $datasource_single;
    private static $datasource_multiple;

    /**
     * @var  \Cloudinary\Api $api
     */
    private $api;

    public static function setUpBeforeClass()
    {
        if (!Cloudinary::config_get("api_secret")) {
            self::markTestSkipped('Please setup environment for Api test to run');
        }
        self::$metadata_fields[] = self::$unique_external_id_general = 'metadata_external_id_general_' . UNIQUE_TEST_ID;
        self::$metadata_fields[] = self::$unique_external_id_string = 'metadata_external_id_string_' . UNIQUE_TEST_ID;
        self::$metadata_fields[] = self::$unique_external_id_int = 'metadata_external_id_int_' . UNIQUE_TEST_ID;
        self::$metadata_fields[] = self::$unique_external_id_date = 'metadata_external_id_date_' . UNIQUE_TEST_ID;
        self::$metadata_fields[] = self::$unique_external_id_enum = 'metadata_external_id_enum_' . UNIQUE_TEST_ID;
        self::$metadata_fields[] = self::$unique_external_id_enum_2 = 'metadata_external_id_enum_2_' . UNIQUE_TEST_ID;
        self::$metadata_fields[] = self::$unique_external_id_set = 'metadata_external_id_set_' . UNIQUE_TEST_ID;
        self::$metadata_fields[] = self::$unique_external_id_set_2 = 'metadata_external_id_set_2_' . UNIQUE_TEST_ID;
        self::$metadata_fields[] = self::$unique_external_id_set_3 = 'metadata_external_id_set_3_' . UNIQUE_TEST_ID;
        self::$metadata_fields[] = self::$unique_external_id_for_deletion = 'metadata_deletion_test_' . UNIQUE_TEST_ID;
        self::$metadata_fields[] = self::$unique_external_id_for_deletion_2 = 'metadata_deletion_test_2_' . UNIQUE_TEST_ID;
        self::$metadata_fields[] = self::$unique_external_id_for_testing_date_validation = 'metadata_date_validation_test_' . UNIQUE_TEST_ID;
        self::$metadata_fields[] = self::$unique_external_id_for_testing_date_validation_2 = 'metadata_date_validation_test_2_' . UNIQUE_TEST_ID;
        self::$metadata_fields[] = self::$unique_external_id_for_testing_integer_validation = 'metadata_integer_validation_test_' . UNIQUE_TEST_ID;
        self::$metadata_fields[] = self::$unique_external_id_for_testing_integer_validation_2 = 'metadata_integer_validation_test_2_' . UNIQUE_TEST_ID;
        self::$unique_datasource_entry_external_id = 'metadata_datasource_entry_external_id' . UNIQUE_TEST_ID;
        self::$datasource_single = [
            [
                'value' => 'v1',
                'external_id' => self::$unique_datasource_entry_external_id,
            ]
        ];
        self::$datasource_multiple = [
            [
                'value' => 'v2',
                'external_id' => self::$unique_datasource_entry_external_id,
            ],
            [
                'value' => 'v3'
            ],
            [
                'value' => 'v4'
            ],
        ];

        try {
            (new Api())->add_metadata_field([
                'external_id' => self::$unique_external_id_general,
                'label' => self::$unique_external_id_general,
                'type' => 'string'
            ]);
            (new Api())->add_metadata_field([
                'datasource' => [
                    'values' => self::$datasource_multiple
                ],
                'external_id' => self::$unique_external_id_enum_2,
                'label' => self::$unique_external_id_enum_2,
                'type' => 'enum'
            ]);
            (new Api())->add_metadata_field([
                'datasource' => [
                    'values' => self::$datasource_multiple
                ],
                'external_id' => self::$unique_external_id_set_2,
                'label' => self::$unique_external_id_set_2,
                'type' => 'set'
            ]);
            (new Api())->add_metadata_field([
                'datasource' => [
                    'values' => self::$datasource_multiple
                ],
                'external_id' => self::$unique_external_id_set_3,
                'label' => self::$unique_external_id_set_3,
                'type' => 'set'
            ]);
            (new Api())->add_metadata_field([
                'external_id' => self::$unique_external_id_for_deletion,
                'label' => self::$unique_external_id_for_deletion,
                'type' => 'integer'
            ]);
            (new Api())->add_metadata_field([
                'external_id' => self::$unique_external_id_for_deletion_2,
                'label' => self::$unique_external_id_for_deletion_2,
                'type' => 'integer'
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

    public static function tearDownAfterClass()
    {
        $api = new Api();

        foreach (self::$metadata_fields as $externalId) {
            try {
                $api->delete_metadata_field($externalId);
            } catch (Exception $e) {
            }
        }
    }

    /**
     * Asserts that a given object fits the generic structure of a metadata field
     *
     * @see https://cloudinary.com/documentation/admin_api#generic_structure_of_a_metadata_field
     *
     * @param \Cloudinary\Api\Response $metadataField The object to test
     * @param string $type          The type of metadata field we expect
     * @param array $values         An associative array where the key is the name of the parameter to check and the
     *                              value is the value
     */
    private function assert_metadata_field($metadataField, $type = null, $values = array())
    {
        $this->assertInternalType('string', $metadataField['external_id']);
        if ($type) {
            $this->assertEquals($type, $metadataField['type']);
        } else {
            $this->assertContains($metadataField['type'], ['string', 'integer', 'date', 'enum', 'set']);
        }
        $this->assertInternalType('string', $metadataField['label']);
        $this->assertInternalType('boolean', $metadataField['mandatory']);
        $this->assertArrayHasKey('default_value', $metadataField);
        $this->assertArrayHasKey('validation', $metadataField);
        if (in_array($metadataField['type'], ['enum', 'set'])) {
            $this->assert_metadata_field_datasource($metadataField['datasource']);
        }

        foreach ($values as $key => $value) {
            $this->assertEquals($value, $metadataField[$key]);
        }
    }

    /**
     * Asserts that a given object fits the generic structure of a metadata field datasource
     *
     * @see https://cloudinary.com/documentation/admin_api#datasource_values
     *
     * @param $dataSource
     */
    private function assert_metadata_field_datasource($dataSource)
    {
        $this->assertNotEmpty($dataSource);
        $this->assertArrayHasKey('values', $dataSource);
        if (!empty($values)) {
            $this->assertInternalType('string', $dataSource['values'][0]['value']);
            $this->assertInternalType('string', $dataSource['values'][0]['external_id']);
            $this->assertContains($dataSource['values'][0]['state'], ['active', 'inactive']);
        }
    }

    /**
     * Test getting a list of all metadata fields
     *
     * @throws \Cloudinary\Api\GeneralError
     */
    public function test_list_metadata_fields()
    {
        $result = $this->api->list_metadata_fields();

        $this->assertGreaterThanOrEqual(1, count($result['metadata_fields']));
        $this->assert_metadata_field($result['metadata_fields'][0]);
    }

    /**
     * Test getting a metadata field by external id
     *
     * @throws \Cloudinary\Api\GeneralError
     */
    public function test_get_metadata_field()
    {
        $result = $this->api->metadata_field_by_field_id(self::$unique_external_id_general);

        $this->assert_metadata_field($result, 'string', ['label' => self::$unique_external_id_general]);
    }

    /**
     * Test creating a string metadata field
     *
     * @throws \Cloudinary\Api\GeneralError
     */
    public function test_create_string_metadata_field()
    {
        $result = $this->api->add_metadata_field([
            'external_id' => self::$unique_external_id_string,
            'label' => self::$unique_external_id_string,
            'type' => 'string'
        ]);

        $this->assert_metadata_field($result, 'string', [
            'label' => self::$unique_external_id_string,
            'external_id' => self::$unique_external_id_string,
            'mandatory' => false
        ]);
    }

    /**
     * Test creating an integer metadata field
     *
     * @throws \Cloudinary\Api\GeneralError
     */
    public function test_create_int_metadata_field()
    {
        $result = $this->api->add_metadata_field([
            'external_id' => self::$unique_external_id_int,
            'label' => self::$unique_external_id_int,
            'type' => 'integer'
        ]);

        $this->assert_metadata_field($result, 'integer', [
            'label' => self::$unique_external_id_int,
            'external_id' => self::$unique_external_id_int,
            'mandatory' => false
        ]);
    }

    /**
     * Test creating a date metadata field
     *
     * @throws \Cloudinary\Api\GeneralError
     */
    public function test_create_date_metadata_field()
    {
        $result = $this->api->add_metadata_field([
            'external_id' => self::$unique_external_id_date,
            'label' => self::$unique_external_id_date,
            'type' => 'date'
        ]);

        $this->assert_metadata_field($result, 'date', [
            'label' => self::$unique_external_id_date,
            'external_id' => self::$unique_external_id_date,
            'mandatory' => false
        ]);
    }

    /**
     * Test creating an Enum metadata field
     *
     * @throws \Cloudinary\Api\GeneralError
     */
    public function test_create_enum_metadata_field()
    {
        $result = $this->api->add_metadata_field([
            'datasource' => [
                'values' => self::$datasource_single
            ],
            'external_id' => self::$unique_external_id_enum,
            'label' => self::$unique_external_id_enum,
            'type' => 'enum'
        ]);

        $this->assert_metadata_field($result, 'enum', [
            'label' => self::$unique_external_id_enum,
            'external_id' => self::$unique_external_id_enum,
            'mandatory' => false
        ]);
    }

    /**
     * Test creating a set metadata field
     *
     * @throws \Cloudinary\Api\GeneralError
     */
    public function test_create_set_metadata_field()
    {
        $result = $this->api->add_metadata_field([
            'datasource' => [
                'values' => self::$datasource_multiple
            ],
            'external_id' => self::$unique_external_id_set,
            'label' => self::$unique_external_id_set,
            'type' => 'set'
        ]);

        $this->assert_metadata_field($result, 'set', [
            'label' => self::$unique_external_id_set,
            'external_id' => self::$unique_external_id_set,
            'mandatory' => false
        ]);
    }

    /**
     * Update a metadata field by external id
     *
     * @throws \Cloudinary\Api\GeneralError
     */
    public function test_update_metadata_field()
    {
        $newLabel = 'update_metadata_test_' . self::$unique_external_id_general;
        $newDefaultValue = 'update_metadata_test_' . self::$unique_external_id_general;

        // Call the API to update the metadata field
        // Will also attempt to update some fields that cannot be updated (external_id and type) which will be ignored
        $result = $this->api->update_metadata_field(
            self::$unique_external_id_general,
            [
                'external_id' => self::$unique_external_id_set,
                'label' => $newLabel,
                'type' => 'integer',
                'mandatory' => true,
                'default_value' => $newDefaultValue
            ]
        );

        $this->assert_metadata_field($result, 'string', [
            'external_id' => self::$unique_external_id_general,
            'label' => $newLabel,
            'default_value' => $newDefaultValue,
            'mandatory' => true,
        ]);
    }

    /**
     * Update a metadata field datasource
     *
     * @throws \Cloudinary\Api\GeneralError
     */
    public function test_update_metadata_field_datasource()
    {
        $result = $this->api->update_metadata_field_datasource(
            self::$unique_external_id_enum_2,
            self::$datasource_single
        );

        $this->assert_metadata_field_datasource($result);
        assertArrayContainsArray($this, $result['values'], self::$datasource_single[0]);
        $this->assertCount(count(self::$datasource_multiple), $result['values']);
        $this->assertEquals(self::$datasource_single[0]['value'], $result['values'][0]['value']);
    }

    /**
     * Test deleting a metadata field definition by its external id.
     *
     * @throws \Cloudinary\Api\GeneralError
     */
    public function test_delete_metadata_field()
    {
        $this->api->delete_metadata_field(self::$unique_external_id_for_deletion);

        $this->setExpectedException('\Cloudinary\Api\NotFound');
        $this->api->metadata_field_by_field_id(self::$unique_external_id_for_deletion);
    }

    /**
     * Test deleting a metadata field definition then attempting to create a new one with the same external id which
     * should fail.
     *
     * @throws \Cloudinary\Api\GeneralError
     */
    public function test_delete_metadata_field_does_not_release_external_id()
    {
        $this->api->delete_metadata_field(self::$unique_external_id_for_deletion_2);

        $this->setExpectedException(
            '\Cloudinary\Api\BadRequest',
            'external id ' . self::$unique_external_id_for_deletion_2 . ' already exists'
        );
        $this->api->add_metadata_field([
            'external_id' => self::$unique_external_id_for_deletion_2,
            'label' => self::$unique_external_id_for_deletion_2,
            'type' => 'integer'
        ]);
    }

    /**
     * Delete entries in a metadata field datasource
     *
     * @throws \Cloudinary\Api\GeneralError
     */
    public function test_delete_metadata_field_data_source()
    {
        $result = $this->api->delete_datasource_entries(
            self::$unique_external_id_set_2,
            [
                self::$unique_datasource_entry_external_id
            ]
        );

        $this->assert_metadata_field_datasource($result);
        $this->assertCount(count(self::$datasource_multiple) - 1, $result['values']);

        $values = array_map(function($datasource_entity){
            return $datasource_entity['value'];
        }, $result['values']);

        $this->assertContains(self::$datasource_multiple[1]['value'], $values);
        $this->assertContains(self::$datasource_multiple[2]['value'], $values);
    }

    /**
     * Test date field validation
     *
     * @throws \Cloudinary\Api\GeneralError
     */
    public function test_date_field_default_value_validation()
    {
        $pastDate = date('Y-m-d', time() - 60 * 60 * 24 * 3);
        $yesterdaydate = date('Y-m-d', time() - 60 * 60 * 24);
        $todayDate = date('Y-m-d');
        $futureDate = date('Y-m-d', time() + 60 * 60 * 24 * 3);
        $lastThreeDaysValidation = [
            'rules' => [
                [
                    'type' => 'greater_than',
                    'equals' => false,
                    'value' => $pastDate
                ],
                [
                    'type' => 'less_than',
                    'equals' => false,
                    'value' => $todayDate
                ],
            ],
            'type' => 'and'
        ];

        // Test entering a metadata field with date validation and a valid default value
        $metadata_field = [
            'external_id' => self::$unique_external_id_for_testing_date_validation,
            'label' => self::$unique_external_id_for_testing_date_validation,
            'type' => 'date',
            'default_value' => $yesterdaydate,
            'validation' => $lastThreeDaysValidation
        ];
        $result = $this->api->add_metadata_field($metadata_field);

        $this->assert_metadata_field($result, 'date', [
            'validation' => $lastThreeDaysValidation,
            'default_value' => $metadata_field['default_value'],
        ]);

        // Test entering a metadata field with date validation and an invalid default value
        $metadata_field = [
            'external_id' => self::$unique_external_id_for_testing_date_validation_2,
            'label' => self::$unique_external_id_for_testing_date_validation_2,
            'type' => 'date',
            'default_value' => $futureDate,
            'validation' => $lastThreeDaysValidation
        ];
        $this->setExpectedException('\Cloudinary\Api\BadRequest');
        $this->api->add_metadata_field($metadata_field);
    }

    /**
     * Test integer field validation
     *
     * @throws \Cloudinary\Api\GeneralError
     */
    public function test_integer_field_validation()
    {
        $validation = [
            'type' => 'less_than',
            'equals' => true,
            'value' => 5
        ];

        // Test entering a metadata field with integer validation and a valid default value
        $metadata_field = [
            'external_id' => self::$unique_external_id_for_testing_integer_validation,
            'label' => self::$unique_external_id_for_testing_integer_validation,
            'type' => 'integer',
            'default_value' => 5,
            'validation' => $validation
        ];
        $result = $this->api->add_metadata_field($metadata_field);

        $this->assert_metadata_field($result, 'integer', [
            'validation' => $validation,
            'default_value' => $metadata_field['default_value'],
        ]);

        // Test entering a metadata field with integer validation and a valid default value
        $metadata_field = [
            'external_id' => self::$unique_external_id_for_testing_integer_validation_2,
            'label' => self::$unique_external_id_for_testing_integer_validation_2,
            'type' => 'integer',
            'default_value' => 6,
            'validation' => $validation
        ];
        $this->setExpectedException('\Cloudinary\Api\BadRequest');
        $this->api->add_metadata_field($metadata_field);
    }

    /**
     * Restore a deleted entry in a metadata field datasource
     *
     * @throws \Cloudinary\Api\GeneralError
     */
    public function test_restore_metadata_field_datasource()
    {
        // Begin by deleting a datasource entry
        $result = $this->api->delete_datasource_entries(
            self::$unique_external_id_set_3,
            [
                self::$unique_datasource_entry_external_id
            ]
        );

        $this->assert_metadata_field_datasource($result);
        $this->assertCount(2, $result['values']);

        // Restore datasource entry
        $result = $this->api->restore_metadata_field_datasource(
            self::$unique_external_id_set_3,
            [
                self::$unique_datasource_entry_external_id
            ]
        );
        $this->assert_metadata_field_datasource($result);
        $this->assertCount(3, $result['values']);
    }
}
