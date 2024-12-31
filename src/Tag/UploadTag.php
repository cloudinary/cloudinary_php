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
use Cloudinary\Asset\AssetType;
use Cloudinary\Configuration\Configuration;
use Cloudinary\JsonUtils;

/**
 * Generates an HTML `<input>` tag to use for uploading files.
 *
 * For example:
 *
 *```
 * <input
 *    class="cloudinary-fileupload"
 *    data-cloudinary-field="..."
 *    data-form-data="..."
 *    data-url="..."
 *    name="file"
 *    type="file"
 * >
 *```
 *
 * @api
 */
class UploadTag extends BaseConfigurableApiTag
{
    public const NAME    = 'input';
    protected const IS_VOID = true;

    /**
     * @var array $attributes An array of tag attributes.
     */
    protected array $attributes = [
        'name' => 'file',
        'type' => 'file',
    ];

    /**
     * @var array $classes An array of tag (unique) classes. Keys are used for uniqueness.
     */
    protected array $classes = ['cloudinary-fileupload' => 0];

    /**
     * UploadTag constructor.
     *
     * @param string        $field         The name of an input field in the same form that will be updated post-upload
     *                                     with the asset's metadata.
     * @param Configuration $configuration The configuration instance.
     * @param array         $uploadParams
     */
    public function __construct($field, $configuration = null, $uploadParams = [], string $assetType = AssetType::AUTO)
    {
        parent::__construct($configuration, $uploadParams, $assetType);

        $this->setAttribute('data-cloudinary-field', $field);
    }

    /**
     * Creates a new UploadTag the provided source and an array of parameters.
     *
     * @param string $field                The name of an input field in the same form that will be updated post-upload
     *                                     with the asset's metadata.
     * @param array  $params               The upload parameters.
     *
     */

    public static function fromParams(string $field, array $params = []): UploadTag
    {
        $configuration = self::fromParamsDefaultConfig();

        $configuration->importJson($params);

        return (new self($field, $configuration, $params))->addClass(ArrayUtils::get($params, ['html', 'class']));
    }

    /**
     * Serializes the tag attributes.
     *
     * @param array $attributes Optional. Additional attributes to add without affecting the tag state.
     *
     */
    public function serializeAttributes(array $attributes = []): string
    {
        $attributes['data-max-chunk-size'] = $this->apiConfig->chunkSize;
        $attributes['data-form-data']      = JsonUtils::encode($this->getUploadParams());
        $attributes['data-url']            = $this->uploadApi->getUploadUrl($this->assetType);

        return parent::serializeAttributes($attributes);
    }

    /**
     * Builds an unsigned upload tag.
     *
     * @param Configuration|null $configuration The configuration instance.
     *
     */
    public static function unsigned(
        $field,
        string $uploadPreset,
        ?Configuration $configuration = null,
        array $uploadParams = [],
        string $assetType = AssetType::AUTO
    ): UploadTag {
        $uploadParams['upload_preset'] = $uploadPreset;

        $tag = new UploadTag(
            $field,
            $configuration,
            $uploadParams,
            $assetType
        );

        $tag->config->tag->unsignedUpload = true;

        return $tag;
    }
}
