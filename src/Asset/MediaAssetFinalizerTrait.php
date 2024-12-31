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

use Cloudinary\ArrayUtils;
use Cloudinary\Exception\ConfigurationException;
use Cloudinary\StringUtils;
use Cloudinary\Transformation\CommonTransformation;
use Cloudinary\Utils;

/**
 * Trait MediaAssetFinalizerTrait
 *
 * @property AssetDescriptor      $asset
 * @property AuthToken            $authToken
 * @property CommonTransformation $transformation
 */
trait MediaAssetFinalizerTrait
{
    /**
     * Finalizes asset transformation.
     *
     * @param mixed $withTransformation Additional transformation
     * @param bool  $append             Whether to append transformation or set in instead of the asset transformation.
     *
     */
    protected function finalizeTransformation(mixed $withTransformation = null, bool $append = true): string
    {
        if ($withTransformation === null && ! $this->urlConfig->responsiveWidth) {
            return (string)$this->transformation;
        }

        if (! $append || $this->transformation === null) {
            return (string)$withTransformation;
        }

        $resultingTransformation = clone $this->transformation;

        if ($this->urlConfig->responsiveWidth) {
            $resultingTransformation->addTransformation($this->urlConfig->responsiveWidthTransformation);
        }

        $resultingTransformation->addTransformation($withTransformation);

        return (string)$resultingTransformation;
    }

    /**
     * Sign both transformation and asset parts of the URL.
     *
     * @throws ConfigurationException
     */
    protected function finalizeSimpleSignature(): string
    {
        if (! $this->urlConfig->signUrl || $this->authToken->isEnabled()) {
            return '';
        }

        if (empty($this->cloud->apiSecret)) {
            throw new ConfigurationException('Must supply apiSecret');
        }

        $toSign    = ArrayUtils::implodeUrl([$this->transformation, $this->asset->publicId()]);
        $signature = StringUtils::base64UrlEncode(
            Utils::sign(
                $toSign,
                $this->cloud->apiSecret,
                true,
                $this->getSignatureAlgorithm()
            )
        );

        return Utils::formatSimpleSignature(
            $signature,
            $this->urlConfig->longUrlSignature ? Utils::LONG_URL_SIGNATURE_LENGTH : Utils::SHORT_URL_SIGNATURE_LENGTH
        );
    }

    /**
     * Finalizes 'shorten' functionality.
     *
     * Currently only image/upload is supported.
     *
     * @param string|null $assetType The asset type to finalize.
     *
     * @return null|string The finalized asset type.
     */
    protected function finalizeShorten(?string $assetType): ?string
    {
        if ($this->urlConfig->shorten
            && $this->asset->deliveryType === DeliveryType::UPLOAD
            && $this->asset->assetType === AssetType::IMAGE
        ) {
            $assetType = Image::SHORTEN_ASSET_TYPE;
        }

        if ($this->urlConfig->useRootPath) {
            $assetType = null;
        }

        return $assetType;
    }
}
