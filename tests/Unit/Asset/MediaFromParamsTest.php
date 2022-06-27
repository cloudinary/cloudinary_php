<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Test\Unit\Asset;

use Cloudinary\ArrayUtils;
use Cloudinary\Asset\AssetType;
use Cloudinary\Asset\DeliveryType;
use Cloudinary\Asset\Image;
use Cloudinary\Asset\Media;
use Cloudinary\Configuration\Configuration;
use Cloudinary\Configuration\UrlConfig;
use Cloudinary\Utils;
use InvalidArgumentException;
use UnexpectedValueException;

/**
 * Class MediaFromParamsTest
 *
 * These tests are taken from the v1 SDK.
 */
final class MediaFromParamsTest extends AssetTestCase
{
    /**
     * Should use cloud_name from config
     */
    public function testCloudName()
    {
        self::assertMediaFromParamsUrl(
            self::IMAGE_NAME
        );
    }

    /**
     * Should allow overriding cloud_name in $options
     */
    public function testCloudNameOptions()
    {
        $options = ['cloud_name' => 'test321'];

        self::assertMediaFromParamsUrl(
            self::IMAGE_NAME,
            $options,
            $options
        );
    }

    /**
     * Should allow overriding cloud_name in $options
     */
    public function testCloudNameNoConfig()
    {
        self::clearEnvironment();

        $options = ['cloud_name' => 'test321'];

        self::assertMediaFromParamsUrl(
            self::IMAGE_NAME,
            $options,
            $options
        );
    }

    public function testNoConfigThrowsException()
    {
        self::clearEnvironment();

        $this->expectException(InvalidArgumentException::class);

        self::assertMediaFromParamsUrl(
            self::IMAGE_NAME
        );
    }

    /**
     * Should use default secure distribution if secure=true
     */
    public function testSecureDistribution()
    {
        $options = ['secure' => true];

        self::assertMediaFromParamsUrl(
            self::IMAGE_NAME,
            $options,
            ['protocol' => self::PROTOCOL_HTTPS]
        );

        $options = ['secure' => false];

        self::assertMediaFromParamsUrl(
            self::IMAGE_NAME,
            $options,
            ['protocol' => self::PROTOCOL_HTTP]
        );
    }

    /**
     * Should respect "secure" configuration key set by user.
     */
    public function testSecureDistributionFromConfig()
    {
        Configuration::instance()->url->secure();

        self::assertMediaFromParamsUrl(
            self::IMAGE_NAME,
            [],
            ['protocol' => self::PROTOCOL_HTTPS]
        );

        Configuration::instance()->init(['cloud' => ['cloud_name' => self::CLOUD_NAME], 'url' => ['secure' => true]]);

        self::assertMediaFromParamsUrl(
            self::IMAGE_NAME,
            [],
            ['protocol' => self::PROTOCOL_HTTPS]
        );
    }

    /**
     * Should allow overwriting secure distribution if secure=TRUE
     */
    public function testSecureDistributionOverwrite()
    {
        $options = ['secure_distribution' => self::TEST_HOSTNAME, 'secure' => true];

        self::assertMediaFromParamsUrl(
            self::IMAGE_NAME,
            $options,
            ['hostname' => self::TEST_HOSTNAME, 'protocol' => self::PROTOCOL_HTTPS]
        );

        $options = ['secure_cname' => self::TEST_HOSTNAME, 'secure' => true];

        self::assertMediaFromParamsUrl(
            self::IMAGE_NAME,
            $options,
            ['hostname' => self::TEST_HOSTNAME, 'protocol' => self::PROTOCOL_HTTPS]
        );
    }

    /**
     * Should support secure_cdn_subdomain true override with secure
     */
    public function testSecureSubdomainTrue()
    {
        $options = [
            'cdn_subdomain'        => true,
            'secure'               => true,
            'secure_cdn_subdomain' => true,
            'private_cdn'          => true,
        ];
        self::assertMediaFromParamsUrl(
            self::IMAGE_NAME,
            $options,
            [
                'hostname'   => self::CLOUD_NAME . '-res-2.' . UrlConfig::DEFAULT_DOMAIN,
                'cloud_name' => '',
                'protocol'   => self::PROTOCOL_HTTPS,
            ]
        );
    }

    /**
     * should use format from $options
     */
    public function testFormat()
    {
        $options = ['format' => self::IMG_EXT];

        self::assertMediaFromParamsUrl(
            self::ASSET_ID,
            $options,
            ['suffix' => '.' . self::IMG_EXT]
        );
    }

    /**
     * @return array
     */
    public function variousOptions()
    {
        return [
            'Should use x, y, radius, prefix, gravity and quality from $options' => [
                [
                    'x'       => 1,
                    'y'       => 2,
                    'radius'  => 3,
                    'gravity' => 'center',
                    'quality' => 0.4,
                    'prefix'  => 'a',
                    'opacity' => 20,
                ],
                'g_center,o_20,p_a,q_0.4,r_3,x_1,y_2',
            ],
            'Gravity auto'                                                       => [
                [
                    'gravity' => 'auto',
                    'crop'    => 'crop',
                    'width'   => 0.5,
                ],
                'c_crop,g_auto,w_0.5',
            ],
            'Gravity auto with params'                                           => [
                [
                    'gravity' => 'auto:ocr_text',
                    'crop'    => 'crop',
                    'width'   => 0.5,
                ],
                'c_crop,g_auto:ocr_text,w_0.5',
            ],
            'Gravity ocr_text '                                                  => [
                [
                    'gravity' => 'ocr_text',
                    'crop'    => 'crop',
                    'width'   => 0.5,
                ],
                'c_crop,g_ocr_text,w_0.5',
            ],
        ];
    }

    /**
     * @dataProvider variousOptions
     *
     * @param $options
     * @param $expectedTransformation
     */
    public function testVariousOptions($options, $expectedTransformation)
    {
        self::assertMediaFromParamsUrl(
            self::IMAGE_NAME,
            $options,
            ['path' => $expectedTransformation]
        );
    }

    /**
     * Should use type from $options
     */
    public function testType()
    {
        $options = ['type' => DeliveryType::FACEBOOK];

        self::assertMediaFromParamsUrl(
            self::IMAGE_NAME,
            $options,
            ['delivery_type' => DeliveryType::FACEBOOK]
        );
    }

    /**
     * Should use resource_type from $options
     */
    public function testResourceType()
    {
        $options = ['resource_type' => AssetType::RAW];

        self::assertMediaFromParamsUrl(
            self::IMAGE_NAME,
            $options,
            ['asset_type' => AssetType::RAW]
        );
    }

    /**
     * Should ignore http links only if type is not given
     */
    public function testIgnoreHttp()
    {
        //TODO: discuss if this is still relevant (Currently not supported)

//        self::assertMediaFromParamsUrl(
//            'http://blah.com/hello'
//        );

        $options = ['type' => DeliveryType::FETCH];

        self::assertMediaFromParamsUrl(
            'http://blah.com/hello',
            $options,
            ['delivery_type' => DeliveryType::FETCH]
        );
    }

    /**
     * Should escape fetch urls
     */
    public function testFetch()
    {
        $options = ['type' => DeliveryType::FETCH];

        self::assertMediaFromParamsUrl(
            'http://blah.com/hello?a=b',
            $options,
            [
                'delivery_type' => DeliveryType::FETCH,
                'source'        => 'http://blah.com/hello%3Fa%3Db',
            ]
        );
    }

    /**
     * Tests force_version parameter under different conditions.
     */
    public function testForceVersion()
    {
        self::assertMediaFromParamsUrl(
            self::IMAGE_IN_FOLDER,
            [],
            ['path' => self::DEFAULT_ASSET_VERSION_STR]
        );

        // Should not set default version v1 to resources stored in folders if force_version is set to false
        self::assertMediaFromParamsUrl(
            self::IMAGE_IN_FOLDER,
            ['force_version' => false]
        );

        // Explicitly set version is always passed
        self::assertMediaFromParamsUrl(
            self::IMAGE_IN_FOLDER,
            [
                'version'       => self::TEST_ASSET_VERSION,
                'force_version' => false,
            ],
            ['path' => self::TEST_ASSET_VERSION_STR]
        );

        // Should use force_version from config
        Configuration::instance()->url->forceVersion = false;

        self::assertMediaFromParamsUrl(
            self::IMAGE_IN_FOLDER
        );

        // Should override config with options
        self::assertMediaFromParamsUrl(
            self::IMAGE_IN_FOLDER,
            [
                'force_version' => true,

            ],
            ['path' => self::DEFAULT_ASSET_VERSION_STR]
        );
    }

    public function testShorten()
    {
        $options = ['shorten' => true];

        self::assertMediaFromParamsUrl(
            self::IMAGE_NAME,
            $options,
            ['full_path' => 'iu/' . self::IMAGE_NAME]
        );

        $options = ['shorten' => true, 'type' => DeliveryType::PRIVATE_DELIVERY];

        self::assertMediaFromParamsUrl(
            self::IMAGE_NAME,
            $options,
            ['delivery_type' => DeliveryType::PRIVATE_DELIVERY]
        );
    }

    /**
     * @return array
     */
    public function expectedMediaSignatures()
    {
        return [
            'Should sign transformation and ignore version'                          => [
                [
                    'version'        => 1234,
                    'transformation' => ['crop' => 'crop', 'width' => 10, 'height' => 20],
                    'sign_url'       => true,
                ],
                's--Ai4Znfl3--/c_crop,h_20,w_10/v1234',
            ],
            'Should ignore version'                                                  => [
                [
                    'version'  => 1234,
                    'sign_url' => true,
                ],
                's----SjmNDA--/v1234',
            ],
            'Should sign transformation '                                            => [
                [
                    'transformation' => ['crop' => 'crop', 'width' => 10, 'height' => 20],
                    'sign_url'       => true,
                ],
                's--Ai4Znfl3--/c_crop,h_20,w_10',
            ],
            'Should sign transformation and ignore type'                             => [
                [
                    'type'           => DeliveryType::AUTHENTICATED,
                    'transformation' => ['crop' => 'crop', 'width' => 10, 'height' => 20],
                    'sign_url'       => true,
                ],
                's--Ai4Znfl3--/c_crop,h_20,w_10',
            ],
            'Should sign remote URL'                                                 => [
                [
                    'type'     => DeliveryType::FETCH,
                    'version'  => 1234,
                    'sign_url' => true,
                    'source'   => 'http://google.com/path/to/image.png',
                ],
                's--hH_YcbiS--/v1234',
            ],
            'Should sign an URL with a short signature by default'                   => [
                [
                    'sign_url' => true,
                    'source'   => 'sample.jpg',
                ],
                's--v2fTPYTu--',
            ],
            'Should sign an URL with a long signature if long_url_signature is true' => [
                [
                    'sign_url'           => true,
                    'long_url_signature' => true,
                    'source'             => 'sample.jpg',
                ],
                's--2hbrSMPOjj5BJ4xV7SgFbRDevFaQNUFf--',
            ],
            'Should sign a URL with a short signature by default and use SHA256 when configured' => [
                [
                    'sign_url' => true,
                    'signature_algorithm' => Utils::ALGO_SHA256,
                    'source' => 'sample.jpg',
                ],
                's--2hbrSMPO--'
            ],
        ];
    }

    /**
     * @dataProvider expectedMediaSignatures
     *
     * @param $options
     * @param $expectedPath
     */
    public function testMediaSignedUrl($options, $expectedPath)
    {
        // Use values from the previous SDK to preserve signatures.
        $publicIdToSign = ArrayUtils::pop($options, 'source', 'image.jpg');

        Configuration::instance()->cloud->apiSecret = 'b';

        $deliveryType = ArrayUtils::get($options, 'type', DeliveryType::UPLOAD);

        self::assertMediaFromParamsUrl(
            $publicIdToSign,
            $options,
            [
                'path'          => $expectedPath,
                'delivery_type' => $deliveryType,
            ]
        );
    }

    /**
     * @return array
     */
    public function expectedFileSignatures()
    {
        return [
            'Should sign an URL with a short signature by default'                   => [
                [
                    'sign_url' => true,
                    'source'   => 'sample.jpg',
                ],
                's--v2fTPYTu--',
            ],
            'Should sign an URL with a long signature if long_url_signature is true' => [
                [
                    'sign_url'           => true,
                    'long_url_signature' => true,
                    'source'             => 'sample.jpg',
                ],
                's--2hbrSMPOjj5BJ4xV7SgFbRDevFaQNUFf--',
            ],
        ];
    }

    /**
     * @dataProvider expectedFileSignatures
     *
     * @param $options
     * @param $expectedPath
     */
    public function testFileSignedUrl($options, $expectedPath)
    {
        // Use values from the previous SDK to preserve signatures.
        $publicIdToSign = ArrayUtils::pop($options, 'source', 'image.jpg');

        Configuration::instance()->cloud->apiSecret = 'b';

        $deliveryType = ArrayUtils::get($options, 'type', DeliveryType::UPLOAD);

        self::assertFileFromParamsUrl(
            $publicIdToSign,
            $options,
            [
                'path'          => $expectedPath,
                'delivery_type' => $deliveryType,
            ]
        );
    }

    /**
     * Should escape public_ids
     */
    public function testEscapePublicId()
    {
        $tests = [
            'a b'                    => 'a%20b',
            'a+b'                    => 'a%2Bb',
            'a%20b'                  => 'a%20b',
            'a-b'                    => 'a-b',
            'a??b'                   => 'a%3F%3Fb',
            'parentheses(interject)' => 'parentheses%28interject%29',
        ];
        foreach ($tests as $source => $target) {
            self::assertMediaFromParamsUrl($source, [], ['source' => $target]);
        }
    }

    /**
     * Should support url_suffix in shared distribution
     */
    public function testAllowUrlSuffixInShared()
    {
        $options = ['url_suffix' => self::URL_SUFFIX];

        self::assertMediaFromParamsUrl(
            self::IMAGE_NAME,
            $options,
            ['full_path' => 'images/' . self::URL_SUFFIXED_IMAGE_NAME]
        );
    }

    /**
     * Should disallow url_suffix in non upload types
     *
     */
    public function testDisallowUrlSuffixWithNonUploadTypes()
    {
        self::assertErrorThrowing(
            function () {
                $options = ['url_suffix' => self::URL_SUFFIX, 'private_cdn' => true, 'type' => DeliveryType::FACEBOOK];
                $url     = Media::fromParams(self::IMAGE_NAME, $options)->toUrl();
            }
        );
    }

    /**
     * Should disallow url_suffix with '.'
     */
    public function testDisallowSuffixWithDot()
    {
        //
        $options = ['url_suffix' => 'hello.world', 'private_cdn' => true];

        $this->expectException(UnexpectedValueException::class);
        self::assertMediaFromParamsUrl(
            self::IMAGE_NAME,
            $options
        );
    }

    /**
     * Should disallow url_suffix with '/'
     */
    public function testDisallowSuffixWithSlash()
    {
        $options = ['url_suffix' => 'hello/world', 'private_cdn' => true];

        $this->expectException(UnexpectedValueException::class);
        self::assertMediaFromParamsUrl(
            self::IMAGE_NAME,
            $options
        );
    }

    /**
     * Should support url_suffix for private_cdn
     */
    public function testUrlSuffixForPrivateCdn()
    {
        $options = ['url_suffix' => self::URL_SUFFIX, 'private_cdn' => true];

        self::assertMediaFromParamsUrl(
            self::IMAGE_NAME,
            $options,
            [
                'hostname'   => self::PRIVATE_CDN_HOSTNAME,
                'cloud_name' => '',
                'full_path'  => 'images/' . self::URL_SUFFIXED_IMAGE_NAME,
            ]
        );

        $options = ['url_suffix' => self::URL_SUFFIX, 'transformation' => ['angle' => 0], 'private_cdn' => true];

        self::assertMediaFromParamsUrl(
            self::IMAGE_NAME,
            $options,
            [
                'hostname'   => self::PRIVATE_CDN_HOSTNAME,
                'cloud_name' => '',
                'full_path'  => 'images/a_0/' . self::URL_SUFFIXED_IMAGE_NAME,
            ]
        );
    }

    /**
     * Should put format after url_suffix
     */
    public function testFormatAfterUrlSuffix()
    {
        $options = ['url_suffix' => self::URL_SUFFIX, 'private_cdn' => true, 'format' => self::IMG_EXT];

        self::assertMediaFromParamsUrl(
            self::ASSET_ID,
            $options,
            [
                'hostname'   => self::PRIVATE_CDN_HOSTNAME,
                'cloud_name' => '',
                'full_path'  => 'images/' . self::URL_SUFFIXED_IMAGE_NAME,
            ]
        );
    }

    /**
     * Should not sign the url_suffix
     */
    public function testDontSignTheUrlSuffix()
    {
        //
        $options = ['format' => self::IMG_EXT_JPG, 'sign_url' => true];

        $referenceImage = Image::fromParams(self::IMAGE_NAME, $options);

        $expectedSignature = self::invokeNonPublicMethod($referenceImage, 'finalizeSimpleSignature');

        $options = [
            'url_suffix'  => self::URL_SUFFIX,
            'private_cdn' => true,
            'format'      => self::IMG_EXT_JPG,
            'sign_url'    => true,
        ];

        self::assertMediaFromParamsUrl(
            self::IMAGE_NAME,
            $options,
            [
                'hostname'   => self::PRIVATE_CDN_HOSTNAME,
                'cloud_name' => '',
                'full_path'  => "images/$expectedSignature/" . self::URL_SUFFIXED_ASSET_ID . '.' . self::IMG_EXT_JPG,
            ]
        );

        $options = ['format' => self::IMG_EXT_JPG, 'angle' => 0, 'sign_url' => true];

        $referenceTImage = Image::fromParams(self::IMAGE_NAME, $options);

        $expectedTransformedSignature = self::invokeNonPublicMethod($referenceTImage, 'finalizeSimpleSignature');

        $options = [
            'url_suffix'     => self::URL_SUFFIX,
            'private_cdn'    => true,
            'format'         => self::IMG_EXT_JPG,
            'transformation' => ['angle' => 0],
            'sign_url'       => true,
        ];

        self::assertMediaFromParamsUrl(
            self::IMAGE_NAME,
            $options,
            [
                'hostname'   => self::PRIVATE_CDN_HOSTNAME,
                'cloud_name' => '',
                'full_path'  => "images/$expectedTransformedSignature/a_0/" . self::URL_SUFFIXED_ASSET_ID . '.'
                                . self::IMG_EXT_JPG,
            ]
        );
    }

    /**
     * Should support url_suffix for raw uploads
     */
    public function testUrlSuffixForRaw()
    {
        $options = ['url_suffix' => self::URL_SUFFIX, 'private_cdn' => true, 'resource_type' => 'raw'];

        self::assertMediaFromParamsUrl(
            self::ASSET_ID,
            $options,
            [
                'hostname'   => self::PRIVATE_CDN_HOSTNAME,
                'cloud_name' => '',
                'full_path'  => 'files/' . self::URL_SUFFIXED_ASSET_ID . '',
            ]
        );
    }

    /**
     * Should support url_suffix for video uploads
     */
    public function testUrlSuffixForVideos()
    {
        $options = ['url_suffix' => self::URL_SUFFIX, 'private_cdn' => true, 'resource_type' => 'video'];

        self::assertMediaFromParamsUrl(
            self::VIDEO_NAME,
            $options,
            [
                'hostname'   => self::PRIVATE_CDN_HOSTNAME,
                'cloud_name' => '',
                'full_path'  => 'videos/' . self::URL_SUFFIXED_ASSET_ID . '.' . self::VID_EXT,
            ]
        );
    }

    /**
     * Should support url_suffix for private images
     */
    public function testUrlSuffixForPrivate()
    {
        $options = [
            'url_suffix'    => self::URL_SUFFIX,
            'private_cdn'   => true,
            'resource_type' => 'image',
            'type'          => 'private',
        ];

        self::assertMediaFromParamsUrl(
            self::IMAGE_NAME,
            $options,
            [
                'hostname'   => self::PRIVATE_CDN_HOSTNAME,
                'cloud_name' => '',
                'full_path'  => 'private_images/' . self::URL_SUFFIXED_IMAGE_NAME,
            ]
        );

        $options = [
            'url_suffix'    => self::URL_SUFFIX,
            'private_cdn'   => true,
            'format'        => self::IMG_EXT_JPG,
            'resource_type' => 'image',
            'type'          => 'private',
        ];

        self::assertMediaFromParamsUrl(
            self::IMAGE_NAME,
            $options,
            [
                'hostname'   => self::PRIVATE_CDN_HOSTNAME,
                'cloud_name' => '',
                'full_path'  => 'private_images/' . self::URL_SUFFIXED_ASSET_ID . '.' . self::IMG_EXT_JPG,
            ]
        );
    }

    /**
     * Should support url_suffix for authenticated images
     */
    public function testUrlSuffixForAuthenticated()
    {
        $options = [
            'url_suffix'    => self::URL_SUFFIX,
            'private_cdn'   => true,
            'resource_type' => 'image',
            'type'          => 'authenticated',
        ];

        self::assertMediaFromParamsUrl(
            self::IMAGE_NAME,
            $options,
            [
                'hostname'   => self::PRIVATE_CDN_HOSTNAME,
                'cloud_name' => '',
                'full_path'  => 'authenticated_images/' . self::URL_SUFFIXED_IMAGE_NAME,
            ]
        );
    }
}
