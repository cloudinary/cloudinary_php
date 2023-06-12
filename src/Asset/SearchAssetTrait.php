<?php

namespace Cloudinary\Asset;

/**
 * Trait SearchAssetTrait
 */
trait SearchAssetTrait
{
    private $ttl = 300;

    /**
     * Sets the time to live of the search URL.
     *
     * @param int $ttl The time to live in seconds.
     *
     * @return $this
     *
     * @api
     */
    public function ttl($ttl)
    {
        $this->ttl = $ttl;

        return $this;
    }
}
