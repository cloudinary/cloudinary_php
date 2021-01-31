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

use Cloudinary\Api\Exception\ApiError;
use Cloudinary\Api\Exception\NotFound;
use Cloudinary\Test\Integration\IntegrationTestCase;

/**
 * Class TransformationsTest
 */
final class TransformationsTest extends IntegrationTestCase
{
    const TRANSFORMATION_NAME_PREFIX = 't_';

    private static $TRANSFORMATION_NAME;
    private static $TRANSFORMATION_NAME_STRING;
    private static $TRANSFORMATION_NAME_PARAMETERS;
    private static $TRANSFORMATION_NAME_DELETE;
    private static $TRANSFORMATION_PARAMETER_AS_STRING;
    private static $TRANSFORMATION_PARAMETER_AS_ARRAY;

    private static $TRANSFORMATIONS = [];

    const TRANSFORMATION_PARAMETER_UPDATE = [
        'crop' => 'scale',
        'width' => 103
    ];

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        $id = self::$UNIQUE_TEST_ID;
        self::$TRANSFORMATIONS[] = self::$TRANSFORMATION_NAME = 'transformation_name_' . $id;
        self::$TRANSFORMATIONS[] = self::$TRANSFORMATION_NAME_STRING = 'transformation_name_string_' . $id;
        self::$TRANSFORMATIONS[] = self::$TRANSFORMATION_NAME_PARAMETERS = 'transformation_name_parameters_' . $id;
        self::$TRANSFORMATIONS[] = self::$TRANSFORMATION_NAME_DELETE = 'transformation_name_delete_' . $id;
        self::$TRANSFORMATION_PARAMETER_AS_ARRAY = [
            'width' => mt_rand(1, 9999),
            'height' => mt_rand(1, 9999),
            'crop' => 'fill',
        ];
        self::$TRANSFORMATIONS[] = self::$TRANSFORMATION_PARAMETER_AS_STRING = ''
            . 'c_' . self::$TRANSFORMATION_PARAMETER_AS_ARRAY['crop'] . ','
            . 'h_' . self::$TRANSFORMATION_PARAMETER_AS_ARRAY['height'] . ','
            . 'w_' . self::$TRANSFORMATION_PARAMETER_AS_ARRAY['width'];

        self::$adminApi->createTransformation(
            self::$TRANSFORMATION_NAME,
            self::$TRANSFORMATION_PARAMETER_AS_STRING
        );
    }

    public static function tearDownAfterClass()
    {
        foreach (self::$TRANSFORMATIONS as $transformation) {
            self::cleanupTransformation($transformation);
        }

        parent::tearDownAfterClass();
    }

    /**
     * Get all transformations
     */
    public function testGetAllTransformations()
    {
        $result = self::$adminApi->transformations();

        self::assertNotEmpty($result['transformations']);
        self::assertValidTransformation($result['transformations'][0]);
    }

    /**
     * Get details of a single transformation by name
     */
    public function testGetDetailTransformationByName()
    {
        $result = self::$adminApi->transformation(self::$TRANSFORMATION_NAME);

        self::assertValidTransformation(
            $result,
            [
                'allowed_for_strict' => true,
                'used' => false,
                'named' => true,
                'name' => self::TRANSFORMATION_NAME_PREFIX . self::$TRANSFORMATION_NAME
            ],
            self::$TRANSFORMATION_PARAMETER_AS_ARRAY
        );
    }

    /**
     * Get details of a transformation by parameters
     */
    public function testGetDetailTransformationByParameters()
    {
        $this->markTestIncomplete('This test has not been implemented yet.');
    }

    /**
     * Create a named transformation by string
     */
    public function testCreateNamedTransformationByString()
    {
        self::$adminApi->createTransformation(
            self::$TRANSFORMATION_NAME_STRING,
            self::$TRANSFORMATION_PARAMETER_AS_STRING
        );
        $result = self::$adminApi->transformation(self::$TRANSFORMATION_NAME_STRING);

        self::assertValidTransformation(
            $result,
            [
                'allowed_for_strict' => true,
                'used' => false,
                'named' => true,
                'name' => self::TRANSFORMATION_NAME_PREFIX . self::$TRANSFORMATION_NAME_STRING
            ],
            self::$TRANSFORMATION_PARAMETER_AS_ARRAY
        );
    }

    /**
     * Create a named transformation by parameters
     */
    public function testCreateNamedTransformationByParameters()
    {
        self::$adminApi->createTransformation(
            self::$TRANSFORMATION_NAME_PARAMETERS,
            self::$TRANSFORMATION_PARAMETER_AS_ARRAY
        );
        $result = self::$adminApi->transformation(self::$TRANSFORMATION_NAME_PARAMETERS);

        self::assertValidTransformation(
            $result,
            [
                'allowed_for_strict' => true,
                'used' => false,
                'named' => true,
                'name' => self::TRANSFORMATION_NAME_PREFIX . self::$TRANSFORMATION_NAME_PARAMETERS
            ],
            self::$TRANSFORMATION_PARAMETER_AS_ARRAY
        );
    }

    /**
     * Allow and Disallow transformation by name
     *
     * @throws ApiError
     */
    public function testAllowDisallowTransformationByName()
    {
        $result = self::$adminApi->transformation(self::$TRANSFORMATION_NAME);

        self::assertTrue($result['allowed_for_strict']);

        $result = self::$adminApi->updateTransformation(self::$TRANSFORMATION_NAME, ['allowed_for_strict' => 0]);

        self::assertEquals('updated', $result['message']);

        $result = self::$adminApi->transformation(self::$TRANSFORMATION_NAME);

        self::assertFalse($result['allowed_for_strict']);

        $result = self::$adminApi->updateTransformation(self::$TRANSFORMATION_NAME, ['allowed_for_strict' => 1]);

        self::assertEquals('updated', $result['message']);

        $result = self::$adminApi->transformation(self::$TRANSFORMATION_NAME);

        self::assertTrue($result['allowed_for_strict']);
    }

    /**
     * Allow and Disallow transformation by parameters
     */
    public function testAllowDisallowTransformationByParameters()
    {
        $this->markTestIncomplete('This test has not been implemented yet.');
    }

    /**
     * Update a named transformation
     *
     * @throws ApiError
     */
    public function testUpdateNamedTransformation()
    {
        $result = self::$adminApi->updateTransformation(
            self::$TRANSFORMATION_NAME,
            ['unsafe_update' => self::TRANSFORMATION_PARAMETER_UPDATE]
        );

        self::assertEquals('updated', $result['message']);

        $result = self::$adminApi->transformation(self::$TRANSFORMATION_NAME);

        self::assertValidTransformation($result, [], self::TRANSFORMATION_PARAMETER_UPDATE);
    }

    /**
     * Delete transformation by name
     *
     * @throws ApiError
     */
    public function testDeleteTransformationByName()
    {
        self::$adminApi->createTransformation(
            self::$TRANSFORMATION_NAME_DELETE,
            self::$TRANSFORMATION_PARAMETER_AS_STRING
        );

        $result = self::$adminApi->transformation(self::$TRANSFORMATION_NAME_DELETE);

        self::assertValidTransformation(
            $result,
            [
                'allowed_for_strict' => true,
                'used' => false,
                'named' => true,
                'name' => self::TRANSFORMATION_NAME_PREFIX . self::$TRANSFORMATION_NAME_DELETE
            ]
        );

        $result = self::$adminApi->deleteTransformation(self::$TRANSFORMATION_NAME_DELETE);

        self::assertEquals('deleted', $result['message']);

        $this->expectException(NotFound::class);
        self::$adminApi->transformation(self::$TRANSFORMATION_NAME_DELETE);
    }

    /**
     * Delete transformation by parameters
     *
     * @throws ApiError
     */
    public function testDeleteTransformationByParameters()
    {
        self::$adminApi->createTransformation(
            self::$TRANSFORMATION_PARAMETER_AS_STRING,
            self::$TRANSFORMATION_PARAMETER_AS_STRING
        );

        $result = self::$adminApi->transformation(self::$TRANSFORMATION_PARAMETER_AS_STRING);

        self::assertValidTransformation(
            $result,
            [
                'allowed_for_strict' => true,
                'used' => false,
                'named' => false,
                'name' => self::$TRANSFORMATION_PARAMETER_AS_STRING
            ]
        );

        $result = self::$adminApi->deleteTransformation(self::$TRANSFORMATION_PARAMETER_AS_ARRAY);

        self::assertEquals('deleted', $result['message']);

        $this->expectException(NotFound::class);
        self::$adminApi->transformation(self::$TRANSFORMATION_PARAMETER_AS_STRING);
    }
}
