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
use Psr\Http\Message\UriInterface;

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
    protected static string $assetType = 'search';

    /**
     * SearchAsset constructor.
     *
     * @param mixed|null $configuration
     */
    public function __construct(mixed $configuration = null)
    {
        parent::__construct('', $configuration);
    }

    /**
     * Creates a signed Search URL that can be used on the client side.
     *
     * @param int|null    $ttl        The time to live in seconds.
     * @param string|null $nextCursor Starting position.
     *
     * @return UriInterface The resulting search URL.
     *
     * @throws ConfigurationException
     */
    public function toUrl(?int $ttl = null, ?string $nextCursor = null): UriInterface
    {
        return $this->finalizeUrl(ArrayUtils::implodeUrl($this->prepareSearchUrlParts($ttl, $nextCursor)));
    }

    /**
     * Internal pre-serialization helper.
     *
     * @param int|null    $ttl        The time to live in seconds.
     * @param string|null $nextCursor Starting position.
     *
     * @return array URL parts.
     *
     * @internal
     */
    protected function prepareSearchUrlParts(?int $ttl = null, ?string $nextCursor = null): array
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
     * @param string $toSign Payload to sign.
     *
     * @return string the signature.
     */
    private function finalizeSearchSignature(string $toSign): string
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
