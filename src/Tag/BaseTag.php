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
use Cloudinary\Asset\AssetQualifiers;
use Cloudinary\Configuration\Configuration;
use Cloudinary\Transformation\QualifiersAction;
use Cloudinary\Utils;
use UnexpectedValueException;

/**
 * Class BaseTag
 *
 * @api
 */
abstract class BaseTag
{
    const SINGLE_QUOTES = 'single_quotes';
    const DOUBLE_QUOTES = 'double_quotes';

    /**
     * @var string NAME Mandatory. The name of the tag.
     */
    const NAME = null;

    /**
     * @var bool IS_VOID Indicates whether the tag is a void (self-closed, without body) tag.
     */
    const IS_VOID = false;

    /**
     * @var array $classes An array of tag (unique) classes. Keys are used for uniqueness.
     */
    protected $classes = [];

    /**
     * @var array $attributes An array of tag attributes.
     */
    protected $attributes = [];

    /**
     * @var Configuration $config The Configuration instance.
     */
    public $config;

    /**
     * @var array $content The items of the tag content(body).
     */
    protected $content = [];

    /**
     * BaseTag constructor.
     *
     * @param Configuration|string|array|null $configuration The Configuration source.
     */
    public function __construct($configuration = null)
    {
        if (static::NAME === null) {
            throw new UnexpectedValueException('Tag name cannot be empty!');
        }

        if ($configuration === null) {
            $configuration = Configuration::instance(); // get global instance
        }

        $this->configuration($configuration);
    }

    /**
     * Sets the configuration.
     *
     * @param Configuration|string|array|null $configuration The Configuration source.
     *
     * @return Configuration
     */
    public function configuration($configuration)
    {
        $tempConfiguration = new Configuration($configuration); // TODO: improve performance here
        $this->config      = $tempConfiguration;

        return $tempConfiguration;
    }

    /**
     * Imports (merges) the configuration.
     *
     * @param Configuration|string|array|null $configuration The Configuration source.
     *
     * @return static
     */
    public function importConfiguration($configuration)
    {
        $this->config->importConfig($configuration);

        return $this;
    }

    /**
     * Adds a tag class.
     *
     * @param string|array $class The class to add.
     *
     * @return static
     */
    public function addClass($class)
    {
        if (empty($class)) {
            return $this;
        }

        if (is_string($class)) {
            $class = explode(' ', $class);
        }

        $this->classes = array_merge($this->classes, array_flip($class));

        return $this;
    }

    /**
     * Resets tag classes and sets the specified one.
     *
     * @param string|array $class The class to set.
     *
     * @return static
     */
    public function setClass($class)
    {
        $this->classes = [];

        return $this->addClass($class);
    }

    /**
     * Sets tag attribute.
     *
     * @param string $key   The attribute name.
     * @param mixed  $value The attribute value.
     *
     * @return static
     */
    public function setAttribute($key, $value = null)
    {
        $this->attributes[$key] = $value;

        return $this;
    }

    /**
     * Sets (multiple) tag attributes.
     *
     * @param array $attributes The attributes to set.
     *
     * @return static
     */
    public function setAttributes(array $attributes)
    {
        $this->attributes = ArrayUtils::convertToAssoc($attributes);

        return $this;
    }

    /**
     * Deletes tag attribute.
     *
     * @param string $key The name of the attribute to delete.
     *
     * @return static
     */
    public function deleteAttribute($key)
    {
        unset($this->attributes[$key]);

        return $this;
    }

    /**
     * Adds tag content.
     *
     * @param mixed $content The content value.
     *
     * @param mixed $key     Optional. Used for uniqueness.
     *
     * @return $this
     */
    public function addContent($content, $key = null)
    {
        if ($key === null) {
            $this->content [] = $content;
        } else {
            $this->content[$key] = $content;
        }

        return $this;
    }

    /**
     * Sets the content of the tag to the specified one.
     *
     * @param mixed $content The content of the tag.
     *
     * @return static
     */
    public function setContent($content)
    {
        $this->content = [];

        return $this->addContent($content);
    }

    /**
     * Serializes the tag to string.
     *
     * @return string
     */
    public function serialize()
    {
        $closingChar = static::IS_VOID && $this->config->tag->voidClosingSlash ? '/>' : '>';

        $tag = ArrayUtils::implodeFiltered(' ', ['<' . static::NAME, $this->serializeAttributes()]) . $closingChar;
        if (! static::IS_VOID) {
            $d       = $this->config->tag->contentDelimiter;
            $content = $this->serializeContent();
            $tag     .= $content ? "{$d}{$content}{$d}" : '';
            $tag     .= '</' . static::NAME . '>';
        }

        return $tag;
    }

    /**
     * Serializes the tag content.
     *
     * @param array $additionalContent        The additional content.
     * @param bool  $prependAdditionalContent Whether to prepend additional content (instead of append).
     *
     * @return string
     */
    public function serializeContent($additionalContent = [], $prependAdditionalContent = false)
    {
        $content = $prependAdditionalContent ? ArrayUtils::mergeNonEmpty(
            $additionalContent,
            $this->content
        ) : ArrayUtils::mergeNonEmpty($this->content, $additionalContent);

        return implode(
            $this->config->tag->contentDelimiter,
            $content
        );
    }

    /**
     * Serializes the tag attributes.
     *
     * @param array $attributes Optional. Additional attributes to add without affecting the tag state.
     *
     * @return string
     */
    public function serializeAttributes($attributes = [])
    {
        $classAttr     = ! empty($this->classes) ? ['class' => implode(' ', array_keys($this->classes))] : [];
        $allAttributes = array_merge($classAttr, $this->attributes, $attributes);

        if ($this->config->tag->sortAttributes) {
            ksort($allAttributes);
        }

        if ($this->config->tag->prependSrcAttribute && array_key_exists('src', $allAttributes)) {
            ArrayUtils::prependAssoc($allAttributes, 'src', ArrayUtils::pop($allAttributes, 'src'));
        }

        $attrStrings = [];
        foreach ($allAttributes as $name => $value) {
            if (is_bool($value)) {
                // if the value is set to true, we just set the name, otherwise omit the attribute.
                if ($value === false) {
                    continue;
                }

                $value = null;
            }

            $attrStrings [] = $this->serializeAttribute($name, $value);
        }

        return implode(' ', $attrStrings);
    }

    /**
     * Serializes a single tag attribute.
     *
     * @param string $name  The name of the attribute
     * @param mixed  $value The value of the attribute
     *
     * @return string
     */
    protected function serializeAttribute($name, $value)
    {
        if (empty($value)) {
            return $name;
        }

        $value = Utils::normalizeToString($value);

        if ($this->config->tag->quotesType === self::DOUBLE_QUOTES) {
            $value = '"' . htmlspecialchars($value) . '"';
        } else {
            $value = "'" . htmlspecialchars($value, ENT_QUOTES) . "'";
        }

        return "{$name}={$value}";
    }

    /**
     * Removes asset and transformation keys from the list of parameters, leaving only attributes.
     *
     * @param array $params The input parameters.
     *
     * @return array
     */
    protected static function collectAttributesFromParams($params)
    {
        $attributes = ArrayUtils::pop($params, 'attributes', []);

        $nonAttributes = array_merge(
            AssetQualifiers::ASSET_KEYS,
            array_keys(QualifiersAction::QUALIFIERS),
            ['responsive_breakpoints']
        );

        $paramsAttributes = ArrayUtils::blacklist($params, $nonAttributes);

        // Explicitly provided attributes override options
        return array_merge($paramsAttributes, $attributes);
    }

    /**
     * Returns Configuration for fromParams function.
     *
     * @return Configuration
     */
    protected static function fromParamsDefaultConfig()
    {
        $configuration = (new Configuration(Configuration::instance()));
        # set v1 defaults
        $configuration->tag->quotesType       = self::SINGLE_QUOTES;
        $configuration->tag->sortAttributes   = true;
        $configuration->tag->voidClosingSlash = true;
        $configuration->tag->contentDelimiter = '';

        return $configuration;
    }

    /**
     * Serializes the tag to string.
     *
     * @return string
     */
    public function toTag()
    {
        return $this->serialize();
    }

    /**
     * Serializes the tag to string.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toTag();
    }
}
