<?php

namespace Cloudinary;

use Exception;
use Cloudinary;
use Cloudinary\Test\CloudinaryTestCase;
use Cloudinary\Metadata\DateMetadataField;
use Cloudinary\Metadata\EnumMetadataField;
use Cloudinary\Metadata\IntMetadataField;
use Cloudinary\Metadata\MetadataDataSource;
use Cloudinary\Metadata\MetadataFieldType;
use Cloudinary\Metadata\SetMetadataField;
use Cloudinary\Metadata\StringMetadataField;
use Cloudinary\Metadata\Validators\AndValidator;
use Cloudinary\Metadata\Validators\DateGreaterThan;
use Cloudinary\Metadata\Validators\DateLessThan;
use Cloudinary\Metadata\Validators\IntLessThan;
use DateInterval;
use DateTime;
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

        $api = new Api();

        $stringMetadataField = new StringMetadataField(self::$UNIQUE_EXTERNAL_ID_GENERAL);
        $stringMetadataField->setExternalId(self::$UNIQUE_EXTERNAL_ID_GENERAL);
        try {
            $api->add_metadata_field($stringMetadataField->jsonSerialize());
        } catch (Exception $e) {var_dump($e->getMessage());
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
            self::fail(
                'Exception thrown while deleting metadata fields in MetadataFieldsTest::tearDownAfterClass() - ' .
                $e->getMessage()
            );
        }

        $api->delete_resources_by_tag(UNIQUE_TEST_TAG);
    }

    /**
     * @param $metadataField
     */
    private function assertMetadataField($metadataField)
    {
        $this->assertNotEmpty($metadataField['type']);
        $this->assertNotEmpty($metadataField['external_id']);
        $this->assertNotEmpty($metadataField['label']);
        $this->assertArrayHasKey('mandatory', $metadataField);
        $this->assertArrayHasKey('default_value', $metadataField);
        $this->assertArrayHasKey('validation', $metadataField);
        if (in_array($metadataField['type'], [MetadataFieldType::ENUM, MetadataFieldType::SET])) {
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

        $this->assertMetadataField($result);
    }

    /**
     * Create a string metadata field
     *
     * @throws \Cloudinary\Api\GeneralError
     */
    public function testCreateStringMetadataField()
    {
        $stringMetadataField = new StringMetadataField(self::$UNIQUE_EXTERNAL_ID_STRING);
        $stringMetadataField->setExternalId(self::$UNIQUE_EXTERNAL_ID_STRING);

        $result = $this->api->add_metadata_field($stringMetadataField->jsonSerialize());

        $this->assertMetadataField($result);
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
        $intMetadataField = new IntMetadataField(self::$UNIQUE_EXTERNAL_ID_INT);
        $intMetadataField->setExternalId(self::$UNIQUE_EXTERNAL_ID_INT);

        $result = $this->api->add_metadata_field($intMetadataField->jsonSerialize());

        $this->assertMetadataField($result);
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
        $dateMetadataField = new DateMetadataField(self::$UNIQUE_EXTERNAL_ID_DATE);
        $dateMetadataField->setExternalId(self::$UNIQUE_EXTERNAL_ID_DATE);

        $result = $this->api->add_metadata_field($dateMetadataField->jsonSerialize());

        $this->assertMetadataField($result);
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
        $enumMetadataField = new EnumMetadataField(self::$UNIQUE_EXTERNAL_ID_ENUM, self::$DATASOURCE_MULTIPLE);
        $enumMetadataField->setDataSource(self::$DATASOURCE_SINGLE);
        $enumMetadataField->setExternalId(self::$UNIQUE_EXTERNAL_ID_ENUM);

        $result = $this->api->add_metadata_field($enumMetadataField->jsonSerialize());

        $this->assertMetadataField($result);
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
        $setMetadataField = new SetMetadataField(self::$UNIQUE_EXTERNAL_ID_SET, self::$DATASOURCE_MULTIPLE);
        $setMetadataField->setExternalId(self::$UNIQUE_EXTERNAL_ID_SET);

        $result = $this->api->add_metadata_field($setMetadataField->jsonSerialize());

        $this->assertMetadataField($result);
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
        $newLabel = self::$UNIQUE_EXTERNAL_ID_GENERAL . '-new';
        $newDefaultValue = self::$UNIQUE_EXTERNAL_ID_GENERAL . '-new';

        $stringMetadataField = new StringMetadataField($newLabel);
        $stringMetadataField->setDefaultValue($newDefaultValue);
        $stringMetadataField->setMandatory(true);
        $result = $this->api->update_metadata_field(self::$UNIQUE_EXTERNAL_ID_GENERAL, $stringMetadataField->jsonSerialize());

        $this->assertMetadataField($result);
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
        $enumMetadataField = new EnumMetadataField(self::$UNIQUE_EXTERNAL_ID_ENUM_2, self::$DATASOURCE_MULTIPLE);
        $enumMetadataField->setExternalId(self::$UNIQUE_EXTERNAL_ID_ENUM_2);

        $result = $this->api->add_metadata_field($enumMetadataField->jsonSerialize());

        $this->assertMetadataField($result);

        $metadataDataSource = new MetadataDataSource(self::$DATASOURCE_SINGLE);
        $result = $this->api->update_metadata_field_datasource(
            self::$UNIQUE_EXTERNAL_ID_ENUM_2,
            $metadataDataSource->jsonSerialize()['values']
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
        $tempMetadataFieldId = self::$UNIQUE_EXTERNAL_ID_INT . '-for-deletion';

        $intMetadataField = new IntMetadataField($tempMetadataFieldId);
        $intMetadataField->setExternalId($tempMetadataFieldId);
        $result = $this->api->add_metadata_field($intMetadataField->jsonSerialize());

        $this->assertMetadataField($result);

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
        $setMetadataField = new SetMetadataField(self::$UNIQUE_EXTERNAL_ID_SET_2, self::$DATASOURCE_MULTIPLE);
        $setMetadataField->setExternalId(self::$UNIQUE_EXTERNAL_ID_SET_2);

        $result = $this->api->add_metadata_field($setMetadataField->jsonSerialize());

        $this->assertMetadataField($result);

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
        $dateMax = new DateTime();
        $dateMin = (new DateTime())->sub(new DateInterval('P3D'));
        $legalValue = (new DateTime())->sub(new DateInterval('P1D'));
        $illegalValue = (new DateTime())->add(new DateInterval('P3D'));

        $dateMetadataField = new DateMetadataField(self::$UNIQUE_EXTERNAL_ID_DATE . '-date-validation-test');
        $dateMetadataField->setValidation(
            new AndValidator([
                new DateGreaterThan($dateMin),
                new DateLessThan($dateMax)
            ])
        );

        $dateMetadataField->setDefaultValue($legalValue);
        $result = $this->api->add_metadata_field($dateMetadataField->jsonSerialize());

        $this->assertMetadataField($result);

        $this->api->delete_metadata_field($result['external_id']);

        $hasException = false;
        try {
            $dateMetadataField->setDefaultValue($illegalValue);
            $this->api->add_metadata_field($dateMetadataField->jsonSerialize());
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
        $intMetadataField = new IntMetadataField(self::$UNIQUE_EXTERNAL_ID_DATE . '-int-validation-test');
        $intMetadataField->setValidation(new IntLessThan(5, true));

        $intMetadataField->setDefaultValue(5);
        $result = $this->api->add_metadata_field($intMetadataField->jsonSerialize());

        $this->assertMetadataField($result);

        $this->api->delete_metadata_field($result['external_id']);

        $hasException = false;
        try {
            $intMetadataField->setDefaultValue(6);
            $this->api->add_metadata_field($intMetadataField->jsonSerialize());
        } catch (BadRequest $e) {
            $hasException = true;
        }

        $this->assertTrue($hasException, "The metadata field with illegal value was added");
    }
}
