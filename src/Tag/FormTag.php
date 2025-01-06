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

use Cloudinary\Api\HttpMethod;
use Cloudinary\ArrayUtils;

/**
 * Generates an HTML `<form>` tag, for example:
 *
 *
 * ```
 * <form enctype="multipart/form-data" action=API_URL method="POST"/>
 * <input name="timestamp" type="hidden" value="\d+">
 * <input name="public_id" type="hidden" value="hello">
 * <input name="signature" type="hidden" value="[0-9a-f]+">
 * <input name="api_key" type="hidden" value="1234">
 * </form>
 * ```
 *
 * @api
 *
 */
class FormTag extends BaseConfigurableApiTag
{
    public const NAME = 'form';

    /**
     * @var array $attributes An array of tag attributes.
     */
    protected array $attributes = [
        'enctype' => 'multipart/form-data',
        'method'  => HttpMethod::POST,
    ];

    /**
     * Serializes the tag attributes.
     *
     * @param array $attributes Optional. Additional attributes to add without affecting the tag state.
     *
     * @internal
     */
    public function serializeAttributes(array $attributes = []): string
    {
        $attributes['action'] = $this->uploadApi->getUploadUrl($this->assetType);

        return parent::serializeAttributes($attributes);
    }

    /**
     * Returns input tags that are appended to the form tag.
     *
     * For example:
     * ```
     * <input name="timestamp" type="hidden" value="\d+"\/>
     * <input name="public_id" type="hidden" value="hello"\/>
     * <input name="signature" type="hidden" value="[0-9a-f]+"\/>
     * <input name="api_key" type="hidden" value="1234"\/>
     * ```
     *
     * @param array $additionalContent        The additional content.
     * @param bool  $prependAdditionalContent Whether to prepend additional content (instead of append).
     *
     *
     * @internal
     */
    public function serializeContent(array $additionalContent = [], bool $prependAdditionalContent = false): string
    {
        $inputTags    = [];
        $uploadParams = $this->getUploadParams();

        foreach ($uploadParams as $key => $value) {
            $inputTags[] = (string)new FormInputTag($key, $value);
        }

        return parent::serializeContent(
            ArrayUtils::mergeNonEmpty($inputTags, $additionalContent),
            $prependAdditionalContent
        );
    }
}
