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
use Cloudinary\Transformation\Argument\Text\FontFamily;
use Cloudinary\Transformation\Argument\Text\FontWeight;
use Cloudinary\Transformation\Effect;
use Cloudinary\Transformation\Position;
use Cloudinary\Transformation\Resize;
use Cloudinary\Transformation\Source;
use Cloudinary\Transformation\TextStyle;
use Cloudinary\Transformation\Transformation;

Configuration::instance(['cloud' => ['cloud_name' => 'demo']]);

$sample = [
    'name'      => 'Sample', //group name
    'iconClass' => 'fas fa-camera',
    'items'     => [
        [
            'name'  => 'Your Transformation', //subGroup name
            'items' => [
                [
                    (new Transformation())
                        ->resize(
                            Resize::fill(220, 140)
                        )->overlay(
                            Source::image('brown_sheep')->resize(Resize::fill(220, 140)),
                            Position::center(220)
                        )->overlay(
                            Source::image('horses')->resize(Resize::fill(220, 140)),
                            Position::center(-110, 140)
                        )->overlay(
                            Source::image('white_chicken')->resize(Resize::fill(220, 140)),
                            Position::center(110, 70)
                        )->overlay(
                            Source::image('butterfly')->resize(Resize::scale()->height(200))->rotate(10),
                            Position::center(-10)
                        )->resize(Resize::crop(400, 260))
                        ->roundCorners(20)
                        ->overlay(
                            Source::text('Memories from our trip')
                                  ->textStyle((new TextStyle(FontFamily::PARISIENNE, 35))->fontWeight(FontWeight::BOLD))
                                  ->textColor('#990C47'),
                            Position::center()->offsetY(155)
                        )->effect(Effect::shadow())
                    ,
                    '(new Transformation())
    ->resize(
        Resize::fill(220, 140)
    )->overlay(
        Source::image(\'brown_sheep\')->resize(Resize::fill(220, 140)),
        Position::center(220)
    )->overlay(
        Source::image(\'horses\')->resize(Resize::fill(220, 140)),
        Position::center(-110, 140)
    )->overlay(
        Source::image(\'white_chicken\')->resize(Resize::fill(220, 140)),
        Position::center(110, 70)
    )->overlay(
        Source::image(\'butterfly\')->resize(Resize::scale()->height(200))->rotate(10),
        Position::center(-10)
    )->resize(Resize::crop(400, 260))
    ->roundCorners(20)
    ->overlay(
        Source::text(\'Memories from our trip\')
                  ->style((new TextStyle(FontFamily::PARISIENNE, 35))->fontWeight(FontWeight::BOLD))
                  ->textColor(\'#990C47\'),
        Position::center()->offsetY(155)
    )->effect(Effect::shadow())',
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
            $subGroup['publicId'] = isset($group['publicId']) ? $group['publicId'] : 'yellow_tulip';
        }

        foreach ($subGroup['items'] as &$sampleArray) {
            $sampleArray[] = ($subGroup['publicId']);
            $sampleArray   = ClassUtils::verifyVarArgsInstance($sampleArray, TransformationSample::class);
        }
    }

    return $group;
}

$page = new SamplePage(
    'PHP SDK v2 Transformation Sample',
    'Cloudinary - PHP SDK v2 Transformation Samples'
);

$page->addGroup(createSampleGroup($sample));
$page->currentNavLink = 2;

echo $page;
