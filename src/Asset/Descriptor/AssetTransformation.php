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

use BadMethodCallException;
use Cloudinary\ArrayUtils;
use Cloudinary\ClassUtils;
use Cloudinary\JsonUtils;
use Cloudinary\Transformation\BaseAction;
use Cloudinary\Transformation\CommonTransformation;
use Cloudinary\Transformation\ComponentInterface;
use Cloudinary\Transformation\Qualifier\BaseQualifier;
use Cloudinary\Transformation\Transformation;
use Cloudinary\Transformation\TransformationTrait;
use InvalidArgumentException;

/**
 * Class AssetTransformation
 *
 * A.K.A EagerTransformation
 *
 * @api
 */
class AssetTransformation implements ComponentInterface
{
    use TransformationTrait;

    protected const DELIMITER = '/'; # delimiter between transformation and extension

    /**
     * @var Transformation $transformation The asset transformation.
     */
    protected mixed $transformation;
    /**
     * @var mixed|string|null The file extension, A.K.A format
     */
    protected mixed $extension;

    /**
     * AssetTransformation constructor.
     *
     * @param CommonTransformation|array|string|null $transformation The asset transformation.
     * @param string|null                            $extension      The file extension.
     */
    public function __construct(
        CommonTransformation|array|string|null $transformation = null,
        ?string $extension = null
    ) {
        $this->transformation = ClassUtils::forceInstance($transformation, Transformation::class);

        if ($extension === null && is_array($transformation)) {
            $extension = ArrayUtils::get($transformation, 'format');
        }

        $this->extension($extension);
    }

    /**
     * Sets the file extension.
     *
     * @param ?string $extension The file extension.
     *
     * @return $this
     */
    public function extension(?string $extension): static
    {
        $this->extension = $extension;

        return $this;
    }

    /**
     * Creates a new asset from the provided string (URL).
     *
     * @param string $string The asset string (URL).
     *
     */
    public static function fromString(string $string): mixed
    {
        throw new BadMethodCallException('Not Implemented');
    }


    /**
     * Creates a new asset from the provided JSON.
     *
     * @param array|string $json The asset json. Can be an array or a JSON string.
     *
     */
    public static function fromJson(array|string $json): AssetTransformation
    {
        $new = new self('');

        $new->importJson($json);

        return $new;
    }


    /**
     * Creates a new asset transformation from the provided array of parameters.
     *
     * @param array $params The asset transformation parameters.
     *
     * @return static
     */
    public static function fromParams(array $params): self
    {
        return new self(Transformation::fromParams($params), ArrayUtils::get($params, 'format'));
    }


    /**
     * Imports data from the provided string (URL).
     *
     * @param string $string The asset string (URL).
     *
     */
    public function importString(string $string): mixed
    {
        throw new BadMethodCallException('Not Implemented');
    }


    /**
     * Imports data from the provided JSON.
     *
     * @param array|string $json The asset json. Can be an array or a JSON string.
     *
     */
    public function importJson(array|string $json): static
    {
        $json = JsonUtils::decode($json);

        if (! array_key_exists('asset_transformation', $json)
            || ! array_key_exists('transformation', $json['asset_transformation'])
        ) {
            throw new InvalidArgumentException('Invalid asset transformation JSON');
        }

        $assetJson = $json['asset'];

        $this->transformation = ArrayUtils::get($assetJson, 'transformation');
        $this->extension(ArrayUtils::get($assetJson, 'extension'));

        return $this;
    }

    /**
     * Adds (appends) a transformation in URL syntax to the current chain. A transformation is a set of instructions
     * for adjusting images or videos—such as resizing, cropping, applying filters, adding overlays, or optimizing
     * formats. For a detailed listing of all transformations, see the [Transformation
     * Reference](https://cloudinary.com/documentation/transformation_reference) or the [PHP
     * reference](https://cloudinary.com/documentation/sdks/php/php-transformation-builder/index.html).
     *
     * Appended transformation is nested.
     *
     * @param CommonTransformation $transformation The transformation to add.
     *
     */
    public function addTransformation(CommonTransformation $transformation): static
    {
        $this->transformation->addTransformation($transformation);

        return $this;
    }

    /**
     * Adds (chains) a transformation action.
     *
     * @param BaseAction|BaseQualifier|mixed $action The transformation action to add.
     *                                               If BaseQualifier is provided, it is wrapped with action.
     *
     */
    public function addAction(mixed $action): static
    {
        $this->transformation->addAction($action);

        return $this;
    }

    /**
     * Serializes to string.
     *
     * @return string
     */
    public function __toString()
    {
        return ArrayUtils::implodeFiltered(
            self::DELIMITER,
            array_values($this->jsonSerialize(true)['asset']),
            static function ($s) {
                /** @noinspection TypeUnsafeComparisonInspection */
                return $s != '' || $s === ''; // no extension, use original format
            }
        );
    }

    /**
     * Serializes to JSON.
     *
     * @param bool $includeEmptyKeys Whether to include empty keys.
     *
     */
    public function jsonSerialize(bool $includeEmptyKeys = false): array
    {
        $dataArr = [
            'transformation' => $this->transformation ? (string)$this->transformation : null, // FIXME: serialization
            'extension'      => $this->extension,
        ];

        if (! $includeEmptyKeys) {
            $dataArr = array_filter($dataArr);
        }

        return ['asset' => $dataArr];
    }
}
