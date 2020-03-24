<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Samples;

use Cloudinary\Asset\Image;
use Cloudinary\Asset\Video;
use Cloudinary\StringUtils;
use Cloudinary\Tag\ImageTag;
use Cloudinary\Tag\VideoTag;
use Cloudinary\Transformation\Transformation;

require_once __DIR__ . '/Sample.php';
require_once __DIR__ . '/Tabs/CodeTab.php';
require_once __DIR__ . '/Tabs/TransformationTab.php';

/**
 * Class TransformationSample
 */
class TransformationSample extends Sample
{
    /**
     * @var Transformation
     */
    private $transformation;
    /**
     * @var string
     */
    private $publicId;
    /**
     * @var string
     */
    private $type;

    /**
     * TransformationSample constructor.
     * @param string $name
     * @param $transformation
     * @param $code
     * @param $publicId
     * @param string $type
     */
    public function __construct($transformation, $code, $publicId = 'sample', $name = 'sample', $type = 'image')
    {
        parent::__construct($name, $code);
        $this->transformation = $transformation;
        $this->publicId       = $publicId;
        $this->type           = $type;
    }

    /**
     * @return array
     */
    protected function createTabs()
    {
        return [
            $this->createCodeTab(),
            new TransformationTab(
                $this->transformation,
                $this->code,
                $this->publicId
            )
        ];
    }

    /**
     * @return string
     */
    protected function getExample()
    {
        $publicId       = $this->publicId;
        $transformation = $this->transformation;

        if (StringUtils::endsWith($publicId, '.mp4')) {
            return $this->createVideoTag(Video::upload($publicId), $transformation);
        }
        return $this->createImageTag(Image::upload($publicId), $transformation);
    }

    /**
     * @param Image $image
     * @param $transformation
     * @return string
     */
    private function createImageTag($image, $transformation)
    {
        $url = $image->toUrl($transformation);
        $tag = (new ImageTag($image, null, $transformation));
        return $this->getExampleHtml($url, $tag);
    }

    /**
     * @param Video $video
     * @param $transformation
     * @return string
     */
    private function createVideoTag($video, $transformation)
    {
        $url = $video->toUrl($transformation);
        $tag = (new VideoTag($video->setTransformation($transformation)))->setAttribute('controls');
        return $this->getExampleHtml($url, $tag);
    }
}
