<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Test\Integration\Admin;

use Cloudinary\Api\ApiResponse;
use Cloudinary\Api\Exception\ApiError;
use Cloudinary\Api\Exception\BadRequest;
use Cloudinary\Api\Metadata\DateMetadataField;
use Cloudinary\Api\Metadata\EnumMetadataField;
use Cloudinary\Api\Metadata\IntMetadataField;
use Cloudinary\Api\Metadata\MetadataFieldType;
use Cloudinary\Api\Metadata\SetMetadataField;
use Cloudinary\Api\Metadata\StringMetadataField;
use Cloudinary\Api\Metadata\Validators\AndValidator;
use Cloudinary\Api\Metadata\Validators\DateGreaterThan;
use Cloudinary\Api\Metadata\Validators\DateLessThan;
use Cloudinary\Api\Metadata\Validators\IntLessThan;
use Cloudinary\Test\Helpers\RequestAssertionsTrait;
use Cloudinary\Test\Integration\IntegrationTestCase;
use DateInterval;
use DateTime;
use Exception;
use PHPUnit\Framework\Constraint\IsType;

/**
 * Class MetadataFieldsTest
 */
class MetadataFieldsTest extends IntegrationTestCase
{
    use RequestAssertionsTrait;

    private static $METADATA_FIELDS = [];
    private static $EXTERNAL_ID_GENERAL;
    private static $EXTERNAL_ID_DATE;
    private static $EXTERNAL_ID_ENUM;
    private static $EXTERNAL_ID_SET;
    private static $EXTERNAL_ID_SET_2;
    private static $EXTERNAL_ID_SET_3;
    private static $EXTERNAL_ID_DELETE;
    private static $EXTERNAL_ID_DATE_VALIDATION;
    private static $EXTERNAL_ID_DATE_VALIDATION_2;
    private static $EXTERNAL_ID_INT_VALIDATION;
    private static $EXTERNAL_ID_INT_VALIDATION_2;
    private static $DATASOURCE_ENTRY_EXTERNAL_ID;
    private static $DATASOURCE_SINGLE;
    private static $DATASOURCE_MULTIPLE;

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        $id = self::$UNIQUE_TEST_ID;
        self::$METADATA_FIELDS[] = self::$EXTERNAL_ID_GENERAL = 'metadata_external_id_general_' . $id;
        self::$METADATA_FIELDS[] = self::$EXTERNAL_ID_DATE = 'metadata_external_id_date_' . $id;
        self::$METADATA_FIELDS[] = self::$EXTERNAL_ID_ENUM = 'metadata_external_id_enum_' . $id;
        self::$METADATA_FIELDS[] = self::$EXTERNAL_ID_SET = 'metadata_external_id_set_' . $id;
        self::$METADATA_FIELDS[] = self::$EXTERNAL_ID_SET_2 = 'metadata_external_id_set_2_' . $id;
        self::$METADATA_FIELDS[] = self::$EXTERNAL_ID_SET_3 = 'metadata_external_id_set_3_' . $id;
        self::$METADATA_FIELDS[] = self::$EXTERNAL_ID_DELETE = 'metadata_deletion_test_' . $id;
        self::$METADATA_FIELDS[] = self::$EXTERNAL_ID_DATE_VALIDATION = 'metadata_date_validation_' . $id;
        self::$METADATA_FIELDS[] = self::$EXTERNAL_ID_DATE_VALIDATION_2 = 'metadata_date_validation_2_' . $id;
        self::$METADATA_FIELDS[] = self::$EXTERNAL_ID_INT_VALIDATION = 'metadata_integer_validation_' . $id;
        self::$METADATA_FIELDS[] = self::$EXTERNAL_ID_INT_VALIDATION_2 = 'metadata_integer_validation_2_' . $id;
        // Sample datasource data
        self::$DATASOURCE_ENTRY_EXTERNAL_ID = 'metadata_datasource_entry_external_id' . $id;
        self::$DATASOURCE_SINGLE = [
            [
                'value' => 'v1',
                'external_id' => self::$DATASOURCE_ENTRY_EXTERNAL_ID
            ]
        ];
        self::$DATASOURCE_MULTIPLE = [
            [
                'value' => 'v2',
                'external_id' => self::$DATASOURCE_ENTRY_EXTERNAL_ID
            ],
            [
                'value' => 'v3'
            ],
            [
                'value' => 'v4'
            ],
        ];

        $stringMetadataField = new StringMetadataField(self::$EXTERNAL_ID_GENERAL);
        $stringMetadataField->setExternalId(self::$EXTERNAL_ID_GENERAL);
        self::$adminApi->addMetadataField($stringMetadataField);

        $enumMetadataField = new EnumMetadataField(self::$EXTERNAL_ID_ENUM, self::$DATASOURCE_MULTIPLE);
        $enumMetadataField->setExternalId(self::$EXTERNAL_ID_ENUM);
        self::$adminApi->addMetadataField($enumMetadataField);

        $setMetadataField = new SetMetadataField(self::$EXTERNAL_ID_SET_2, self::$DATASOURCE_MULTIPLE);
        $setMetadataField->setExternalId(self::$EXTERNAL_ID_SET_2);
        self::$adminApi->addMetadataField($setMetadataField);

        $setMetadataField = new SetMetadataField(self::$EXTERNAL_ID_SET_3, self::$DATASOURCE_MULTIPLE);
        $setMetadataField->setExternalId(self::$EXTERNAL_ID_SET_3);
        self::$adminApi->addMetadataField($setMetadataField);

        $setMetadataField = new IntMetadataField(self::$EXTERNAL_ID_DELETE);
        $setMetadataField->setExternalId(self::$EXTERNAL_ID_DELETE);
        self::$adminApi->addMetadataField($setMetadataField);
    }

    public static function tearDownAfterClass()
    {
        self::cleanupAssetsByTag(self::$UNIQUE_TEST_TAG);
        foreach (self::$METADATA_FIELDS as $externalId) {
            self::cleanupMetadataField($externalId);
        }
    }

    /**
     * Asserts that a given object fits the generic structure of a metadata field.
     * Optionally checks its type and compares it against the given values.
     *
     * @see https://cloudinary.com/documentation/admin_api#generic_structure_of_a_metadata_field
     *
     * @param ApiResponse $metadataField The object to test.
     * @param string      $type          The type of metadata field we expect.
     * @param array       $values        An associative array where the key is the name of the parameter to check
     *                                   and the value is the value.
     */
    private static function assertMetadataField($metadataField, $type = null, $values = [])
    {
        self::assertIsString($metadataField['external_id']);
        if ($type) {
            self::assertEquals($type, $metadataField['type']);
        } else {
            self::assertContains(
                $metadataField['type'],
                [
                    MetadataFieldType::INTEGER,
                    MetadataFieldType::STRING,
                    MetadataFieldType::DATE,
                    MetadataFieldType::ENUM,
                    MetadataFieldType::SET
                ]
            );
        }
        self::assertIsString($metadataField['label']);
        self::assertIsBool($metadataField['mandatory']);
        self::assertArrayHasKey('default_value', (array)$metadataField);
        self::assertArrayHasKey('validation', (array)$metadataField);
        if (in_array($metadataField['type'], [MetadataFieldType::ENUM, MetadataFieldType::SET], true)) {
            self::assertMetadataFieldDataSource($metadataField['datasource']);
        }

        foreach ($values as $key => $value) {
            self::assertEquals($value, $metadataField[$key]);
        }
    }

    /**
     * Asserts that a given object fits the generic structure of a metadata field datasource.
     *
     * @see https://cloudinary.com/documentation/admin_api#datasource_values
     *
     * @param $dataSource
     */
    private static function assertMetadataFieldDataSource($dataSource)
    {
        self::assertNotEmpty($dataSource);
        self::assertArrayHasKey('values', $dataSource);
        if (!empty($dataSource['values'])) {
            self::assertIsString($dataSource['values'][0]['value']);
            self::assertIsString($dataSource['values'][0]['external_id']);
            if (!empty($dataSource['values'][0]['state'])) {
                self::assertContains($dataSource['values'][0]['state'], ['active', 'inactive']);
            }
        }
    }

    /**
     * Asserts that a metadata field was deleted.
     *
     * @param $result
     */
    private static function assertDeleteMetadataField($result)
    {
        self::assertCount(1, $result);
        self::assertEquals('ok', $result['message']);
    }

    /**
     * Test getting a metadata field by external id.
     *
     * @see https://cloudinary.com/documentation/admin_api#get_a_metadata_field_by_external_id
     */
    public function testGetMetadataField()
    {
        $result = self::$adminApi->metadataFieldByFieldId(self::$EXTERNAL_ID_GENERAL);

        self::assertMetadataField(
            $result,
            MetadataFieldType::STRING,
            [
                'external_id' => self::$EXTERNAL_ID_GENERAL,
                'label' => self::$EXTERNAL_ID_GENERAL,
                'mandatory' => false
            ]
        );
    }

    /**
     * Test creating a date metadata field.
     *
     * @see https://cloudinary.com/documentation/admin_api#create_a_metadata_field
     */
    public function testCreateDateMetadataField()
    {
        $dateMetadataField = new DateMetadataField(self::$EXTERNAL_ID_DATE);
        $dateMetadataField->setExternalId(self::$EXTERNAL_ID_DATE);

        $result = self::$adminApi->addMetadataField($dateMetadataField);

        self::assertMetadataField(
            $result,
            MetadataFieldType::DATE,
            [
                'label' => self::$EXTERNAL_ID_DATE,
                'external_id' => self::$EXTERNAL_ID_DATE,
                'mandatory' => false
            ]
        );
    }

    /**
     * Test creating a set metadata field.
     *
     * @see https://cloudinary.com/documentation/admin_api#create_a_metadata_field
     */
    public function testCreateSetMetadataField()
    {
        $setMetadataField = new SetMetadataField(self::$EXTERNAL_ID_SET, self::$DATASOURCE_MULTIPLE);
        $setMetadataField->setExternalId(self::$EXTERNAL_ID_SET);

        $result = self::$adminApi->addMetadataField($setMetadataField);

        self::assertMetadataField(
            $result,
            MetadataFieldType::SET,
            [
                'label' => self::$EXTERNAL_ID_SET,
                'external_id' => self::$EXTERNAL_ID_SET,
                'mandatory' => false
            ]
        );
    }

    /**
     * Test updating a metadata field by external id.
     *
     * @see https://cloudinary.com/documentation/admin_api#update_a_metadata_field_by_external_id
     *
     * @throws ApiError
     */
    public function testUpdateMetadataField()
    {
        $newLabel = 'update_metadata_test_new_label_' . self::$EXTERNAL_ID_GENERAL;
        $newDefaultValue = 'update_metadata_test_new_default_value_' . self::$EXTERNAL_ID_GENERAL;

        $stringMetadataField = new StringMetadataField($newLabel);
        $stringMetadataField->setDefaultValue($newDefaultValue);
        $stringMetadataField->setMandatory(true);
        $result = self::$adminApi->updateMetadataField(self::$EXTERNAL_ID_GENERAL, $stringMetadataField);

        self::assertMetadataField(
            $result,
            MetadataFieldType::STRING,
            [
                'label' => $newLabel,
                'external_id' => self::$EXTERNAL_ID_GENERAL,
                'default_value' => $newDefaultValue,
                'mandatory' => true
            ]
        );
    }

    /**
     * Test updating a metadata field datasource.
     *
     * @see https://cloudinary.com/documentation/admin_api#update_a_metadata_field_datasource
     *
     * @throws ApiError
     */
    public function testUpdateMetadataFieldDataSource()
    {
        $result = self::$adminApi->updateMetadataFieldDatasource(
            self::$EXTERNAL_ID_ENUM,
            self::$DATASOURCE_SINGLE
        );

        self::assertMetadataFieldDataSource($result);
        self::assertArrayContainsArray($result['values'], self::$DATASOURCE_SINGLE[0]);
        self::assertCount(count(self::$DATASOURCE_MULTIPLE), $result['values']);
        self::assertEquals(self::$DATASOURCE_SINGLE[0]['value'], $result['values'][0]['value']);
    }

    /**
     * Test deleting a metadata field definition then attempting to create a new one with the same external id which
     * should fail.
     *
     * @throws ApiError
     */
    public function testDeleteMetadataFieldDoesNotReleaseExternalId()
    {
        self::markTestSkipped('Check the expected behaviour.');

        $result = self::$adminApi->deleteMetadataField(self::$EXTERNAL_ID_DELETE);

        self::assertDeleteMetadataField($result);

        $intMetadataField = new IntMetadataField(self::$EXTERNAL_ID_DELETE);
        $intMetadataField->setExternalId(self::$EXTERNAL_ID_DELETE);

        $this->expectException(BadRequest::class);
        $this->expectExceptionMessage('external id ' . self::$EXTERNAL_ID_DELETE . ' already exists');

        self::$adminApi->addMetadataField($intMetadataField);
    }

    /**
     * Test deleting entries in a metadata field datasource.
     *
     * @see https://cloudinary.com/documentation/admin_api#delete_entries_in_a_metadata_field_datasource
     *
     * @throws ApiError
     */
    public function testDeleteMetadataFieldDataSource()
    {
        $result = self::$adminApi->deleteDatasourceEntries(
            self::$EXTERNAL_ID_SET_2,
            [
                self::$DATASOURCE_ENTRY_EXTERNAL_ID
            ]
        );

        self::assertMetadataFieldDataSource($result);
        self::assertCount(count(self::$DATASOURCE_MULTIPLE) - 1, $result['values']);

        $values = array_column($result['values'], 'value');

        self::assertContains(self::$DATASOURCE_MULTIPLE[1]['value'], $values);
        self::assertContains(self::$DATASOURCE_MULTIPLE[2]['value'], $values);
    }

    /**
     * Test date field multi validation.
     *
     * @see https://cloudinary.com/documentation/admin_api#validating_data
     * @see https://cloudinary.com/documentation/admin_api#and_validation
     *
     * @throws Exception
     */
    public function testDateFieldDefaultValueValidation()
    {
        $pastDate = (new DateTime())->sub(new DateInterval('P3D'));
        $yesterdayDate = (new DateTime())->sub(new DateInterval('P1D'));
        $todayDate = new DateTime();
        $futureDate = (new DateTime())->add(new DateInterval('P3D'));
        $lastThreeDaysValidation = new AndValidator(
            [
                new DateGreaterThan($pastDate),
                new DateLessThan($todayDate)
            ]
        );

        $dateMetadataField = new DateMetadataField(self::$EXTERNAL_ID_DATE_VALIDATION);
        $dateMetadataField->setExternalId(self::$EXTERNAL_ID_DATE_VALIDATION);
        $dateMetadataField->setValidation($lastThreeDaysValidation);
        $dateMetadataField->setDefaultValue($yesterdayDate);
        $result = self::$adminApi->addMetadataField($dateMetadataField);

        self::assertMetadataField(
            $result,
            MetadataFieldType::DATE,
            [
                'validation' => $lastThreeDaysValidation->jsonSerialize(),
                'default_value' => $yesterdayDate->format('Y-m-d'),
            ]
        );

        // Test entering a metadata field with date validation and an invalid default value
        $dateMetadataField = new DateMetadataField(self::$EXTERNAL_ID_DATE_VALIDATION_2);
        $dateMetadataField->setExternalId(self::$EXTERNAL_ID_DATE_VALIDATION_2);
        $dateMetadataField->setValidation($lastThreeDaysValidation);
        $dateMetadataField->setDefaultValue($futureDate);

        $this->expectException(BadRequest::class);
        self::$adminApi->addMetadataField($dateMetadataField);
    }

    /**
     * Test integer field validation.
     */
    public function testIntegerFieldValidation()
    {
        $validation = new IntLessThan(5, true);

        // Test entering a metadata field with integer validation and a valid default value
        $intMetadataField = new IntMetadataField(self::$EXTERNAL_ID_INT_VALIDATION);
        $intMetadataField->setExternalId(self::$EXTERNAL_ID_INT_VALIDATION);
        $intMetadataField->setValidation($validation);
        $intMetadataField->setDefaultValue(5);
        $result = self::$adminApi->addMetadataField($intMetadataField);

        self::assertMetadataField(
            $result,
            MetadataFieldType::INTEGER,
            [
                'validation' => $validation->jsonSerialize(),
                'default_value' => 5,
            ]
        );

        // Test entering a metadata field with integer validation and a valid default value
        $intMetadataField = new IntMetadataField(self::$EXTERNAL_ID_INT_VALIDATION_2);
        $intMetadataField->setExternalId(self::$EXTERNAL_ID_INT_VALIDATION_2);
        $intMetadataField->setValidation($validation);
        $intMetadataField->setDefaultValue(6);

        $this->expectException(BadRequest::class);
        self::$adminApi->addMetadataField($intMetadataField);
    }

    /**
     * Test restoring a deleted entry in a metadata field datasource.
     *
     * @see https://cloudinary.com/documentation/admin_api#restore_entries_in_a_metadata_field_datasource
     *
     * @throws ApiError
     */
    public function testRestoreMetadataFieldDatasource()
    {
        // Begin by deleting a datasource entry
        $result = self::$adminApi->deleteDatasourceEntries(
            self::$EXTERNAL_ID_SET_3,
            [
                self::$DATASOURCE_ENTRY_EXTERNAL_ID
            ]
        );

        self::assertMetadataFieldDataSource($result);
        self::assertCount(2, $result['values']);

        // Restore datasource entry
        $result = self::$adminApi->restoreMetadataFieldDatasource(
            self::$EXTERNAL_ID_SET_3,
            [
                self::$DATASOURCE_ENTRY_EXTERNAL_ID
            ]
        );
        self::assertMetadataFieldDataSource($result);
        self::assertCount(3, $result['values']);
    }

    /**
     * Should order by asc or desc in a metadata field datasource.
     */
    public function testReorderMetadataFieldDatasource()
    {
        // datasource is set with values in the order v2, v3, v4
        $result = self::$adminApi->reorderMetadataFieldDatasource(self::$EXTERNAL_ID_SET_3, 'value', 'asc');

        self::assertMetadataFieldDataSource($result);
        // ascending order means v2 is the first value
        self::assertEquals(self::$DATASOURCE_MULTIPLE[0]['value'], $result['values'][0]['value']);

        $result = self::$adminApi->reorderMetadataFieldDatasource(self::$EXTERNAL_ID_SET_3, 'value', 'desc');

        self::assertMetadataFieldDataSource($result);
        // descending order means v4 is the first value
        self::assertEquals(self::$DATASOURCE_MULTIPLE[2]['value'], $result['values'][0]['value']);
    }
}
