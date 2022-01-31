<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Test\Unit\Transformation\Common;

use Cloudinary\Test\Unit\Asset\AssetTestCase;
use Cloudinary\Transformation\LayerQualifierFactory;
use Cloudinary\Transformation\QualifiersAction;
use InvalidArgumentException;

/**
 * Class ActionFromQualifiersTest
 */
final class ActionFromQualifiersTest extends AssetTestCase
{
    const CUSTOM_FUNCTION_WASM_STR     = 'wasm:blur.wasm';
    const CUSTOM_FUNCTION_WASM_ARRAY   = ['function_type' => 'wasm', 'source' => 'blur.wasm'];
    const CUSTOM_FUNCTION_REMOTE_ARRAY = [
        'function_type' => 'remote',
        'source'        => 'https://df34ra4a.execute-api.us-west-2.amazonaws.com/default/cloudinaryFn',
    ];
    const CUSTOM_FUNCTION_REMOTE_STR   =
        'remote:aHR0cHM6Ly9kZjM0cmE0YS5leGVjdXRlLWFwaS51cy13ZXN0LTIuYW1hem9uYXdzLmNvbS9kZWZhdWx0L2Nsb3VkaW5hcnlGbg==';

    /**
     * Custom function data provider.
     *
     * @return array[]
     */
    public function customFunctionDataProvider()
    {
        return [
            'Should support custom function from string'                       => [
                'fn_' . self::CUSTOM_FUNCTION_WASM_STR,
                ['custom_function' => self::CUSTOM_FUNCTION_WASM_STR]
            ],
            'Should support custom function from array'                        => [
                'fn_' . self::CUSTOM_FUNCTION_WASM_STR,
                ['custom_function' => self::CUSTOM_FUNCTION_WASM_ARRAY]
            ],
            'Should encode custom function source for remote function'         => [
                'fn_' . self::CUSTOM_FUNCTION_REMOTE_STR,
                ['custom_function' => self::CUSTOM_FUNCTION_REMOTE_ARRAY]
            ],
            'Should support custom pre function from string'                   => [
                'fn_pre:' . self::CUSTOM_FUNCTION_WASM_STR,
                ['custom_pre_function' => self::CUSTOM_FUNCTION_WASM_STR]
            ],
            'Should support custom pre function from array'                    => [
                'fn_pre:' . self::CUSTOM_FUNCTION_WASM_STR,
                ['custom_pre_function' => self::CUSTOM_FUNCTION_WASM_ARRAY]
            ],
            'Should encode custom pre function source for remote pre function' => [
                'fn_pre:' . self::CUSTOM_FUNCTION_REMOTE_STR,
                ['custom_pre_function' => self::CUSTOM_FUNCTION_REMOTE_ARRAY]
            ],
        ];
    }

    /**
     * Border data provider.
     *
     * @return array[]
     */
    public function borderDataProvider()
    {
        return [
            'Border as a string'                    => ['bo_1px_solid_blue', ['border' => '1px_solid_blue']],
            'Border as an array'                    => ['bo_5px_solid_black', ['border' => ['width' => 5]]],
            'Border as an array with few options'   => [
                'bo_5px_solid_rgb:ffaabbdd',
                ['border' => ['width' => 5, 'color' => '#ffaabbdd']]
            ],
        ];
    }

    /**
     * Flags data provider.
     *
     * @return array[]
     */
    public function flagsDataProvider()
    {
        return [
            'Should support flags as a string'                => [
                'fl_abc',
                ['flags' => 'abc'],
            ],
            'Should support flags as a string with an option' => [
                'fl_attachment:pretty_flower',
                ['flags' => 'attachment:pretty_flower'],
            ],
            'Should support flags as an array'                => [
                'fl_abc.def',
                ['flags' => ['abc', 'def']],
            ],
        ];
    }

    /**
     * Effect data provider.
     *
     * @return array[]
     */
    public function effectDataProvider()
    {
        return [
            'Should support an effect as a string'                 => [
                'e_sepia',
                ['effect' => 'sepia'],
            ],
            'Should support an effect as an array'                 => [
                'e_sepia:-10',
                ['effect' => ['sepia', -10]],
            ],
            'Should support an effect as a string with an option'  => [
                'e_art:incognito',
                ['effect' => 'art:incognito'],
            ],
            'Should not normalize value named "duration" as "du_"' => [
                'e_preview:duration_2',
                ['effect' => 'preview:duration_2'],
            ],
        ];
    }

    /**
     * Keyframe interval data provider.
     *
     * @return array[]
     */
    public function keyframeIntervalDataProvider()
    {
        return [
            'Keyframe interval as a positive integer'          => ['ki_10.0', ['keyframe_interval' => 10]],
            'Keyframe interval as a big positive integer'      => ['ki_300.0', ['keyframe_interval' => 300]],
            'Keyframe interval as a float with a zero integer' => ['ki_0.05', ['keyframe_interval' => 0.05]],
            'Keyframe interval as a float'                     => ['ki_3.45', ['keyframe_interval' => 3.45]],
            'Keyframe interval as a string'                    => ['ki_10', ['keyframe_interval' => '10']],
            'Keyframe interval as a user variable'             => ['ki_$ki', ['keyframe_interval' => '$ki']],
        ];
    }

    /**
     * Audio data provider.
     *
     * @return array[]
     */
    public function audioDataProvider()
    {
        return [
            'Audio codec as a string value' => ['ac_acc', ['audio_codec' => 'acc']],
            'Bit Rate as integer'           => ['br_2048', ['bit_rate' => 2048]],
            'Bit Rate as a million'         => ['br_1m', ['bit_rate' => '1m']],
            'Audio frequency as an integer' => ['af_44100', ['audio_frequency' => 44100]],
        ];
    }

    /**
     * Video sampling data provider.
     *
     * @return array[]
     */
    public function videoSamplingDataProvider()
    {
        return [
            'Video sampling as an integer' => ['vs_20', ['video_sampling' => 20]],
            'Video sampling as a float'    => ['vs_2.3s', ['video_sampling' => '2.3s']],
        ];
    }

    /**
     * Start offset data provider.
     *
     * @return array[]
     */
    public function startOffsetDataProvider()
    {
        return [
            'Start offset as a float'                             => ['so_2.63', ['start_offset' => 2.63]],
            'Start offset as a string'                            => ['so_2.64', ['start_offset' => '2.64']],
            'Start offset as a string with a percent as a letter' => ['so_35p', ['start_offset' => '35p']],
            'Start offset as a string with a % sign'              => ['so_36p', ['start_offset' => '36%']],
            'Start offset as auto'                                => ['so_auto', ['start_offset' => 'auto']],
        ];
    }

    /**
     * End offset data provider.
     *
     * @return array[]
     */
    public function endOffsetDataProvider()
    {
        return [
            'End offset as a float'                             => ['eo_2.63', ['end_offset' => 2.63]],
            'End offset as a string'                            => ['eo_2.64', ['end_offset' => '2.64']],
            'End offset as a string with a percent as a letter' => ['eo_35p', ['end_offset' => '35p']],
            'End offset as a string with a % sign'              => ['eo_36p', ['end_offset' => '36%']],
        ];
    }

    /**
     * Duration data provider.
     *
     * @return array[]
     */
    public function durationParameterDataProvider()
    {
        return [
            'Duration parameter as a float'                             => ['du_2.63', ['duration' => 2.63]],
            'Duration parameter as a string'                            => ['du_2.64', ['duration' => '2.64']],
            'Duration parameter as a string with a percent as a letter' => ['du_35p', ['duration' => '35p']],
            'Duration parameter as a string with a % sign'              => ['du_36p', ['duration' => '36%']],
        ];
    }

    /**
     * Offset data provider.
     *
     * @return array[]
     */
    public function offsetDataProvider()
    {
        return [
            'Offset as a string separated by two points'                    => [
                'eo_3.21,so_2.66',
                ['offset' => '2.66..3.21']
            ],
            'Offset as an array of floats'                                  => [
                'eo_3.22,so_2.67',
                ['offset' => [2.67, 3.22]]
            ],
            'Offset as an array of strings with a % sign'                   => [
                'eo_70p,so_35p',
                ['offset' => ['35%', '70%']]
            ],
            'Offset as an array of strings and a percent as a letter'       => [
                'eo_71p,so_36p',
                ['offset' => ['36p', '71p']]
            ],
            'Offset as an array of float strings and a percent as a letter' => [
                'eo_70.5p,so_35.5p',
                ['offset' => ['35.5p', '70.5p']]
            ],
        ];
    }

    /**
     * Operators data provider.
     *
     * @return array[]
     */
    public function operatorsDataProvider()
    {
        return [
            'Should support and translate operators: ^, * and variables: initial_width, initial_height, crop' => [
                'c_scale,h_ih_mul_2,w_iw_pow_2',
                [
                    'transformation' => [
                        ['width' => 'initial_width ^ 2', 'height' => 'initial_height * 2', 'crop' => 'scale'],
                    ],
                ]
            ],
            'Should support and translate operator > and variables: duration, width, crop'                    => [
                'if_du_gt_30,c_scale,w_100',
                ['if' => 'duration > 30', 'width' => '100', 'crop' => 'scale']
            ],
            'Should support and translate operator > and variables: initial_duration, width, crop'            => [
                'if_idu_gt_30,c_scale,w_100',
                ['if' => 'initial_duration > 30', 'width' => '100', 'crop' => 'scale']
            ],
            'Should support and translate operators: <, > and variables: effect, aspect_ratio'                => [
                'if_ar_gt_0.3_and_ar_lt_0.5,e_grayscale',
                ['if' => 'aspect_ratio > 0.3 && aspect_ratio < 0.5', 'effect' => 'grayscale']
            ],
            'Should support and translate operators: =, !=, <, >, <=, >=, &&, ||'
            . ' and variables: width, height, pages, faces, aspect_ratio'                                     => [
                'if_w_eq_0_and_h_ne_0_or_ar_lt_0_and_pc_gt_0_and_fc_lte_0_and_w_gte_0,e_grayscale',
                [
                    'if'     => 'width = 0 && height != 0 || aspect_ratio < 0 && page_count > 0 and face_count <= 0 ' .
                                'and width >= 0',
                    'effect' => 'grayscale',
                ],
            ],
        ];
    }

    /**
     * User variable data provider.
     *
     * @return array[]
     */
    public function userVariableDataProvider()
    {
        return [
            'Normalize expression should not convert user variables' => [
                '$width_10/w_$width_add_10_add_w',
                [
                    'transformation' => [
                        ['$width' => 10],
                        ['width' => '$width + 10 + width'],
                    ],
                ]
            ],
            'Array should define set of variables'                   => [
                'if_fc_gt_2,$z_5,$foo_$z_mul_2,c_scale,w_$foo_mul_200',
                [
                    'if'        => 'face_count > 2',
                    'crop'      => 'scale',
                    'width'     => '$foo * 200',
                    'variables' => [
                        '$z'   => 5,
                        '$foo' => '$z * 2',
                    ],
                ]
            ],
            'Key should define variable'                             => [
                '$foo_10/if_fc_gt_2/c_scale,w_$foo_mul_200_div_fc/if_end',
                [
                    'transformation' => [
                        ['$foo' => 10],
                        ['if' => 'face_count > 2'],
                        ['crop' => 'scale', 'width' => '$foo * 200 / face_count'],
                        ['if' => 'end'],
                    ],
                ]
            ],
            'Should sort defined variable'                           => [
                '$first_2,$second_1',
                [
                    '$second' => 1,
                    '$first'  => 2,
                ]
            ],
            'Should place defined variables before ordered'          => [
                '$first_2,$second_1,$z_5,$foo_$z_mul_2',
                [
                    'variables' => [
                        '$z'   => 5,
                        '$foo' => '$z * 2',
                    ],
                    '$second'   => 1,
                    '$first'    => 2,
                ]
            ],
            'Should support text values'                             => [
                '$efname_!blur!,e_$efname:100',
                [
                    'effect'  => '$efname:100',
                    '$efname' => '!blur!',
                ]
            ],
        ];
    }

    /**
     * Gravity data provider.
     *
     * @return array[]
     */
    public function gravityDataProvider()
    {
        return [
            'Should support gravity with x, y, radius, quality, prefix and opacity parameters' => [
                'g_center,o_20,p_a,q_0.4,r_3,x_1,y_2',
                [
                    'x'       => 1,
                    'y'       => 2,
                    'radius'  => 3,
                    'gravity' => 'center',
                    'quality' => 0.4,
                    'prefix'  => 'a',
                    'opacity' => 20,
                ]
            ],
            'Should support gravity with crop and width parameters'                            => [
                'c_crop,g_auto,w_0.5',
                ['gravity' => 'auto', 'crop' => 'crop', 'width' => 0.5]
            ],
            'Should support auto and ocr_text gravity with crop and width parameters'          => [
                'c_crop,g_auto:ocr_text,w_0.5',
                ['gravity' => 'auto:ocr_text', 'crop' => 'crop', 'width' => 0.5]
            ],
            'Should support ocr_text gravity with crop and width parameters'                   => [
                'c_crop,g_ocr_text,w_0.5',
                ['gravity' => 'ocr_text', 'crop' => 'crop', 'width' => 0.5]
            ],
        ];
    }

    /**
     * Quality data provider.
     *
     * @return array[]
     */
    public function qualityDataProvider()
    {
        return [
            'Quality as an integer'     => [
                'g_center,p_a,q_80,r_3,x_1,y_2',
                ['x' => 1, 'y' => 2, 'radius' => 3, 'gravity' => 'center', 'quality' => 80, 'prefix' => 'a']
            ],
            'Quality as a multi string' => [
                'g_center,p_a,q_80:444,r_3,x_1,y_2',
                ['x' => 1, 'y' => 2, 'radius' => 3, 'gravity' => 'center', 'quality' => '80:444', 'prefix' => 'a']
            ],
            'Quality as auto'           => [
                'g_center,p_a,q_auto,r_3,x_1,y_2',
                ['x' => 1, 'y' => 2, 'radius' => 3, 'gravity' => 'center', 'quality' => 'auto', 'prefix' => 'a']
            ],
            'Quality as auto:good'      => [
                'g_center,p_a,q_auto:good,r_3,x_1,y_2',
                ['x' => 1, 'y' => 2, 'radius' => 3, 'gravity' => 'center', 'quality' => 'auto:good', 'prefix' => 'a']
            ],
        ];
    }

    /**
     * Radius data provider.
     *
     * @return array[]
     */
    public function radiusDataProvider()
    {
        return [
            'Radius as an integer'                                           => ['r_10', ['radius' => 10]],
            'Radius as a string'                                             => ['r_10', ['radius' => '10']],
            'Radius as a user value'                                         => ['r_$v', ['radius' => '$v']],
            'Radius as an array'                                             => [
                'r_10:20:30',
                ['radius' => [10, 20, 30]]
            ],
            'Radius as an array of mixed types'                              => [
                'r_10:20:$v',
                ['radius' => [10, 20, '$v']]
            ],
            'Radius as an array of integers with a user value in the middle' => [
                'r_10:20:$v:40',
                ['radius' => [10, 20, '$v', 40]]
            ],
            'Radius as a multi string'                                       => ['r_10:20', ['radius' => ['10:20']]],
            'Radius as a multi string with a user value'                     => [
                'r_10:20:$v:40',
                ['radius' => ['10:20:$v:40']]
            ],
        ];
    }

    /**
     * Transformation data provider.
     *
     * @return array[]
     */
    public function transformationDataProvider()
    {
        return [
            'Should support a named transformation'              => ['t_blip', ['transformation' => 'blip']],
            'Should support a named transformations as an array' => [
                't_blip.blop',
                [
                    'transformation' => [
                        'blip',
                        'blop'
                    ]
                ]
            ],
            'Should support a base transformation'               => [
                'c_fill,x_100,y_100/c_crop,w_100',
                [
                    'transformation' => ['x' => 100, 'y' => 100, 'crop' => 'fill'],
                    'crop'           => 'crop',
                    'width'          => 100,
                ]
            ],
            'Should support an array of base transformations'    => [
                'c_fill,w_200,x_100,y_100/r_10/c_crop,w_100',
                [
                    'transformation' => [
                        ['x' => 100, 'y' => 100, 'width' => 200, 'crop' => 'fill'],
                        ['radius' => 10],
                    ],
                    'crop'           => 'crop',
                    'width'          => 100,
                ]
            ],
            'Should not include an empty transformation'         => [
                'c_fill,x_100,y_100',
                ['transformation' => [[], ['x' => 100, 'y' => 100, 'crop' => 'fill'], []]]
            ],
        ];
    }

    /**
     * Background data provider.
     *
     * @return array[]
     */
    public function backgroundDataProvider()
    {
        return [
            'Should support a background as a constant'   => [
                'b_red',
                ['background' => 'red'],
            ],
            'Should support a background as an RGB value' => [
                'b_rgb:112233',
                ['background' => '#112233'],
            ],
        ];
    }

    /**
     * Data provider for `testQualifiersAction()`.
     *
     * @return array[]
     */
    public function generalQualifiersActionDataProvider()
    {
        return [
            'Should support size using width and height parameters'               => [
                'c_crop,h_20,w_10',
                ['crop' => 'crop', 'width' => 10, 'height' => 20],
            ],
            'Should support size as a string separated by x'                      => [
                'c_crop,h_10,w_10',
                ['size' => '10x10', 'crop' => 'crop'],
            ],
            'Should support a video codec with profile and level parameters'      => [
                'vc_h264:basic:3.1',
                ['video_codec' => ['codec' => 'h264', 'profile' => 'basic', 'level' => '3.1']],
            ],
            'Should support a video codec'                                        => [
                'vc_h264',
                ['video_codec' => 'h264'],
            ],
            'Should support underlay'                                             => [
                'u_text',
                ['underlay' => 'text'],
            ],
            'Should support fps'                                                  => [
                'fps_22',
                ['fps' => 22],
            ],
            'Should support streaming profile when value includes a hyphen'       => [
                'sp_some-profile',
                ['streaming_profile' => 'some-profile'],
            ],
            'Should support streaming profile when value includes an underscore'  => [
                'sp_some_profile',
                ['streaming_profile' => 'some_profile'],
            ],
            'Should support a page'                                               => [
                'pg_5',
                ['page' => 5],
            ],
            'Should support an aspect ratio as width divided by the height'       => [
                'ar_1.0',
                ['aspect_ratio' => '1.0'],
            ],
            'Should support an aspect ratio with the width and the height'        => [
                'ar_3:2',
                ['aspect_ratio' => '3:2'],
            ],
            'Should support density'                                              => [
                'dn_150',
                ['density' => 150],
            ],
            'Should support an angle as an integer'                               => [
                'a_12',
                ['angle' => 12],
            ],
            'Should support an angle as an array'                                 => [
                'a_auto.12',
                ['angle' => ['auto', 12]],
            ],
            'Should support a default image'                                      => [
                'd_default',
                ['default_image' => 'default'],
            ],
            'Should not include empty options'                                    => [
                'x_0,y_0',
                [
                    'x'       => 0,
                    'y'       => '0',
                    'width'   => '',
                    'height'  => '',
                    'prefix'  => false,
                    'opacity' => null,
                ],
            ],
            'Should not support a nonexistent value'                              => [
                '',
                ['nonexistent' => 'value'],
            ],
            'Should support string interpolation'                                 => [
                'c_scale,l_text:Arial_18:$(start)Hello%20$(name)$(ext)%252C%20%24%28no%20%29%20%24%28%20no%29$(end)',
                [
                    'crop'    => 'scale',
                    'overlay' => [
                        'text'        => '$(start)Hello $(name)$(ext), $(no ) $( no)$(end)',
                        'font_family' => 'Arial',
                        'font_size'   => '18',
                    ],
                ]
            ],
        ];
    }

    /**
     * @dataProvider customFunctionDataProvider
     * @dataProvider borderDataProvider
     * @dataProvider keyframeIntervalDataProvider
     * @dataProvider audioDataProvider
     * @dataProvider videoSamplingDataProvider
     * @dataProvider startOffsetDataProvider
     * @dataProvider endOffsetDataProvider
     * @dataProvider durationParameterDataProvider
     * @dataProvider offsetDataProvider
     * @dataProvider radiusDataProvider
     * @dataProvider generalQualifiersActionDataProvider
     * @dataProvider qualityDataProvider
     * @dataProvider transformationDataProvider
     * @dataProvider userVariableDataProvider
     * @dataProvider operatorsDataProvider
     * @dataProvider effectDataProvider
     * @dataProvider flagsDataProvider
     * @dataProvider gravityDataProvider
     * @dataProvider backgroundDataProvider
     *
     * @param $transformation
     * @param $options
     */
    public function testQualifiersAction($transformation, $options)
    {
        self::assertEquals($transformation, (string)new QualifiersAction($options));
    }

    /**
     * Layers qualifiers data provider for `testOverlayOptions()`.
     *
     * @return array
     */
    public function layersQualifiersDataProvider()
    {
        return [
            'public_id'                           => [['public_id' => 'logo'], 'logo'],
            'public_id with folder'               => [['public_id' => 'folder/logo'], 'folder:logo'],
            'private'                             => [['public_id' => 'logo', 'type' => 'private'], 'private:logo'],
            'format'                              => [['public_id' => 'logo', 'format' => 'png'], 'logo.png'],
            'video'                               => [['resource_type' => 'video', 'public_id' => 'cat'], 'video:cat'],
            'text'                                => [
                ['public_id' => 'logo', 'text' => 'Hello World, Nice to meet you?'],
                'text:logo:Hello%20World%252C%20Nice%20to%20meet%20you%3F',
            ],
            'text with slash'                     => [
                [
                    'text'        => 'Hello World, Nice/ to meet you?',
                    'font_family' => 'Arial',
                    'font_size'   => '18',
                ],
                'text:Arial_18:Hello%20World%252C%20Nice%252F%20to%20meet%20you%3F',
            ],
            'text with font family and size'      => [
                [
                    'text'        => 'Hello World, Nice to meet you?',
                    'font_family' => 'Arial',
                    'font_size'   => '18',
                ],
                'text:Arial_18:Hello%20World%252C%20Nice%20to%20meet%20you%3F',
            ],
            'text with style'                     => [
                [
                    'text'           => 'Hello World, Nice to meet you?',
                    'font_family'    => 'Arial',
                    'font_size'      => '18',
                    'font_weight'    => 'bold',
                    'font_style'     => 'italic',
                    'letter_spacing' => 4,
                ],
                'text:Arial_18_bold_italic_letter_spacing_4:Hello%20World%252C%20Nice%20to%20meet%20you%3F',
            ],
            'text with antialiasing and hinting'  => [
                [
                    'text'              => 'Hello World, Nice to meet you?',
                    'font_family'       => 'Arial',
                    'font_size'         => '18',
                    'font_antialiasing' => 'best',
                    'font_hinting'      => 'medium',
                ],
                'text:Arial_18_antialias_best_hinting_medium:Hello%20World%252C%20Nice%20to%20meet%20you%3F',
            ],
            'subtitles'                           => [
                ['resource_type' => 'subtitles', 'public_id' => 'sample_sub_en.srt'],
                'subtitles:sample_sub_en.srt',
            ],
            'lut'                                 => [
                ['resource_type' => 'lut', 'public_id' => 'public_id'],
                'lut:public_id',
            ],
            'subtitles with font family and size' => [
                [
                    'resource_type' => 'subtitles',
                    'public_id'     => 'sample_sub_he.srt',
                    'font_family'   => 'Arial',
                    'font_size'     => '40',
                ],
                'subtitles:Arial_40:sample_sub_he.srt',
            ],
            'fetch'                               => [
                ['public_id' => 'logo', 'fetch' => 'https://cloudinary.com/images/old_logo.png'],
                'fetch:aHR0cHM6Ly9jbG91ZGluYXJ5LmNvbS9pbWFnZXMvb2xkX2xvZ28ucG5n',
            ],
            'fetch image url'                     => [
                'fetch:' . self::FETCH_IMAGE_URL,
                'fetch:aHR0cHM6Ly9yZXMuY2xvdWRpbmFyeS5jb20vZGVtby9pbWFnZS91cGxvYWQvc2FtcGxlLnBuZw==',
            ],
            'logo'                                => [
                ['public_id' => 'logo', 'type' => 'upload', 'resource_type' => 'image'],
                'logo'
            ],
            'image name'                          => [
                self::IMAGE_NAME,
                self::IMAGE_NAME
            ],
        ];
    }

    /**
     * @dataProvider layersQualifiersDataProvider
     *
     * @param $qualifiers
     * @param $expected
     */
    public function testLayerQualifierFactory($qualifiers, $expected)
    {
        self::assertEquals("l_$expected", (string)LayerQualifierFactory::fromParams($qualifiers));
    }

    /**
     * Layer qualifier factory expect exception data provider.
     *
     * @return array
     */
    public function layerQualifierFactoryExpectExceptionDataProvider()
    {
        return [
            'Underlay require a public id for non text for image layer'     => [
                ['resource_type' => 'image'],
                'Must supply public_id for image layer'
            ],
            'Underlay require a public id for non text for video layer'     => [
                ['resource_type' => 'video'],
                'Must supply public_id for video layer'
            ],
            'Underlay require a public id for non text for raw layer'       => [
                ['resource_type' => 'raw'],
                'Must supply public_id for raw layer'
            ],
            'Underlay require a public id for non text for subtitles layer' => [
                ['resource_type' => 'subtitles'],
                'Must supply public_id for subtitles layer'
            ],
            'Text require a public id or style'                             => [
                ['text' => 'text'],
                'Must supply either style qualifiers or a public_id when providing text qualifier in a text layer'
            ],
        ];
    }

    /**
     * Should throws an exception for wrong layer qualifiers.
     *
     * @dataProvider layerQualifierFactoryExpectExceptionDataProvider
     *
     * @param $layerQualifiers
     * @param $exceptionMessage
     */
    public function testLayerQualifierFactoryExpectException($layerQualifiers, $exceptionMessage)
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage($exceptionMessage);

        LayerQualifierFactory::fromParams($layerQualifiers);
    }
}
