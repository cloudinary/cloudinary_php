<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Transformation;

use Cloudinary\ArrayUtils;
use Cloudinary\StringUtils;
use Cloudinary\Transformation\Argument\Range\Range;
use Cloudinary\Transformation\Parameter\BaseParameter;
use Cloudinary\Transformation\Variable\Variable;

/**
 * Class ParametersAction
 *
 * Action for old-style (legacy) parameters defined in an associative array.
 * The main goal is backwards compatibility with the previous version of the SDK.
 */
class ParametersAction extends BaseAction
{
    const SIMPLE_PARAMETERS = [
        'angle'             => null,
        'aspect_ratio'      => null,
        'background'        => null,
        'crop'              => 'crop_mode',
        'color'             => null,
        'dpr'               => null,
        'duration'          => null,
        'end_offset'        => null,
        'keyframe_interval' => null,
        'height'            => null,
        'if'                => 'if_condition',
        'opacity'           => null,
        'quality'           => null,
        'radius'            => 'corner_radius',
        'start_offset'      => null,
        'width'             => null,
        'x'                 => null,
        'y'                 => null,
        'zoom'              => null,
        'audio_codec'       => null,
        'audio_frequency'   => null,
        'bit_rate'          => null,
        'color_space'       => null,
        'default_image'     => null,
        'delay'             => null,
        'density'           => null,
        'fetch_format'      => 'format',
        'gravity'           => null,
        'prefix'            => null,
        'page'              => null,
        'streaming_profile' => null,
        'video_sampling'    => null,
    ];

    const COMPLEX_PARAMETERS = [
        'custom_function'     => null,
        'custom_pre_function' => null,
        'effect'              => null,
        'flags'               => null,
        'fps'                 => null,
        'offset'              => null,
        'overlay'             => null,
        'size'                => null,
        'transformation'      => null,
        'underlay'            => null,
        'video_codec'         => null,
    ];

    const PARAMETERS = self::COMPLEX_PARAMETERS + self::SIMPLE_PARAMETERS;

    /**
     * Add parameters to the action.
     *
     * @param array $parameters The parameters.
     *
     * @return $this
     */
    public function addParameters(...$parameters)
    {
        $this->parameters = ArrayUtils::mergeNonEmpty($this->parameters, ...$parameters);

        return $this;
    }

    /**
     * Serializes to Cloudinary URL format.
     *
     * @return string
     */
    public function __toString()
    {
        try {
            return $this->generateTransformationString($this->parameters);
        } catch (\Exception $e) {
            trigger_error($e->getMessage(), E_USER_ERROR);
        }

        return '';
    }

    /**
     * Generates a single transformation action.
     *
     * @used-by ParametersAction::generateTransformationString
     *
     * @param array|BaseComponent $transformationAction The action to generate.
     *
     * @return string
     */
    protected function generateTransformationAction($transformationAction)
    {
        $options = is_array($transformationAction) || $this->isTransformationComponent(
            $transformationAction
        ) ? $transformationAction :
            ['transformation' => $transformationAction];

        return $this->generateTransformationString($options);
    }

    /**
     * Generates transformation string using provided options.
     *
     * @param string|array $options Transformation parameters and other options.
     *
     * Warning: $options are being destructively updated!
     *
     * @return string The resulting transformation string.
     */
    protected function generateTransformationString($options)
    {
        if (is_string($options) || $this->isTransformationComponent($options)) {
            return (string)$options;
        }

        if (is_array($options) && ! ArrayUtils::isAssoc($options)) {
            return ArrayUtils::implodeUrl(array_map([$this, 'generateTransformationAction'], $options));
        }

        $baseTransformations = [];

        // Handle nested transformations
        $nestedTransformations = ArrayUtils::build(ArrayUtils::pop($options, 'transformation'));
        if (count(array_filter($nestedTransformations, [$this, 'isTransformation'])) > 0) {
            $baseTransformations = array_map([$this, 'generateTransformationAction'], $nestedTransformations);
        } else { // array of strings (named transformations)
            $options['transformation'] = $nestedTransformations; // put named transformations back
        }

        $if                = $this->buildParam('if', ArrayUtils::pop($options, 'if'));
        $variables         = $this->collectVariables($options);
        $paramsAction      = $this->collectParams($options);
        $rawTransformation = ArrayUtils::pop($options, 'raw_transformation');

        $transformation = ArrayUtils::implodeActionParams($if, $variables, $paramsAction, $rawTransformation);

        $baseTransformations[] = $transformation;

        return ArrayUtils::implodeUrl($baseTransformations);
    }

    /**
     * Collects transformation parameters from all options.
     *
     * @param array $options All options.
     *
     * @return Action
     */
    protected function collectParams($options)
    {
        $size = ArrayUtils::pop($options, 'size');
        if ($size) {
            list($options['width'], $options['height']) = explode('x', $size);
        }

        $offset = new Range(ArrayUtils::pop($options, 'offset'));
        if (! empty((string)$offset)) {
            $options['start_offset'] = $offset->startOffset;
            $options['end_offset']   = $offset->endOffset;
        }

        $params = [];
        foreach (array_intersect_key($options, self::PARAMETERS) as $paramName => $paramValue) {
            ArrayUtils::appendNonEmpty($params, $this->buildParam($paramName, $paramValue));
        }

        return new Action(...array_values($params));
    }

    /**
     * Factory method for building parameters from their names and values.
     *
     * @param string $paramName  The parameter name.
     * @param mixed  $paramValue The parameter value.
     *
     * @return BaseParameter|BaseAction
     */
    protected function buildParam($paramName, $paramValue)
    {
        if (array_key_exists($paramName, self::SIMPLE_PARAMETERS)) {
            $paramBuilderName = StringUtils::snakeCaseToCamelCase(
                ArrayUtils::get(self::SIMPLE_PARAMETERS, $paramName) ?: $paramName
            );

            return Parameter::$paramBuilderName($paramValue);
        }

        switch ($paramName) {
            case 'effect':
                return Effect::fromParams($paramValue);
            case 'flags':
                return Parameter::flag(implode('.', ArrayUtils::build($paramValue)));
            case 'custom_function':
                return CustomFunction::fromParams($paramValue);
            case 'custom_pre_function':
                return CustomFunction::fromParams($paramValue, true);
            case 'fps':
                return Fps::fromParams($paramValue);
            case 'overlay':
                return LayerParamFactory::fromParams($paramValue, LayerStackPosition::OVERLAY);
            case 'transformation':
                return Parameter::namedTransformation(implode('.', ArrayUtils::build($paramValue)));
            case 'underlay':
                return LayerParamFactory::fromParams($paramValue, LayerStackPosition::UNDERLAY);
            case 'video_codec':
                return VideoCodec::fromParams($paramValue);
            default:
                return null;
        }
    }

    /**
     * Variables can be defined under 'variables' key or spread all over other parameters.
     *
     * Here we collect all the variables and sort them alphabetically (for consistency) and after that append all the
     * variables defined under 'variables' key (without sorting, order matters)
     *
     * @param array $options Transformation options
     *
     * @return string Serialized variables
     */
    protected function collectVariables(&$options)
    {
        $variables = ArrayUtils::get($options, 'variables', []);

        $varParams = [];
        foreach ($options as $key => $value) {
            if (Variable::isVariable($key)) {
                $varParams[] = Variable::define($key, $value);
            }
        }

        sort($varParams, SORT_STRING);

        // These variables are NOT sorted in alphabetic order
        if (! empty($variables)) {
            foreach ($variables as $key => $value) {
                $varParams[] = Variable::define($key, $value);
            }
        }

        return ArrayUtils::implodeActionParams(...$varParams);
    }

    /**
     * @param $candidate
     *
     * @return bool
     */
    private function isTransformationComponent($candidate)
    {
        return $candidate instanceof BaseComponent;
    }

    /**
     * @param $candidate
     *
     * @return bool
     */
    private function isTransformation($candidate)
    {
        return is_array($candidate) || $this->isTransformationComponent($candidate);
    }
}
