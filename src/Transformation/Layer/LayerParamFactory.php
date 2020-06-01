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
use Cloudinary\Transformation\Argument\Text\TextStyle;
use InvalidArgumentException;

/**
 * Class LayerParamFactory
 */
class LayerParamFactory
{
    /**
     * Handle overlays.
     *
     * Overlay properties can come as array or as string.
     *
     * @param string|array $layerParams
     * @param string       $layerStackPosition Supported values: LayerStackPosition::OVERLAY,
     *                                         LayerStackPosition::UNDERLAY
     *
     * @return BaseLayerParam
     *
     * @see LayerStackPosition::OVERLAY
     */
    public static function fromParams($layerParams, $layerStackPosition = LayerStackPosition::OVERLAY)
    {
        return self::handleParamValue($layerParams)->setStackPosition($layerStackPosition);
    }

    /**
     * Handles layer parameter value.
     *
     * @param string|array $layerParams The layer parameters.
     *
     * @return BaseLayerParam
     */
    protected static function handleParamValue($layerParams)
    {
        // Handle layer params
        if (is_array($layerParams)) {
            $resourceType = ArrayUtils::get($layerParams, 'resource_type');

            // Fetch layer
            $fetch = ArrayUtils::get($layerParams, 'fetch');
            if (! empty($fetch) || $resourceType === 'fetch') {
                return new FetchLayerParam($fetch);
            }

            $text     = ArrayUtils::get($layerParams, 'text');
            $publicId = ArrayUtils::get($layerParams, 'public_id');
            // Text layer
            if (! empty($text) || $resourceType === 'text') {
                $textStyle = TextStyle::fromParams($layerParams);

                if (! ($publicId !== null xor ! empty((string)$textStyle))) {
                    throw new InvalidArgumentException(
                        'Must supply either style parameters or a public_id when providing text parameter' .
                        ' in a text layer'
                    );
                }

                return (new TextLayerParam($text, $textStyle))->styleFromPublicId($publicId);
            }

            if ($publicId === null) {
                throw new InvalidArgumentException("Must supply public_id for $resourceType layer");
            }

            $format = ArrayUtils::get($layerParams, 'format');
            if ($format !== null) {
                $publicId .= '.' . $format;
            }

            if ($resourceType === 'subtitles') {
                return new SubtitlesLayerParam($publicId, $layerParams);
            }

            if ($resourceType === 'lut') {
                return new LutLayerParam($publicId);
            }

            if ($resourceType === 'video') {
                return new VideoLayerParam($publicId);
            }

            // this is a fallback for unknown(?)/future resource types
            $components = [];

            // Build a components array.
            if ($resourceType !== 'image') {
                $components[] = $resourceType;
            }
            $type = ArrayUtils::get($layerParams, 'type');
            if ($type !== 'upload') {
                $components[] = $type;
            }
            $components[] = $publicId;

            // Build a valid layer string.
            $layerParams = ArrayUtils::implodeParamValues(...$components);
        } elseif (is_string($layerParams)) {
            // Handle fetch layer from string definition.
            if (StringUtils::startsWith($layerParams, 'fetch:')) {
                return new FetchLayerParam($layerParams);
            }
        }

        return new ImageLayerParam($layerParams);
    }
}
