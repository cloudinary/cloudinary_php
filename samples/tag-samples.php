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

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/SamplePage/SamplePage.php';
require_once __DIR__ . '/SamplePage/Sample/TagSample.php';

use Cloudinary\Asset\Video;
use Cloudinary\ClassUtils;
use Cloudinary\Configuration\Configuration;
use Cloudinary\Tag\BaseImageTag;
use Cloudinary\Tag\ImageTag;
use Cloudinary\Tag\PictureTag;
use Cloudinary\Tag\UploadTag;
use Cloudinary\Tag\VideoTag;
use Cloudinary\Transformation\Effect;
use Cloudinary\Transformation\Resize;

Configuration::instance(
    ['cloud' => ['cloud_name' => 'demo', 'api_key' => 'test_key', 'api_secret' => 'test_secret']]
);

const FETCH_IMAGE_URL
= 'http://upload.wikimedia.org/wikipedia/' .
  'commons/4/46/Jennifer_Lawrence_at_the_83rd_Academy_Awards.jpg';

$testImage = 'sample';

$imageTagGroup = [
    'name'      => 'Image Tag', //group name
    'iconClass' => 'fas fa-picture-o',
    'items'     => [
        [
            'name'  => 'Upload',
            'items' => [
                [
                    new ImageTag($testImage),
                    'new ImageTag(\'$testImage\')',
                ],
            ],
        ],
        [
            'name'  => 'Fetch',
            'items' => [
                [
                    ImageTag::fetch(FETCH_IMAGE_URL),
                    'ImageTag::fetch(\'' . FETCH_IMAGE_URL . '\')',
                ],
            ],
        ],
        [
            'name'  => 'Transformation',
            'items' => [
                [
                    (new ImageTag('sample'))->resize(Resize::scale(500))->rotate(17)->effect(Effect::sepia()),
                    '(new ImageTag(\'sample\'))->resize(Resize::scale(500))->rotate(17)->effect(Effect::sepia())',
                ],
            ],
        ],
        [
            'name'  => 'SrcSet',
            'items' => [
                [
                    (new ImageTag('sample'))->breakpoints([50, 500, 800]),
                    '(new ImageTag(\'sample\'))->breakpoints([50, 500, 800])',
                ],
            ],
        ],
        [
            'name'  => 'SrcSet Optimal Breakpoints',
            'items' => [
                [
                    (new ImageTag('sample'))->autoOptimalBreakpoints(),
                    '(new ImageTag(\'sample\'))->autoOptimalBreakpoints()',
                ],
            ],
        ],
        [
            'name'  => 'Picture Tag',
            'items' => [
                [
                    new PictureTag(
                        'sample',
                        [
                            ['max_width' => 360, 'transformation' => 'ar_9:16,c_fill,g_auto,w_360'],
                            ['min_width' => 360, 'max_width' => 800, 'transformation' => 'ar_1,c_fill,g_auto,w_800'],
                            ['min_width' => 800, 'transformation' => 'ar_16:9,c_fill,g_auto'],
                        ]
                    ),
                    'new PictureTag(
                        \'sample\',
                         [
                            [\'max_width\' => 360, \'transformation\' => \'ar_9:16,c_fill,g_auto,w_360\'],
                            [\'min_width\' => 360, \'max_width\' => 800, 
                            \'transformation\' => \'ar_1,c_fill,g_auto,w_800\'],
                            [\'min_width\' => 800, \'transformation\' => \'ar_16:9,c_fill,g_auto\'],
                        ]
                    )',
                ],
            ],
        ],

    ],
];


$videoTagGroup = [
    'name'      => 'Video Tag', //group name
    'iconClass' => 'fas fa-video-o',
    'items'     => [
        [
            'name'  => 'Video Tag',
            'items' => [
                [
                    new VideoTag('dog'),
                    'new VideoTag(\'dog\')',
                ],
            ],
        ],
        [
            'name'  => 'Transformation',
            'items' => [
                [
                    (new VideoTag((new Video('dog'))->rotate(17)->resize(Resize::scale(500)))),
                    '(new VideoTag((new Video(\'dog\'))->rotate(17)->resize(Resize::scale(500))))',
                ],
            ],
        ],
        [
            'name'  => 'Attributes',
            'items' => [
                [
                    (new VideoTag('dog'))->setAttributes(
                        ['controls', 'loop', 'muted' => 'true', 'preload', 'style' => 'border: 1px']
                    ),
                    '(new VideoTag(\'dog\'))->setAttributes(
                        [\'controls\', \'loop\', \'muted\' => \'true\', \'preload\', \'style\' => \'border: 1px\']
                    )',
                ],
            ],
        ],
    ],
];

$variousTagsGroup = [
    'name'      => 'Various Tags', //group name
    'iconClass' => 'fas fa-video-o',
    'items'     => [
        [
            'name'  => 'Upload Tag',
            'items' => [
                [
                    new UploadTag('image'),
                    'new UploadTag(\'image\')',
                ],
            ],
        ],
        [
            'name'  => 'Unsigned Upload Tag',
            'items' => [
                [
                    UploadTag::unsigned('image', 'testUploadPreset'),
                    'UploadTag::unsigned(\'image\', \'testUploadPreset\')',
                ],
            ],
        ],
    ],
];

/**
 * Converts arrays to TagSample
 *
 * @param $group
 *
 * @return mixed
 */
function createSampleGroup($group)
{
    foreach ($group['items'] as &$subGroup) {
        if (! isset($subGroup['publicId'])) {
            $subGroup['publicId'] = isset($group['publicId']) ? $group['publicId'] : 'sample';
        }

        foreach ($subGroup['items'] as &$sampleArray) {
            $sampleArray[]           = ($subGroup['publicId']);
            $sampleArray             = ClassUtils::verifyVarArgsInstance($sampleArray, TagSample::class);
            $sampleArray->keepSpaces = false;
        }
    }

    return $group;
}

$page = new SamplePage(
    'Basic PHP SDK v2 Tag Samples | Cloudinary',
    'Cloudinary - Basic PHP SDK v2 Tag Samples'
);

$page->addGroup(createSampleGroup($imageTagGroup));
$page->addGroup(createSampleGroup($videoTagGroup));
$page->addGroup(createSampleGroup($variousTagsGroup));
$page->currentNavLink = 1;

echo $page;
