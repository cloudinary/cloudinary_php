<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Asset;

use Cloudinary\Api\Search\SearchQueryInterface;
use Cloudinary\Api\Search\SearchQueryTrait;
use Cloudinary\ArrayUtils;
use Cloudinary\Exception\ConfigurationException;
use Cloudinary\StringUtils;
use Cloudinary\Utils;

/**
 * Class SearchAsset
 *
 * @api
 */
class SearchAsset extends BaseAsset implements SearchQueryInterface
{
    use SearchQueryTrait;
    use SearchAssetTrait;

    /**
     * @var string $assetType The type of the asset.
     */
    protected static $assetType = 'search';

    /**
     * SearchAsset constructor.
     *
     * @param mixed $configuration
     */
    public function __construct($configuration = null)
    {
        parent::__construct('', $configuration);
    }

    /**
     * Creates a signed Search URL that can be used on the client side.
     *
     * @param int    $ttl        The time to live in seconds.
     * @param string $nextCursor Starting position.
     *
     * @return string The resulting search URL.
     *
     * @throws ConfigurationException
     */
    public function toUrl($ttl = null, $nextCursor = null)
    {
        return $this->finalizeUrl(ArrayUtils::implodeUrl($this->prepareSearchUrlParts($ttl, $nextCursor)));
    }

    /**
     * Internal pre-serialization helper.
     *
     * @return array
     *
     * @internal
     */
    protected function prepareSearchUrlParts($ttl, $nextCursor)
    {
        if ($ttl == null) {
            $ttl = $this->ttl;
        }

        $query = $this->asArray();

        $_nextCursor = ArrayUtils::pop($query, 'next_cursor');
        if ($nextCursor == null) {
            $nextCursor = $_nextCursor;
        }

        ksort($query);
        $b64Query = StringUtils::base64UrlEncode(json_encode($query));

        return [
            'distribution' => $this->finalizeDistribution(),
            'asset_type'   => self::$assetType,
            'signature'    => $this->finalizeSearchSignature("{$ttl}{$b64Query}"),
            'ttl'          => $ttl,
            'b64query'     => $b64Query,
            'next_cursor'  => $nextCursor,
        ];
    }

    /**
     * Finalizes URL signature.
     *
     * @see https://cloudinary.com/documentation/advanced_url_delivery_options#generating_delivery_url_signatures
     *
     * @return string
     * @throws ConfigurationException
     */
    private function finalizeSearchSignature($toSign)
    {
        if (empty($this->cloud->apiSecret)) {
            throw new ConfigurationException('Must supply apiSecret');
        }

        return Utils::sign(
            $toSign,
            $this->cloud->apiSecret,
            false,
            Utils::ALGO_SHA256
        );
    }
}
