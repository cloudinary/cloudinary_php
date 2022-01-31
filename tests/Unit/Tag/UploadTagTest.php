<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Test\Unit\Tag;

use Cloudinary\Configuration\Configuration;
use Cloudinary\Tag\UploadTag;

/**
 * Class UploadTagTest
 */
final class UploadTagTest extends TagTestCase
{

    /**
     * @var Configuration
     */
    private $configuration;

    public function setUp()
    {
        parent::setUp();
        $this->configuration = new Configuration();
    }

    public function testUploadTagImage()
    {
        $tag = new UploadTag('image', $this->configuration);

        $pattern = '/<input class="cloudinary-fileupload" ' .
            'name="file" type="file" ' .
            'data-cloudinary-field="image" ' .
            'data-max-chunk-size="20000000" ' .
            'data-form-data="{\&quot;timestamp\&quot;:\d+,\&quot;signature\&quot;:\&quot;\w+\&quot;,' .
            '\&quot;api_key\&quot;:\&quot;\w+\&quot;}" ' .
            'data-url="http[^\']+\/v1_1\/test123\/auto\/upload"' .
            '>/';
        self::assertRegExp($pattern, (string)$tag);
    }


    public function testUploadTagChunkSize()
    {
        $this->configuration->api->chunkSize = 5000000;
        $tag                                 = new UploadTag('image', $this->configuration);

        $pattern = '/<input class="cloudinary-fileupload" ' .
            'name="file" type="file" ' .
            'data-cloudinary-field="image" ' .
            'data-max-chunk-size="5000000" ' .
            'data-form-data="{\&quot;timestamp\&quot;:\d+,\&quot;signature\&quot;:\&quot;\w+\&quot;,' .
            '\&quot;api_key\&quot;:\&quot;\w+\&quot;}" ' .
            'data-url="http[^\']+\/v1_1\/test123\/auto\/upload"' .
            '>/';

        self::assertRegExp(
            $pattern,
            (string)$tag
        );
    }

    public function testUploadTagClass()
    {
        $tag = new UploadTag('image', $this->configuration);

        $tag->addClass('classy');

        $pattern = '/<input class="cloudinary-fileupload classy" ' .
            'name="file" type="file" ' .
            'data-cloudinary-field="image" ' .
            'data-max-chunk-size="20000000" ' .
            'data-form-data="{\&quot;timestamp\&quot;:\d+,\&quot;signature\&quot;:\&quot;\w+\&quot;,' .
            '\&quot;api_key\&quot;:\&quot;\w+\&quot;}" ' .
            'data-url="http[^\']+\/v1_1\/test123\/auto\/upload"' .
            '>/';
        self::assertRegExp(
            $pattern,
            (string)$tag
        );
    }

    public function testUnsignedUploadTag()
    {
        $tag = UploadTag::unsigned('image', 'testUploadPreset', $this->configuration);

        $pattern = '/<input class="cloudinary-fileupload" ' .
            'name="file" type="file" ' .
            'data-cloudinary-field="image" ' .
            'data-max-chunk-size="20000000" ' .
            'data-form-data="{\&quot;upload_preset\&quot;:\&quot;\w+\&quot;,' .
            '\&quot;timestamp\&quot;:\d+}" ' .
            'data-url="http[^\']+\/v1_1\/test123\/auto\/upload"' .
            '>/';
        self::assertRegExp(
            $pattern,
            (string)$tag
        );
    }
}
