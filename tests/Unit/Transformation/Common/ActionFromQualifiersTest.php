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

use Cloudinary\Asset\AssetType;
use Cloudinary\Test\Unit\Asset\AssetTestCase;
use Cloudinary\Transformation\LayerQualifierFactory;
use Cloudinary\Transformation\QualifiersAction;
use InvalidArgumentException;

/**
 * Class ActionFromQualifiersTest
 */
final class ActionFromQualifiersTest extends AssetTestCase
{

    private static $customFunctionWasm    = ['function_type' => 'wasm', 'source' => 'blur.wasm'];
    private static $customFunctionWasmStr = 'wasm:blur.wasm';

    private static $customFunctionRemote    = [
        'function_type' => 'remote',
        'source'        => 'https://df34ra4a.execute-api.us-west-2.amazonaws.com/default/cloudinaryFn',
    ];
    private static $customFunctionRemoteStr =
        'remote:aHR0cHM6Ly9kZjM0cmE0YS5leGVjdXRlLWFwaS51cy13ZXN0LTIuYW1hem9uYXdzLmNvbS9kZWZhdWx0L2Nsb3VkaW5hcnlGbg==';

    public function testStreamingProfile()
    {
        // should support streaming profile
        self::assertQualifiersAction("sp_some-profile", ["streaming_profile" => "some-profile"]);
    }

    public function testEffect()
    {
        // should support effect
        self::assertQualifiersAction("e_sepia", ["effect" => "sepia"]);
        // should support effect with array
        $options = ["effect" => ["sepia", -10]];
        self::assertQualifiersAction("e_sepia:-10", ["effect" => ["sepia", -10]]);
    }

    public function testDensity()
    {
        self::assertQualifiersAction("dn_150", ["density" => 150]);
    }

    public function testCustomFunction()
    {
        $wasmStr = self::$customFunctionWasmStr;

        // should support custom function from string
        $options = ['custom_function' => self::$customFunctionWasmStr];
        self::assertQualifiersAction("fn_$wasmStr", $options);


        // should support custom function from array
        $options = ['custom_function' => self::$customFunctionWasm];
        self::assertQualifiersAction("fn_$wasmStr", $options);

        $remoteStr = self::$customFunctionRemoteStr;
        // should encode custom function source for remote function
        $options = ['custom_function' => self::$customFunctionRemote];
        self::assertQualifiersAction("fn_$remoteStr", $options);
    }

    public function testCustomPreFunctionString()
    {
        $wasmStr = self::$customFunctionWasmStr;

        // should support custom pre function from string
        $options = ['custom_pre_function' => self::$customFunctionWasmStr];
        self::assertQualifiersAction("fn_pre:$wasmStr", $options);
    }

    public function test_custom_pre_function_wasm_array()
    {
        $wasmStr = self::$customFunctionWasmStr;

        // should support custom pre function from array
        self::assertQualifiersAction("fn_pre:$wasmStr", ['custom_pre_function' => self::$customFunctionWasm]);
    }

    public function test_custom_pre_function_remote()
    {
        $remoteStr = self::$customFunctionRemoteStr;

        // should encode custom pre function source for remote pre function
        self::assertQualifiersAction("fn_pre:$remoteStr", ['custom_pre_function' => self::$customFunctionRemote]);
    }

    public function testPage()
    {
        self::assertQualifiersAction("pg_5", ["page" => 5]);
    }

    public function testBorder()
    {
        $tests = [
            'bo_1px_solid_blue'         => '1px_solid_blue',
            'bo_5px_solid_black'        => ['width' => 5],
            'bo_5px_solid_rgb:ffaabbdd' => ['width' => 5, 'color' => '#ffaabbdd'],

        ];

        foreach ($tests as $transformation => $value) {
            self::assertQualifiersAction($transformation, ['border' => $value]);
        }
    }

    public function testFlags()
    {
        self::assertQualifiersAction('fl_abc', ['flags' => 'abc']);
        self::assertQualifiersAction('fl_abc.def', ["flags" => ['abc', 'def']]);
    }

    public function testAspectRatio()
    {
        self::assertQualifiersAction("ar_1.0", ["aspect_ratio" => "1.0"]);
        self::assertQualifiersAction("ar_3:2", ["aspect_ratio" => "3:2"]);
    }

    public function testEArtIncognito()
    {
        self::assertQualifiersAction("e_art:incognito", ["effect" => "art:incognito"]);
    }

    /**
     * Should not normalize "duration" named value as "du_" parameter.
     */
    public function testPreviewDuration()
    {
        self::assertQualifiersAction("e_preview:duration_2", ["effect" => "preview:duration_2"]);
    }

    /**
     * Should support a positive number or a string
     */
    public function testKeyframeInterval()
    {
        $tests = [
            'ki_10.0'  => 10,
            'ki_0.05'  => 0.05,
            'ki_3.45'  => 3.45,
            'ki_300.0' => 300,
            'ki_10'    => '10',
        ];

        foreach ($tests as $transformation => $value) {
            self::assertQualifiersAction($transformation, ['keyframe_interval' => $value]);
        }
    }

    public function testAudioCodec()
    {
        // should support a string value
        self::assertQualifiersAction('ac_acc', ['audio_codec' => 'acc']);
    }

    public function testBitRate()
    {
        $tests = [
            'br_2048' => 2048,
            'br_44k'  => '44k',
            'br_1m'   => '1m',
        ];
        foreach ($tests as $transformation => $startOffset) {
            self::assertQualifiersAction($transformation, ['bit_rate' => $startOffset]);
        }
    }

    public function testAudioFrequency()
    {
        // should support an integer value
        self::assertQualifiersAction('af_44100', ['audio_frequency' => 44100]);
    }

    public function testVideoSampling()
    {
        $tests = [
            'vs_20'   => 20,
            'vs_2.3s' => '2.3s',
        ];
        foreach ($tests as $transformation => $value) {
            self::assertQualifiersAction($transformation, ['video_sampling' => $value]);
        }
    }

    public function testStartOffset()
    {
        $tests = [
            'so_2.63' => 2.63,
            'so_2.64' => '2.64',
            'so_35p'  => '35p',
            'so_36p'  => '36%',
            'so_auto' => 'auto',
        ];
        foreach ($tests as $transformation => $value) {
            self::assertQualifiersAction($transformation, ['start_offset' => $value]);
        }
    }

    public function testEndOffset()
    {
        $tests = [
            'eo_2.63' => 2.63,
            'eo_2.64' => '2.64',
            'eo_35p'  => '35p',
            'eo_36p'  => '36%',
        ];
        foreach ($tests as $transformation => $value) {
            self::assertQualifiersAction($transformation, ['end_offset' => $value]);
        }
    }

    public function testDurationParameter()
    {
        $tests = [
            'du_2.63' => 2.63,
            'du_2.64' => '2.64',
            'du_35p'  => '35p',
            'du_36p'  => '36%',
        ];
        foreach ($tests as $transformation => $value) {
            self::assertQualifiersAction($transformation, ['duration' => $value]);
        }
    }

    public function testOffset()
    {
        $tests = [
            'eo_3.21,so_2.66'   => '2.66..3.21',
            'eo_3.22,so_2.67'   => [2.67, 3.22],
            'eo_70p,so_35p'     => ['35%', '70%'],
            'eo_71p,so_36p'     => ['36p', '71p'],
            'eo_70.5p,so_35.5p' => ['35.5p', '70.5p'],
        ];
        foreach ($tests as $transformation => $value) {
            self::assertQualifiersAction($transformation, ['offset' => $value]);
        }
    }

    public function testLayerQualifierFactory()
    {
        $lp = LayerQualifierFactory::fromParams(self::IMAGE_NAME);

        self::assertEquals(
            'l_' . self::IMAGE_NAME,
            (string)$lp
        );

        $lp = LayerQualifierFactory::fromParams('fetch:' . self::FETCH_IMAGE_URL);

        self::assertEquals(
            'l_fetch:aHR0cHM6Ly9yZXMuY2xvdWRpbmFyeS5jb20vZGVtby9pbWFnZS91cGxvYWQvc2FtcGxlLnBuZw==',
            (string)$lp
        );
    }

    /**
     * @return array
     *
     * @internal
     *
     * Data Provider for testOverlayOptions
     *
     */
    public function layersQualifiers()
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
            'lut'                           => [
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

        ];
    }

    /**
     * @dataProvider layersQualifiers
     *
     * @param $qualifiers
     * @param $expected
     */
    public function testOverlayOptions($qualifiers, $expected)
    {
        self::assertEquals("l_$expected", (string)LayerQualifierFactory::fromParams($qualifiers));
    }

    public function testIgnoreDefaultValuesInOverlayOptions()
    {
        $options  = ['public_id' => 'logo', 'type' => 'upload', 'resource_type' => 'image'];
        $expected = 'logo';
        self::assertEquals("l_$expected", (string)LayerQualifierFactory::fromParams($options));
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Must supply either style qualifiers or a public_id
     * when providing text qualifier in a text overlay
     */
    public function testTextRequirePublicIdOrStyle()
    {
        $options = ['text' => 'text'];
        LayerQualifierFactory::fromParams($options);
    }

    /**
     * @return array
     */
    public function layerAssetTypes()
    {
        return [
            AssetType::IMAGE => [AssetType::IMAGE],
            AssetType::VIDEO => [AssetType::VIDEO],
            AssetType::RAW   => [AssetType::RAW],
            'subtitles'      => ['subtitles'],
        ];
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessageRegExp #Must supply public_id for .* layer#
     * @dataProvider layerAssetTypes
     *
     * @param $resourceType
     */
    public function testUnderlayRequirePublicIdForNonText($resourceType)
    {
        $options = ['resource_type' => $resourceType];
        LayerQualifierFactory::fromParams($options);
    }

    /**
     * should support and translate operators: '=', '!=', '<', '>', '<=', '>=', '&&', '||'
     * and variables: width, height, pages, faces, aspect_ratio
     */
    public function testTranslateIf()
    {
        $allOperators =
            'if_' .
            'w_eq_0_and' .
            '_h_ne_0_or' .
            '_ar_lt_0_and' .
            '_pc_gt_0_and' .
            '_fc_lte_0_and' .
            '_w_gte_0' .
            ',e_grayscale';

        $cond = 'width = 0 && height != 0 || aspect_ratio < 0 && page_count > 0 and face_count <= 0 and width >= 0';

        $options = ['if' => $cond, 'effect' => 'grayscale'];

        self::assertQualifiersAction($allOperators, $options);

        $options = ['if' => 'aspect_ratio > 0.3 && aspect_ratio < 0.5', 'effect' => 'grayscale'];

        self::assertQualifiersAction('if_ar_gt_0.3_and_ar_lt_0.5,e_grayscale', $options);
    }

    public function testNormalizeExpressionShouldNotConvertUserVariables()
    {
        $options = [
            'transformation' => [
                ['$width' => 10],
                ['width' => '$width + 10 + width'],
            ],
        ];

        self::assertQualifiersAction('$width_10/w_$width_add_10_add_w', $options);
    }

    public function testArrayShouldDefineSetOfVariables()
    {
        $options = [
            'if'        => 'face_count > 2',
            'crop'      => 'scale',
            'width'     => '$foo * 200',
            'variables' => [
                '$z'   => 5,
                '$foo' => '$z * 2',
            ],
        ];

        self::assertQualifiersAction('if_fc_gt_2,$z_5,$foo_$z_mul_2,c_scale,w_$foo_mul_200', $options);
    }

    public function testDurationVariable()
    {
        $options = ['if' => 'duration > 30', 'width' => '100', 'crop' => 'scale'];

        self::assertQualifiersAction('if_du_gt_30,c_scale,w_100', $options);

        $options = ['if' => 'initial_duration > 30', 'width' => '100', 'crop' => 'scale'];

        self::assertQualifiersAction('if_idu_gt_30,c_scale,w_100', $options);
    }

    public function testKeyShouldDefineVariable()
    {
        $options = [
            'transformation' => [
                ['$foo' => 10],
                ['if' => 'face_count > 2'],
                ['crop' => 'scale', 'width' => '$foo * 200 / face_count'],
                ['if' => 'end'],
            ],
        ];

        self::assertQualifiersAction('$foo_10/if_fc_gt_2/c_scale,w_$foo_mul_200_div_fc/if_end', $options);
    }

    public function testUrlShouldConvertOperators()
    {
        $options = [
            'transformation' => [
                ['width' => 'initial_width ^ 2', 'height' => 'initial_height * 2', 'crop' => 'scale'],
            ],
        ];

        self::assertQualifiersAction('c_scale,h_ih_mul_2,w_iw_pow_2', $options);
    }

    public function testShouldSupportStreamingProfile()
    {
        $options = [
            'streaming_profile' => 'some_profile',
        ];

        self::assertQualifiersAction('sp_some_profile', $options);
    }

    public function testShouldSortDefinedVariable()
    {
        $options = [
            '$second' => 1,
            '$first'  => 2,
        ];

        self::assertQualifiersAction('$first_2,$second_1', $options);
    }

    public function testShouldPlaceDefinedVariablesBeforeOrdered()
    {
        $options = [
            'variables' => [
                '$z'   => 5,
                '$foo' => '$z * 2',
            ],
            '$second'   => 1,
            '$first'    => 2,
        ];

        self::assertQualifiersAction('$first_2,$second_1,$z_5,$foo_$z_mul_2', $options);
    }

    public function testShouldSupportTextValues()
    {
        $e = [
            'effect'  => '$efname:100',
            '$efname' => '!blur!',
        ];

        self::assertQualifiersAction('$efname_!blur!,e_$efname:100', $e);
    }

    public function testShouldSupportStringInterpolation()
    {
        $options =
            [
                'crop'    => 'scale',
                'overlay' => [
                    'text'        => '$(start)Hello $(name)$(ext), $(no ) $( no)$(end)',
                    'font_family' => 'Arial',
                    'font_size'   => '18',
                ],
            ];

        self::assertQualifiersAction(
            'c_scale,l_text:Arial_18:$(start)Hello%20$(name)$(ext)%252C%20%24%28no%20%29%20%24%28%20no%29$(end)',
            $options
        );
    }


    public function testVariousOptions()
    {
        // should use x, y, radius, prefix, gravity and quality from $options
        $options = [
            'x'       => 1,
            'y'       => 2,
            'radius'  => 3,
            'gravity' => 'center',
            'quality' => 0.4,
            'prefix'  => 'a',
            'opacity' => 20,
        ];
        self::assertQualifiersAction(
            'g_center,o_20,p_a,q_0.4,r_3,x_1,y_2',
            $options
        );
        $options = ['gravity' => 'auto', 'crop' => 'crop', 'width' => 0.5];
        self::assertQualifiersAction(
            'c_crop,g_auto,w_0.5',
            $options
        );
        $options = ['gravity' => 'auto:ocr_text', 'crop' => 'crop', 'width' => 0.5];
        self::assertQualifiersAction(
            'c_crop,g_auto:ocr_text,w_0.5',
            $options
        );
        $options = ['gravity' => 'ocr_text', 'crop' => 'crop', 'width' => 0.5];
        self::assertQualifiersAction(
            'c_crop,g_ocr_text,w_0.5',
            $options
        );
    }

    public function testQuality()
    {
        self::assertQualifiersAction(
            'g_center,p_a,q_80,r_3,x_1,y_2',
            ['x' => 1, 'y' => 2, 'radius' => 3, 'gravity' => 'center', 'quality' => 80, 'prefix' => 'a']
        );
        self::assertQualifiersAction(
            'g_center,p_a,q_80:444,r_3,x_1,y_2',
            ['x' => 1, 'y' => 2, 'radius' => 3, 'gravity' => 'center', 'quality' => '80:444', 'prefix' => 'a']
        );
        self::assertQualifiersAction(
            'g_center,p_a,q_auto,r_3,x_1,y_2',
            ['x' => 1, 'y' => 2, 'radius' => 3, 'gravity' => 'center', 'quality' => 'auto', 'prefix' => 'a']
        );
        self::assertQualifiersAction(
            'g_center,p_a,q_auto:good,r_3,x_1,y_2',
            ['x' => 1, 'y' => 2, 'radius' => 3, 'gravity' => 'center', 'quality' => 'auto:good', 'prefix' => 'a']
        );
    }

    /**
     * Should support a string, integer and array of mixed types
     */
    public function testRadius()
    {
        $radiusTestValues = [
            [10, "r_10"],
            ['10', 'r_10'],
            ['$v', 'r_$v'],
            [[10, 20, 30], 'r_10:20:30'],
            [[10, 20, '$v'], 'r_10:20:$v'],
            [[10, 20, '$v', 40], 'r_10:20:$v:40'],
            [['10:20'], 'r_10:20'],
            [['10:20:$v:40'], 'r_10:20:$v:40'],
        ];

        foreach ($radiusTestValues as $value) {
            self::assertQualifiersAction($value[1], ['radius' => $value[0]]);
        }
    }

    public function testNoEmptyOptions()
    {
        // should use x, y, width, height, crop, prefix and opacity from $options
        $options = [
            'x'       => 0,
            'y'       => '0',
            'width'   => '',
            'height'  => '',
            #"crop"    => ' ',
            'prefix'  => false,
            'opacity' => null,
        ];
        self::assertQualifiersAction('x_0,y_0', $options);
    }

    public function testTransformationSimple()
    {
        // should support named transformation
        $options = ['transformation' => 'blip'];
        self::assertQualifiersAction('t_blip', $options);
    }

    public function testTransformationArray()
    {
        // should support array of named transformations
        $options = ['transformation' => ['blip', 'blop']];
        self::assertQualifiersAction('t_blip.blop', $options);
    }

    public function testBaseTransformations()
    {
        // should support base transformation
        $options = [
            'transformation' => ['x' => 100, 'y' => 100, 'crop' => 'fill'],
            'crop'           => 'crop',
            'width'          => 100,
        ];
        self::assertQualifiersAction('c_fill,x_100,y_100/c_crop,w_100', $options);
    }

    public function testBaseTransformationArray()
    {
        // should support array of base transformations
        $options = [
            'transformation' => [
                ['x' => 100, 'y' => 100, 'width' => 200, 'crop' => 'fill'],
                ['radius' => 10],
            ],
            'crop'           => 'crop',
            'width'          => 100,
        ];
        self::assertQualifiersAction(
            'c_fill,w_200,x_100,y_100/r_10/c_crop,w_100',
            $options
        );
    }

    public function testNoEmptyTransformation()
    {
        // should not include empty transformations
        $options = ['transformation' => [[], ['x' => 100, 'y' => 100, 'crop' => 'fill'], []]];
        self::assertQualifiersAction(
            'c_fill,x_100,y_100',
            $options
        );
    }

    public function testSize()
    {
        // should support size
        $options = ['size' => '10x10', 'crop' => 'crop'];
        self::assertQualifiersAction('c_crop,h_10,w_10', $options);
    }

    public function testBackground()
    {
        // should support background
        self::assertQualifiersAction('b_red', ["background" => "red"]);
        self::assertQualifiersAction('b_rgb:112233', ["background" => "#112233"]);
    }

    public function testDefaultImage()
    {
        // should support default_image
        self::assertQualifiersAction('d_default', ["default_image" => "default"]);
    }

    public function testAngle()
    {
        // should support angle
        self::assertQualifiersAction('a_12', ['angle' => 12]);
        self::assertQualifiersAction('a_auto.12', ["angle" => ["auto", 12]]);
    }

    /**
     * @param $expectedStr
     * @param $options
     */
    protected static function assertQualifiersAction($expectedStr, $options)
    {
        self::assertEquals($expectedStr, (string)new QualifiersAction($options));
    }
}
