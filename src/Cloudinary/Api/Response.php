<?php

namespace Cloudinary\Api;

class Response extends \ArrayObject
{
    public function __construct($response)
    {
        parent::__construct(\Cloudinary\Api::parse_json_response($response));
        $this->rate_limit_reset_at = strtotime($response->headers["X-FeatureRateLimit-Reset"]);
        $this->rate_limit_allowed = intval($response->headers["X-FeatureRateLimit-Limit"]);
        $this->rate_limit_remaining = intval($response->headers["X-FeatureRateLimit-Remaining"]);
    }
}
