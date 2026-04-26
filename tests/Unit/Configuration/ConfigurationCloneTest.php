<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Test\Unit\Configuration;

use Cloudinary\Asset\Image;
use Cloudinary\Configuration\Configuration;
use Cloudinary\Tag\ImageTag;
use Cloudinary\Test\Unit\UnitTestCase;

/**
 * Verifies that cloning a Configuration (and the clone fast-paths in BaseAsset / BaseTag /
 * fromParams) produces a fully independent copy.
 *
 * If any of these tests fail, mutations on a per-asset / per-tag working copy can leak back
 * into the global Configuration::instance() singleton.
 */
class ConfigurationCloneTest extends UnitTestCase
{
    public function testCloneCopiesEverySectionAsDistinctObject()
    {
        $source = Configuration::instance();
        $clone  = clone $source;

        self::assertNotSame($source, $clone);
        self::assertNotSame($source->cloud, $clone->cloud);
        self::assertNotSame($source->api, $clone->api);
        self::assertNotSame($source->url, $clone->url);
        self::assertNotSame($source->tag, $clone->tag);
        self::assertNotSame($source->responsiveBreakpoints, $clone->responsiveBreakpoints);
        self::assertNotSame($source->authToken, $clone->authToken);
        self::assertNotSame($source->logging, $clone->logging);
    }

    public function testCloneMutationsDoNotLeakIntoSource()
    {
        $source = Configuration::instance();
        $clone  = clone $source;

        $originals = [
            'cloudName'       => $source->cloud->cloudName,
            'secure'          => $source->url->secure,
            'cname'           => $source->url->cname,
            'sortAttributes'  => $source->tag->sortAttributes,
            'authTokenKey'    => $source->authToken->key,
            'loggingLevel'    => $source->logging->level,
            'apiProxy'        => $source->api->apiProxy,
            'breakpoints'     => $source->responsiveBreakpoints->breakpoints,
        ];

        $clone->cloud->cloudName              = 'mutated_cloud';
        $clone->url->secure                   = ! (bool) $originals['secure'];
        $clone->url->cname                    = 'clone.example.com';
        $clone->tag->sortAttributes           = ! (bool) $originals['sortAttributes'];
        $clone->authToken->key                = '00112233445566778899aabbccddeeff';
        $clone->logging->level                = 'critical';
        $clone->api->apiProxy                 = 'http://proxy.example.com';
        $clone->responsiveBreakpoints->breakpoints = [100, 200, 300];

        self::assertEquals($originals['cloudName'], $source->cloud->cloudName);
        self::assertEquals($originals['secure'], $source->url->secure);
        self::assertEquals($originals['cname'], $source->url->cname);
        self::assertEquals($originals['sortAttributes'], $source->tag->sortAttributes);
        self::assertEquals($originals['authTokenKey'], $source->authToken->key);
        self::assertEquals($originals['loggingLevel'], $source->logging->level);
        self::assertEquals($originals['apiProxy'], $source->api->apiProxy);
        self::assertEquals($originals['breakpoints'], $source->responsiveBreakpoints->breakpoints);
    }

    public function testAssetConstructorClonesGlobalConfig()
    {
        $global = Configuration::instance();
        $image  = new Image('sample.png');

        self::assertNotSame($global->cloud, $image->cloud);
        self::assertNotSame($global->url, $image->urlConfig);
        self::assertNotSame($global->logging, $image->logging);

        $originalCloudName = $global->cloud->cloudName;
        $originalSecure    = $global->url->secure;
        $originalLevel     = $global->logging->level;

        $image->cloud->cloudName = 'mutated_cloud';
        $image->urlConfig->secure = ! (bool) $originalSecure;
        $image->logging->level    = 'critical';

        self::assertEquals($originalCloudName, $global->cloud->cloudName);
        self::assertEquals($originalSecure, $global->url->secure);
        self::assertEquals($originalLevel, $global->logging->level);
    }

    public function testAssetFromParamsDoesNotMutateGlobalConfig()
    {
        $global            = Configuration::instance();
        $originalCloudName = $global->cloud->cloudName;
        $originalSecure    = $global->url->secure;

        $image = Image::fromParams('sample.png', [
            'cloud_name' => 'from_params_cloud',
            'secure'     => true,
        ]);

        self::assertEquals('from_params_cloud', $image->cloud->cloudName);
        self::assertEquals($originalCloudName, $global->cloud->cloudName);
        self::assertEquals($originalSecure, $global->url->secure);
    }

    public function testTagConstructorClonesGivenConfiguration()
    {
        $source = new Configuration(Configuration::instance());
        $tag    = new ImageTag('sample.jpg', $source);

        self::assertNotSame($source, $tag->config);
        self::assertNotSame($source->cloud, $tag->config->cloud);
        self::assertNotSame($source->tag, $tag->config->tag);

        $originalCloudName     = $source->cloud->cloudName;
        $originalSortAttribute = $source->tag->sortAttributes;

        $tag->config->cloud->cloudName       = 'tag_mutated_cloud';
        $tag->config->tag->sortAttributes    = ! (bool) $originalSortAttribute;

        self::assertEquals($originalCloudName, $source->cloud->cloudName);
        self::assertEquals($originalSortAttribute, $source->tag->sortAttributes);
    }

    public function testTagFromParamsDoesNotMutateGlobalConfig()
    {
        $global            = Configuration::instance();
        $originalCloudName = $global->cloud->cloudName;
        $originalQuotes    = $global->tag->quotesType;

        ImageTag::fromParams('sample.jpg', [
            'cloud_name' => 'tag_from_params_cloud',
        ]);

        self::assertEquals($originalCloudName, $global->cloud->cloudName);
        self::assertEquals($originalQuotes, $global->tag->quotesType);
    }
}
