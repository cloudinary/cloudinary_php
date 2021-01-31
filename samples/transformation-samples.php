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
require_once __DIR__ . '/SamplePage/Sample/TransformationSample.php';

use Cloudinary\ClassUtils;
use Cloudinary\Configuration\Configuration;
use Cloudinary\Transformation\Adjust;
use Cloudinary\Transformation\Argument\Color;
use Cloudinary\Transformation\Argument\GradientDirection;
use Cloudinary\Transformation\Argument\Text\FontFamily;
use Cloudinary\Transformation\Argument\Text\FontStyle;
use Cloudinary\Transformation\Argument\Text\FontWeight;
use Cloudinary\Transformation\Argument\Text\TextDecoration;
use Cloudinary\Transformation\AudioCodec;
use Cloudinary\Transformation\AudioFrequency;
use Cloudinary\Transformation\AutoBackground;
use Cloudinary\Transformation\Background;
use Cloudinary\Transformation\ChromaSubSampling;
use Cloudinary\Transformation\Codec\VideoCodecLevel;
use Cloudinary\Transformation\Codec\VideoCodecProfile;
use Cloudinary\Transformation\CompassGravity;
use Cloudinary\Transformation\Crop;
use Cloudinary\Transformation\Effect;
use Cloudinary\Transformation\Expression\PVar;
use Cloudinary\Transformation\Fill;
use Cloudinary\Transformation\FocalGravity;
use Cloudinary\Transformation\Format;
use Cloudinary\Transformation\Gravity;
use Cloudinary\Transformation\OutlineMode;
use Cloudinary\Transformation\Pad;
use Cloudinary\Transformation\Position;
use Cloudinary\Transformation\Qualifier;
use Cloudinary\Transformation\Quality;
use Cloudinary\Transformation\Reshape;
use Cloudinary\Transformation\RoundCorners;
use Cloudinary\Transformation\Scale;
use Cloudinary\Transformation\Source;
use Cloudinary\Transformation\Transformation;
use Cloudinary\Transformation\VideoCodec;
use Cloudinary\Transformation\VideoEdit;

Configuration::instance(['cloud' => ['cloud_name' => 'demo']]);

$imageGroup = [
    'name'      => 'Image', //group name
    'iconClass' => 'fas fa-camera',
    'items'     => [
        [
            'name'  => 'Scale', //subGroup name
            'items' => [
                [
                    (new Transformation())->resize(Scale::scale(300)),
                    '(new Transformation())->resize(Scale::scale(300))',
                ],
                [
                    (new Transformation())->resize(Scale::scale()->height(300)),
                    '(new Transformation())->resize(Scale::scale()->height(300))',
                ],
                [
                    (new Transformation())->resize(Scale::scale(300, 300)),
                    '(new Transformation())->resize(Scale::scale(300, 300))',
                ],
                [
                    (new Transformation())->resize(Scale::scale(300)->height(300)),
                    '(new Transformation())->resize(Scale::scale(300)->height(300))',
                ],
                [
                    (new Transformation())->resize(Scale::scale()->height(300)->aspectRatio(2.111)),
                    '(new Transformation())->resize(Scale::scale()->height(300)->aspectRatio(2.111))',
                ],
                [
                    (new Transformation())->resize(Scale::scale()->height(300)->aspectRatio('19:9')),
                    "(new Transformation())->resize(Scale::scale()->height(300)->aspectRatio('19:9'))",
                ],
                [
                    (new Transformation())->resize(Scale::scale()->height(300)->aspectRatio(19, 9)),
                    '(new Transformation())->resize(Scale::scale()->height(300)->aspectRatio(19, 9))',
                ],
            ],
        ],
        [
            'name'  => 'Round Corners',
            'items' => [
                [
                    (new Transformation())->roundCorners(70),
                    '(new Transformation())->roundCorners(70)',
                ],
                [
                    (new Transformation())->roundCorners(70, 20),
                    '(new Transformation())->roundCorners(70, 20)',
                ],
                [
                    (new Transformation())->roundCorners(70, 20, 100),
                    '(new Transformation())->roundCorners(70, 20, 100)',
                ],
                [
                    (new Transformation())->roundCorners(70, 20, 100, 40),
                    '(new Transformation())->roundCorners(70, 20, 100, 40)',
                ],
                [
                    (new Transformation())->roundCorners(RoundCorners::max()),
                    '(new Transformation())->roundCorners(RoundCorners::max())',
                ],
            ],
        ],
        [
            'name'     => 'Crop',
            'publicId' => 'woman',
            'items'    => [
                [
                    (new Transformation())->resize(Crop::thumbnail(200, 200)),
                    '(new Transformation())->resize(Crop::thumbnail(200, 200))',
                ],
                [
                    (new Transformation())->resize(Crop::thumbnail(200, 200, Gravity::auto())),
                    '(new Transformation())->resize(Crop::thumbnail(200, 200, Gravity::auto()))',
                ],
                [
                    (new Transformation())->resize(Crop::thumbnail(200, 200)->position(10, 10)),
                    '(new Transformation())->resize(Crop::thumbnail(200, 200)->position(10, 10))',
                ],
                [
                    (new Transformation())->resize(
                        Crop::thumbnail(200, 200)->gravity(
                            Gravity::auto(FocalGravity::BODY)
                        )
                    ),
                    '(new Transformation())->resize(Crop::thumbnail(200, 200)->gravity(
    Gravity::auto(FocalGravity::BODY)
))',
                ],
                [
                    (new Transformation())->resize(Crop::thumbnail(200, 200)->gravity(Gravity::north())),
                    '(new Transformation())->resize(Crop::thumbnail(200, 200)->gravity(Gravity::north()))',
                ],
                [
                    (new Transformation())->resize(
                        Crop::thumbnail(200, 200)->gravity(Gravity::face(CompassGravity::center()))
                    ),
                    ' (new Transformation())->resize(
                        Crop::thumbnail(200, 200)->gravity(Gravity::face(CompassGravity::center()))
                    )',
                ],
                [
                    (new Transformation())->resize(Crop::thumbnail(200, 200, Gravity::auto())->zoom(0.7)),
                    '(new Transformation())->resize(Crop::thumbnail(200, 200, Gravity::auto())->zoom(0.7))',
                ],
            ],
        ],
        [
            'name'  => 'Pad',
            'items' => [
                [
                    (new Transformation())->resize(Pad::pad(200, 200)->background(Color::CORAL)),
                    '(new Transformation())->resize(Pad::pad(200, 200)->background(Color::CORAL))',
                ],
                [
                    (new Transformation())->resize(
                        Pad::pad(200, 200)->background(AutoBackground::border()->contrast())
                    ),
                    ' (new Transformation())->resize(
                        Pad::pad(200, 200)->background(AutoBackground::border()->contrast())
                    )',
                ],
                [
                    (new Transformation())->resize(
                        Pad::pad(200, 200)->background(
                            Background::predominantGradient(2, GradientDirection::diagonalDesc())
                        )
                    ),
                    '(new Transformation())->resize(
    Pad::pad(200, 200)->background(
        Background::predominantGradient(2, GradientDirection::diagonalDesc())
    )
)',
                ],
            ],
        ],
        [
            'name'     => 'Crop & Scale',
            'publicId' => 'woman',
            'items'    => [
                [
                    (new Transformation())
                        ->resize(Crop::thumbnail(200, 200, Gravity::auto()))
                        ->resize(Scale::scale(200, 200)),
                    '(new Transformation())
    ->resize(Crop::thumbnail(200, 200, Gravity::auto()))
    ->resize(Scale::scale(200, 200))',
                ],
            ],
        ],
        [
            'name'  => 'Effects & Adjustments',
            'items' => [
                [
                    (new Transformation())->effect(Effect::sepia()),
                    '(new Transformation())->effect(Effect::sepia())',
                ],
                [
                    (new Transformation())->adjust(Adjust::saturation(82)),
                    '(new Transformation())->adjust(Adjust::saturation(82))',
                ],
                [
                    (new Transformation())->adjust(Adjust::tint(100, Color::GREEN, Color::RED)),
                    '(new Transformation())->adjust(Adjust::tint(100, NamedColor::GREEN, NamedColor::RED))',
                ],
                [
                    (new Transformation())->effect(
                        Effect::shadow()->offset(10, PVar::height()->divide()->numeric(50))->color(Color::green())
                    ),
                    '
                    (new Transformation())->effect(
    Effect::shadow()->offset(10, PVar::height()->divide()->numeric(50))->color(Color::green())
)',
                ],
                [
                    (new Transformation())->adjust(Adjust::replaceColor(Color::MAROON, 80, '2b38aa')),
                    '(new Transformation())->adjust(Adjust::replaceColor(NamedColor::MAROON, 80, \'2b38aa\'))',
                ],
                [
                    (new Transformation())->effect(
                        Effect::outline(OutlineMode::OUTER, 15, 200)->color(Color::orange())
                    ),
                    '(new Transformation())->effect(
    Effect::outline(OutlineMode::OUTER, 15, 200)->color(Color::orange())
)',
                ],
                [
                    (new Transformation())->effect(
                        Effect::generic('outline:outer', 15, 200)->addQualifier(Qualifier::generic('co', 'orange'))
                    ),
                    '(new Transformation())->effect(
    Effect::generic(\'outline:outer\', 15, 200)->addParameter(Parameters::generic(\'co\', \'orange\'))
)',
                ],
            ],
        ],
        [
            'name'  => 'Format',
            'items' => [
                [
                    (new Transformation())->format(Format::auto()),
                    '(new Transformation())->format(Format::auto())',

                ],
                [
                    (new Transformation())->format(Format::png()),
                    '(new Transformation())->format(Format::png())',
                ],
            ],
        ],
        [
            'name'  => 'Quality',
            'items' => [
                [
                    (new Transformation())->quality(70),
                    '(new Transformation())->quality(70)',
                ],
                [
                    (new Transformation())->quality(
                        Quality::level(70)->chromaSubSampling(ChromaSubSampling::chroma420())
                    ),
                    ' (new Transformation())->quality(
                        Quality::level(70)->chromaSubSampling(ChromaSubSampling::chroma420())
                    )',
                ],
                [
                    (new Transformation())->quality(Quality::auto()),
                    '(new Transformation())->quality(Quality::auto())',
                ],
                [
                    (new Transformation())->quality(Quality::autoGood()),
                    '(new Transformation())->quality(Quality::good())',
                ],
                [
                    (new Transformation())->quality(Quality::auto('best')),
                    '(new Transformation())->quality(Quality::auto(\'best\'))',
                ],
            ],
        ],
        [
            'name'  => 'Named and Generic',
            'items' => [
                [
                    (new Transformation())->namedTransformation('fit_100x150'),
                    '(new Transformation())->namedTransformation(\'fit_100x150\')',
                ],
                [
                    (new Transformation('t_jpg_with_quality_30')),
                    '(new Transformation(\'t_jpg_with_quality_30\'))',
                ],
                [
                    (new Transformation('t_jpg_with_quality_30'))
                        ->namedTransformation('crop_400x400')
                        ->namedTransformation('fit_100x150')
                        ->resize(Fill::fill()->height(80)),
                    '(new Transformation(\'t_jpg_with_quality_30\'))
    ->namedTransformation(\'crop_400x400\')
    ->namedTransformation(\'fit_100x150\')
    ->resize(Fill::fill()->height(80))',
                ],
            ],
        ],
        [
            'name'  => 'Overlay',
            'items' => [
                [
                    (new Transformation())->overlay('logo'),
                    '(new Transformation())->overlay(\'logo\')',
                ],
                [
                    (new Transformation())->underlay('logo'),
                    '(new Transformation())->underlay(\'logo\')',
                ],
                [
                    (new Transformation())->overlay(Source::image('logo')->resize(Scale::scale(200, 200))),
                    '(new Transformation())->overlay(Source::image(\'logo\')->resize(Scale::scale(200, 200)))',
                ],
                [
                    (new Transformation())->overlay(
                        Source::image('logo')->resize(Fill::fill(200, 200, Gravity::auto())),
                        Position::north(10, 10)
                    ),
                    '(new Transformation())->overlay(
    Source::image(\'logo\')->resize(Fill::fill(200, 200, Gravity::auto())),
    Position::north(10, 10)
)',
                ],
                [
                    (new Transformation())->overlay(
                        Source::text('my_text')->fontFamily(FontFamily::ARIAL)->fontSize(50)
                    ),
                    '(new Transformation())->overlay(
    Source::text(\'my_text\')->fontFamily(FontFamily::ARIAL)->fontSize(50)
)',
                ],
                [
                    (new Transformation())->overlay(
                        Source::text('Flowers')
                              ->fontFamily(FontFamily::VERDANA)
                              ->fontSize(75)
                              ->fontWeight(FontWeight::BOLD)
                              ->fontStyle(FontStyle::ITALIC)
                              ->textDecoration(TextDecoration::UNDERLINE)
                              ->letterSpacing(14)
                    ),
                    '(new Transformation())->overlay(
    Source::text(\'Flowers\')
        ->fontFamily(FontFamily::VERDANA)
        ->fontSize(75)
        ->fontWeight(FontWeight::BOLD)
        ->fontStyle(FontStyle::ITALIC)
        ->textDecoration(TextDecoration::UNDERLINE)
        ->letterSpacing(14)
)',
                ],
                [
                    (new Transformation())->overlay(
                        Source::text('Your Logo Here')->fontFamily(FontFamily::IMPACT)->fontSize(150)
                              ->textColor(Color::WHITE)
                              ->reshape(Reshape::distortArc(-120)),
                        Position::south()->offsetY(140)
                    ),
                    '(new Transformation())->overlay(
    Source::text(\'Your Logo Here\')->fontFamily(FontFamily::IMPACT)->fontSize(150)
        ->textColor(NamedColor::WHITE)
        ->reshape(Reshape::distortArc(-120)),
    Position::south()->offsetY(140)
)',
                ],
                [
                    (new Transformation())->adjust(Adjust::by3dLut('iwltbap_aspen.3dl')),
                    '(new Transformation())->adjust(Adjust::by3dLut(\'iwltbap_aspen.3dl\'))',
                ],
            ],
        ],
        [
            'name'  => 'CustomParameter',
            'items' => [
                [
                    (new Transformation())->addGenericQualifier('w', 500),
                    '(new Transformation())->addGenericParam(\'w\', 500)',
                ],
            ],
        ],
    ],
];

$videoGroup = [
    'name'      => 'Video',
    'iconClass' => 'fas fa-video',
    'publicId'  => 'dog.mp4',
    'items'     => [
        [
            'name'  => 'Trim',
            'items' => [
                [
                    (new Transformation())->trim(VideoEdit::trim(6.5, 10)),
                    '(new Transformation())->trim(VideoEdit::trim(6.5, 10))',
                ],
                [
                    (new Transformation())->trim(VideoEdit::trim('10p')->duration('30p')),
                    '(new Transformation())->trim(VideoEdit::trim(\'10p\')->duration(\'30p\'))',
                ],
            ],
        ],
        [
            'name'  => 'Concatenate',
            'items' => [
                [
                    (new Transformation())
                        ->resize(Fill::fill(300, 200))
                        ->trim(VideoEdit::trim(0, 3))
                        ->concatenate(
                            Source::video('kitten_fighting')
                                  ->trim(VideoEdit::trim(2, 5))
                                  ->resize(Fill::fill(300, 200))
                        ),
                    '(new Transformation())
    ->resize(Fill::fill(300, 200))
    ->trim(VideoRange::range(0, 3))
    ->concatenate(
        Source::video(\'kitten_fighting\')
            ->trim(VideoRange::range(2, 5))
            ->resize(Fill::fill(300, 200))
    )',
                ],
            ],
        ],
        [
            'name'  => 'Other',
            'items' => [
                [
                    (new Transformation())
                        ->transcode(VideoCodec::h264(VideoCodecProfile::BASELINE, VideoCodecLevel::VCL_31)),
                    '(new Transformation())
    ->transcode(VideoCodec::h264(VideoCodecProfile::VCP_BASELINE, VideoCodecLevel::VCL_31))',
                ],
                [
                    (new Transformation())->transcode(AudioFrequency::freq22050()),
                    '(new Transformation())->transcode(AudioFrequency::freq22050())',
                ],
                [
                    (new Transformation())->transcode(AudioCodec::none()),
                    '(new Transformation())->transcode(AudioCodec::none())',
                ],
                [
                    (new Transformation())->addSubtitles('sample_sub_en.srt'),
                    '(new Transformation())->addSubtitles(\'sample_sub_en.srt\')',
                ],
                [
                    (new Transformation())->effect(Effect::vignette(50))->effect(Effect::noise(90)),
                    '(new Transformation())->effect(Effect::vignette(50))->effect(Effect::noise(90))',
                ],
            ],
        ],
    ],
];

/**
 * Converts arrays to TransformationSample
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
            $sampleArray             = ClassUtils::verifyVarArgsInstance($sampleArray, TransformationSample::class);
            $sampleArray->keepSpaces = true;
        }
    }

    return $group;
}

$page = new SamplePage(
    'PHP SDK v2 Transformation Samples',
    'Cloudinary - PHP SDK v2 Transformation Samples'
);
$page->addGroup(createSampleGroup($imageGroup));
$page->addGroup(createSampleGroup($videoGroup));
$page->currentNavLink = 0;
echo $page;
