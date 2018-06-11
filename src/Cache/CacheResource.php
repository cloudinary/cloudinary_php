<?php

namespace Cloudinary\Cache;

use JsonSerializable;

/**
 * Class CacheResource
 */
class CacheResource implements JsonSerializable
{
    public $format = "";
    public $transformations = [];

    /**
     * CacheResource constructor.
     *
     * @param null $resourceData
     */
    public function __construct($resourceData = null)
    {
        if (!is_array($resourceData)) {
            return;
        }

        $this->format = $resourceData["format"];
        $this->transformations = $resourceData["transformations"];
    }

    /**
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        return ["format" => $this->format, "transformations" => $this->transformations];
    }


    /**
     * @param $otherCacheResource
     */
    public function updateWithOther($otherCacheResource)
    {
        if (is_array($otherCacheResource)) {
            $otherCacheResource = new CacheResource($otherCacheResource);
        }

        if (!is_null($otherCacheResource->format)) {
            $this->format = $otherCacheResource->format;
        }

        $this->transformations = array_replace_recursive($this->transformations, $otherCacheResource->transformations);
    }

    /**
     * @param $options
     *
     * @return null
     */
    public function getBreakpoints($options)
    {
        $optionsCopy = \Cloudinary::array_copy($options);
        $transformationStr = \Cloudinary::generate_transformation_string($optionsCopy);
        $format = \Cloudinary::option_get($options, "format", $this->format);

        if (!array_key_exists($transformationStr, $this->transformations) ||
            !array_key_exists($format, $this->transformations[$transformationStr])
        ) {
            return null;
        }

        return $this->transformations[$transformationStr][$format];
    }

    /**
     * @param        $options
     * @param        $value
     */
    public function setBreakpoints($options, $value)
    {
        $optionsCopy = \Cloudinary::array_copy($options);
        $transformationStr = \Cloudinary::generate_transformation_string($optionsCopy);
        $format = \Cloudinary::option_get($options, "format", $this->format);

        $this->transformations = [$transformationStr => [$format => $value]];
    }
}
