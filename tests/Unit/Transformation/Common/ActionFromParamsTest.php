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
use Cloudinary\Transformation\LayerParamFactory;
use Cloudinary\Transformation\ParametersAction;
use InvalidArgumentException;

/**
 * Class ActionFromParamsTest
 */
final class ActionFromParamsTest extends AssetTestCase
{
    public function testLayerParamFactory()
    {
        $lp = LayerParamFactory::fromParams(self::IMAGE_NAME);

        $this->assertEquals(
            'l_' . self::IMAGE_NAME,
            (string)$lp
        );

        $lp = LayerParamFactory::fromParams('fetch:' . self::FETCH_IMAGE_URL);

        $this->assertEquals(
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
    public function layersParams()
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
     * @dataProvider layersParams
     *
     * @param $params
     * @param $expected
     */
    public function testOverlayOptions($params, $expected)
    {
        $this->assertEquals("l_$expected", (string)LayerParamFactory::fromParams($params));
    }

    public function testIgnoreDefaultValuesInOverlayOptions()
    {
        $options  = ['public_id' => 'logo', 'type' => 'upload', 'resource_type' => 'image'];
        $expected = 'logo';
        $this->assertEquals("l_$expected", (string)LayerParamFactory::fromParams($options));
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Must supply either style parameters or a public_id
     * when providing text parameter in a text overlay
     */
    public function testTextRequirePublicIdOrStyle()
    {
        $options = ['text' => 'text'];
        LayerParamFactory::fromParams($options);
    }

    /**
     * @return array
     */
    public function layerResourceTypes()
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
     * @dataProvider layerResourceTypes
     *
     * @param $resourceType
     */
    public function testUnderlayRequirePublicIdForNonText($resourceType)
    {
        $options = ['resource_type' => $resourceType];
        LayerParamFactory::fromParams($options);
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

        $this->assertParametersAction($allOperators, $options);

        $options = ['if' => 'aspect_ratio > 0.3 && aspect_ratio < 0.5', 'effect' => 'grayscale'];

        $this->assertParametersAction('if_ar_gt_0.3_and_ar_lt_0.5,e_grayscale', $options);
    }

    public function testNormalizeExpressionShouldNotConvertUserVariables()
    {
        $options = [
            'transformation' => [
                ['$width' => 10],
                ['width' => '$width + 10 + width'],
            ],
        ];

        $this->assertParametersAction('$width_10/w_$width_add_10_add_w', $options);
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

        $this->assertParametersAction('if_fc_gt_2,$z_5,$foo_$z_mul_2,c_scale,w_$foo_mul_200', $options);
    }

    public function testDurationVariable()
    {
        $options = ['if' => 'duration > 30', 'width' => '100', 'crop' => 'scale'];

        $this->assertParametersAction('if_du_gt_30,c_scale,w_100', $options);

        $options = ['if' => 'initial_duration > 30', 'width' => '100', 'crop' => 'scale'];

        $this->assertParametersAction('if_idu_gt_30,c_scale,w_100', $options);
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

        $this->assertParametersAction('$foo_10/if_fc_gt_2/c_scale,w_$foo_mul_200_div_fc/if_end', $options);
    }

    public function testUrlShouldConvertOperators()
    {
        $options = [
            'transformation' => [
                ['width' => 'initial_width ^ 2', 'height' => 'initial_height * 2', 'crop' => 'scale'],
            ],
        ];

        $this->assertParametersAction('c_scale,h_ih_mul_2,w_iw_pow_2', $options);
    }

    public function testShouldSupportStreamingProfile()
    {
        $options = [
            'streaming_profile' => 'some_profile',
        ];

        $this->assertParametersAction('sp_some_profile', $options);
    }

    public function testShouldSortDefinedVariable()
    {
        $options = [
            '$second' => 1,
            '$first'  => 2,
        ];

        $this->assertParametersAction('$first_2,$second_1', $options);
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

        $this->assertParametersAction('$first_2,$second_1,$z_5,$foo_$z_mul_2', $options);
    }

    public function testShouldSupportTextValues()
    {
        $e = [
            'effect'  => '$efname:100',
            '$efname' => '!blur!',
        ];

        $this->assertParametersAction('$efname_!blur!,e_$efname:100', $e);
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

        $this->assertParametersAction(
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
        $this->assertParametersAction(
            'g_center,o_20,p_a,q_0.4,r_3,x_1,y_2',
            $options
        );
        $options = ['gravity' => 'auto', 'crop' => 'crop', 'width' => 0.5];
        $this->assertParametersAction(
            'c_crop,g_auto,w_0.5',
            $options
        );
        $options = ['gravity' => 'auto:ocr_text', 'crop' => 'crop', 'width' => 0.5];
        $this->assertParametersAction(
            'c_crop,g_auto:ocr_text,w_0.5',
            $options
        );
        $options = ['gravity' => 'ocr_text', 'crop' => 'crop', 'width' => 0.5];
        $this->assertParametersAction(
            'c_crop,g_ocr_text,w_0.5',
            $options
        );
    }

    public function testQuality()
    {
        $this->assertParametersAction(
            'g_center,p_a,q_80,r_3,x_1,y_2',
            ['x' => 1, 'y' => 2, 'radius' => 3, 'gravity' => 'center', 'quality' => 80, 'prefix' => 'a']
        );
        $this->assertParametersAction(
            'g_center,p_a,q_80:444,r_3,x_1,y_2',
            ['x' => 1, 'y' => 2, 'radius' => 3, 'gravity' => 'center', 'quality' => '80:444', 'prefix' => 'a']
        );
        $this->assertParametersAction(
            'g_center,p_a,q_auto,r_3,x_1,y_2',
            ['x' => 1, 'y' => 2, 'radius' => 3, 'gravity' => 'center', 'quality' => 'auto', 'prefix' => 'a']
        );
        $this->assertParametersAction(
            'g_center,p_a,q_auto:good,r_3,x_1,y_2',
            ['x' => 1, 'y' => 2, 'radius' => 3, 'gravity' => 'center', 'quality' => 'auto:good', 'prefix' => 'a']
        );
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
        $this->assertParametersAction('x_0,y_0', $options);
    }

    public function testTransformationSimple()
    {
        // should support named transformation
        $options = ['transformation' => 'blip'];
        $this->assertParametersAction('t_blip', $options);
    }

    public function testTransformationArray()
    {
        // should support array of named transformations
        $options = ['transformation' => ['blip', 'blop']];
        $this->assertParametersAction('t_blip.blop', $options);
    }

    public function testBaseTransformations()
    {
        // should support base transformation
        $options = [
            'transformation' => ['x' => 100, 'y' => 100, 'crop' => 'fill'],
            'crop'           => 'crop',
            'width'          => 100,
        ];
        $this->assertParametersAction('c_fill,x_100,y_100/c_crop,w_100', $options);
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
        $this->assertParametersAction(
            'c_fill,w_200,x_100,y_100/r_10/c_crop,w_100',
            $options
        );
    }

    public function testNoEmptyTransformation()
    {
        // should not include empty transformations
        $options = ['transformation' => [[], ['x' => 100, 'y' => 100, 'crop' => 'fill'], []]];
        $this->assertParametersAction(
            'c_fill,x_100,y_100',
            $options
        );
    }

    public function testSize()
    {
        // should support size
        $options = ['size' => '10x10', 'crop' => 'crop'];
        $this->assertParametersAction('c_crop,h_10,w_10', $options);
    }

    /**
     * @param $expectedStr
     * @param $options
     */
    protected function assertParametersAction($expectedStr, $options)
    {
        $this->assertEquals($expectedStr, (string)new ParametersAction($options));
    }
}
