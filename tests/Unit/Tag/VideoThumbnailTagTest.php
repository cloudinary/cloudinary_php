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

use Cloudinary\Asset\Video;
use Cloudinary\Tag\VideoThumbnailTag;
use Cloudinary\Transformation\Effect;
use Cloudinary\Transformation\Parameter\VideoRange\VideoRange;

/**
 * Class VideoThumbnailTagTest
 */
final class VideoThumbnailTagTest extends ImageTagTestCase
{
    const TAG_END = self::ASSET_ID . '.jpg">';

    public function testSimpleVideoThumbnailTag()
    {
        $tag = new VideoThumbnailTag(self::VIDEO_NAME);

        $this->assertEquals(
            '<img src="https://res.cloudinary.com/test123/video/upload/'.self::TAG_END,
            (string)$tag
        );
    }

    public function testVideoThumbnailTagWithTransformation()
    {
        $video = (new Video(self::VIDEO_NAME))->trim(VideoRange::range(10, 20));
        $tag = (new VideoThumbnailTag($video))->effect(Effect::sepia(20));

        $this->assertEquals(
            '<img src="https://res.cloudinary.com/test123/video/upload/so_10,eo_20/e_sepia:20/'.self::TAG_END,
            (string)$tag
        );
    }
}
