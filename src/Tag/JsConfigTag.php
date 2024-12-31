<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Tag;

use Cloudinary\ArrayUtils;
use Cloudinary\Configuration\Configuration;

/**
 * Generates an HTML `<script/>` tag for JavaScript:
 *
 * ```
 * <script type="text/javascript">
 * $.cloudinary.config({"api_key":"key","cloud_name":"name"});
 * </script>
 * ```
 *
 * @api
 */
class JsConfigTag extends BaseTag
{
    public const NAME = 'script';

    /**
     * @var array $attributes An array of tag attributes.
     */
    protected array $attributes = [
        'type' => 'text/javascript',
    ];

    /**
     * JsConfigTag constructor.
     *
     * @param Configuration $configuration The configuration instance.
     */
    public function __construct($configuration = null)
    {
        parent::__construct();

        if ($configuration === null) {
            $configuration = Configuration::instance();
        }

        $params = [
            'api_key'             => $configuration->cloud->apiKey,
            'cloud_name'          => $configuration->cloud->cloudName,
            'private_cdn'         => $configuration->url->privateCdn,
            'secure_distribution' => $configuration->url->secureCname, // secure_distribution is still used in v1
            'cdn_subdomain'       => $configuration->url->cdnSubdomain,
        ];

        $this->setContent('$.cloudinary.config('.json_encode(ArrayUtils::safeFilter($params)).');');
    }
}
