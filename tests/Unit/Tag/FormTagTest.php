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
use Cloudinary\Tag\FormTag;
use Cloudinary\Test\Unit\Tag\Patterns\FormTagPatterns;

/**
 * Class FormTagTest
 */
final class FormTagTest extends TagTestCase
{
    /**
     * @var Configuration
     */
    private $configuration;

    /**
     * @var array
     */
    private $uploadParams;

    public function setUp()
    {
        parent::setUp();
        $this->configuration = new Configuration();
        $this->uploadParams  = ['public_id' => self::IMAGE_NAME];
    }

    public function testFormTag()
    {
        $tag = new FormTag($this->configuration, $this->uploadParams);
        $tag->addClass('uploader');

        self::assertRegExp(FormTagPatterns::getFormTagPattern(), (string)$tag);
    }
}
