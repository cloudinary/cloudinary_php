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
use Cloudinary\Transformation\Qualifier;
use Cloudinary\Transformation\Transformation;
use PHPUnit\Framework\Constraint\IsType;

/**
 * Class AdaptiveStreamingProfilesTest
 */
final class AdaptiveStreamingProfilesTest extends IntegrationTestCase
{
    private static $STREAMING_PROFILE_STATIC;
    private static $STREAMING_PROFILE_AS_STRING;
    private static $STREAMING_PROFILE_AS_ARRAY;
    private static $STREAMING_PROFILE_AS_TRANSFORMATION;
    private static $STREAMING_PROFILE_UPDATE;
    private static $STREAMING_PROFILE_DELETE;

    private static $SMALL_IMAGE_FILL_REPRESENTATION_STRING = 'c_fill,w_10,h_10';
    private static $SMALL_IMAGE_FILL_REPRESENTATION_ARRAY = [
        'width' => '10',
        'height' => '10',
        'crop' => 'fill'
    ];

    private static $BIG_IMAGE_REPRESENTATION_STRING = 'w_1200,h_1100';
    private static $BIG_IMAGE_REPRESENTATION_ARRAY = [
        'width' => '1200',
        'height' => '1100'
    ];

    private static $STREAMING_PROFILE_DISPLAY_NAME;

    private static $TRANSFORMATION_AS_OBJECT;

    private static $STREAMING_PROFILES = [];

    /**
     * @throws ApiError
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        $id = self::$UNIQUE_TEST_ID;
        self::$STREAMING_PROFILES[] = self::$STREAMING_PROFILE_STATIC = 'streaming_profile_static_' . $id;
        self::$STREAMING_PROFILES[] = self::$STREAMING_PROFILE_AS_STRING = 'streaming_profile_as_string_' . $id;
        self::$STREAMING_PROFILES[] = self::$STREAMING_PROFILE_AS_ARRAY = 'streaming_profile_as_array_' . $id;
        self::$STREAMING_PROFILES[] = self::$STREAMING_PROFILE_UPDATE = 'streaming_profile_update_' . $id;
        self::$STREAMING_PROFILES[] = self::$STREAMING_PROFILE_DELETE = 'streaming_profile_delete_' . $id;
        self::$STREAMING_PROFILES[] = self::$STREAMING_PROFILE_AS_TRANSFORMATION = 'streaming_profile_transformation_'
            . $id;

        self::$STREAMING_PROFILE_DISPLAY_NAME = 'streaming_profile_display_name_' . $id;

        self::$TRANSFORMATION_AS_OBJECT = (new Transformation())->addAction(Qualifier::height(1));

        self::$adminApi->createStreamingProfile(
            self::$STREAMING_PROFILE_STATIC,
            [
                'representations' => [
                    self::$SMALL_IMAGE_FILL_REPRESENTATION_STRING,
                    self::$BIG_IMAGE_REPRESENTATION_STRING
                ],
            ]
        );
    }

    public static function tearDownAfterClass()
    {
        foreach (self::$STREAMING_PROFILES as $streamingProfile) {
            self::cleanupStreamingProfile($streamingProfile);
        }

        parent::tearDownAfterClass();
    }

    /**
     * Get a list of streaming profiles
     *
     * @throws ApiError
     */
    public function testListStreamingProfiles()
    {
        $result = self::$adminApi->listStreamingProfiles();

        self::assertNotEmpty($result['data']);
        self::assertObjectStructure(
            $result['data'][0],
            [
                'display_name' => [IsType::TYPE_STRING, IsType::TYPE_NULL],
                'name' => IsType::TYPE_STRING,
                'predefined' => IsType::TYPE_BOOL
            ]
        );
    }

    /**
     * Get details of a single streaming profile
     *
     * @throws ApiError
     */
    public function testGetStreamingProfileDetails()
    {
        $result = self::$adminApi->getStreamingProfile(self::$STREAMING_PROFILE_STATIC);

        self::assertValidStreamingProfile($result, ['name' => self::$STREAMING_PROFILE_STATIC]);
        self::assertCount(2, $result['data']['representations']);
        self::assertArrayContainsArray(
            $result['data']['representations'],
            ['transformation' => [self::$BIG_IMAGE_REPRESENTATION_ARRAY]]
        );
    }

    /**
     * Create a streaming profile with representations given as string
     *
     * @throws ApiError
     */
    public function testCreateStreamingProfileFromRepresentationsString()
    {
        $result = self::$adminApi->createStreamingProfile(
            self::$STREAMING_PROFILE_AS_STRING,
            [
                'display_name' => self::$STREAMING_PROFILE_DISPLAY_NAME,
                'representations' => [self::$SMALL_IMAGE_FILL_REPRESENTATION_STRING]
            ]
        );

        self::assertValidStreamingProfile(
            $result,
            [
                'name' => self::$STREAMING_PROFILE_AS_STRING,
                'display_name' => self::$STREAMING_PROFILE_DISPLAY_NAME
            ]
        );

        $result = self::$adminApi->getStreamingProfile(self::$STREAMING_PROFILE_AS_STRING);

        self::assertValidStreamingProfile(
            $result,
            [
                'name' => self::$STREAMING_PROFILE_AS_STRING,
                'display_name' => self::$STREAMING_PROFILE_DISPLAY_NAME
            ]
        );
        self::assertCount(1, $result['data']['representations']);
        self::assertCount(1, $result['data']['representations'][0]['transformation']);
        self::assertArrayContainsArray(
            $result['data']['representations'],
            ['transformation' => [self::$SMALL_IMAGE_FILL_REPRESENTATION_ARRAY]]
        );
    }

    /**
     * Create a streaming profile with representations given as an array
     *
     * @throws ApiError
     */
    public function testCreateStreamingProfileFromRepresentationsArray()
    {
        $result = self::$adminApi->createStreamingProfile(
            self::$STREAMING_PROFILE_AS_ARRAY,
            [
                'display_name' => self::$STREAMING_PROFILE_DISPLAY_NAME,
                'representations' => [
                    [
                        'transformation' => self::$SMALL_IMAGE_FILL_REPRESENTATION_ARRAY
                    ],
                ],
            ]
        );

        self::assertValidStreamingProfile(
            $result,
            [
                'name' => self::$STREAMING_PROFILE_AS_ARRAY,
                'display_name' => self::$STREAMING_PROFILE_DISPLAY_NAME
            ]
        );

        $result = self::$adminApi->getStreamingProfile(self::$STREAMING_PROFILE_AS_ARRAY);

        self::assertValidStreamingProfile(
            $result,
            [
                'name' => self::$STREAMING_PROFILE_AS_ARRAY,
                'display_name' => self::$STREAMING_PROFILE_DISPLAY_NAME
            ]
        );
        self::assertCount(1, $result['data']['representations']);
        self::assertCount(1, $result['data']['representations'][0]['transformation']);
        self::assertArrayContainsArray(
            $result['data']['representations'],
            ['transformation' => [self::$SMALL_IMAGE_FILL_REPRESENTATION_ARRAY]]
        );
    }

    /**
     * Create a streaming profile with representations given as a Transformation object
     *
     * @throws ApiError
     */
    public function testCreateStreamingProfileFromRepresentationsObject()
    {
        $result = self::$adminApi->createStreamingProfile(
            self::$STREAMING_PROFILE_AS_TRANSFORMATION,
            [
                'representations' => [
                    [
                        self::$TRANSFORMATION_AS_OBJECT
                    ],
                ],
            ]
        );

        self::assertValidStreamingProfile($result, ['name' => self::$STREAMING_PROFILE_AS_TRANSFORMATION]);

        $result = self::$adminApi->getStreamingProfile(self::$STREAMING_PROFILE_AS_TRANSFORMATION);

        self::assertValidStreamingProfile($result, ['name' => self::$STREAMING_PROFILE_AS_TRANSFORMATION]);
        self::assertCount(1, $result['data']['representations']);
        self::assertCount(1, $result['data']['representations'][0]['transformation']);
        self::assertEquals(1, $result['data']['representations'][0]['transformation'][0]['height']);
    }

    /**
     * Update an existing streaming profile
     *
     * @throws ApiError
     */
    public function testUpdateStreamingProfile()
    {
        self::$adminApi->createStreamingProfile(
            self::$STREAMING_PROFILE_UPDATE,
            [
                'representations' => [
                    self::$SMALL_IMAGE_FILL_REPRESENTATION_STRING
                ]
            ]
        );
        // Update from a small image transformation to a big image
        $result = self::$adminApi->updateStreamingProfile(
            self::$STREAMING_PROFILE_UPDATE,
            [
                'representations' => [
                    self::$BIG_IMAGE_REPRESENTATION_STRING
                ]
            ]
        );
        self::assertEquals('updated', $result['message']);

        $result = self::$adminApi->getStreamingProfile(self::$STREAMING_PROFILE_UPDATE);

        self::assertCount(1, $result['data']['representations'][0]['transformation']);
        self::assertArrayContainsArray(
            $result['data']['representations'],
            ['transformation' => [self::$BIG_IMAGE_REPRESENTATION_ARRAY]]
        );
    }

    /**
     * Delete or revert the specified streaming profile
     *
     * @throws ApiError
     */
    public function testDeleteStreamingProfile()
    {
        self::$adminApi->createStreamingProfile(
            self::$STREAMING_PROFILE_DELETE,
            [
                'representations' => [
                    self::$SMALL_IMAGE_FILL_REPRESENTATION_STRING
                ]
            ]
        );
        $result = self::$adminApi->getStreamingProfile(self::$STREAMING_PROFILE_DELETE);

        self::assertValidStreamingProfile($result, ['name' => self::$STREAMING_PROFILE_DELETE]);

        $result = self::$adminApi->deleteStreamingProfile(self::$STREAMING_PROFILE_DELETE);

        self::assertEquals('deleted', $result['message']);

        $this->expectException(NotFound::class);
        self::$adminApi->getStreamingProfile(self::$STREAMING_PROFILE_DELETE);
    }
}
