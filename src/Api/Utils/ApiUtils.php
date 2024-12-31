<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Api;

use Cloudinary\ArrayUtils;
use Cloudinary\Asset\AssetTransformation;
use Cloudinary\ClassUtils;
use Cloudinary\Configuration\CloudConfig;
use Cloudinary\Transformation\Transformation;
use Cloudinary\Utils;

/**
 * Class ApiUtils
 *
 * @api
 */
class ApiUtils
{
    public const HEADERS_OUTER_DELIMITER         = "\n";
    public const HEADERS_INNER_DELIMITER         = ':';
    public const API_PARAM_DELIMITER             = ',';
    public const CONTEXT_OUTER_DELIMITER         = '|';
    public const CONTEXT_INNER_DELIMITER         = '=';
    public const ARRAY_OF_ARRAYS_DELIMITER       = '|';
    public const ASSET_TRANSFORMATIONS_DELIMITER = '|';
    public const QUERY_STRING_INNER_DELIMITER    = '=';
    public const QUERY_STRING_OUTER_DELIMITER    = '&';

    /**
     * Serializes a simple parameter, which can be a serializable object.
     *
     * In case the parameter is an array, it is joined using ApiUtils::API_PARAM_DELIMITER.
     *
     * @param string|array|mixed $parameter The parameter to serialize.
     *
     * @return string|null The resulting serialized parameter.
     *
     * @internal
     *
     * @see ApiUtils::API_PARAM_DELIMITER
     */
    public static function serializeSimpleApiParam(mixed $parameter): ?string
    {
        return self::serializeParameter($parameter, self::API_PARAM_DELIMITER);
    }

    /**
     * Serializes 'headers' upload API parameter.
     *
     *
     * @return string|null The resulting serialized parameter.
     *
     * @internal
     */
    public static function serializeHeaders($headers): ?string
    {
        return self::serializeParameter($headers, self::HEADERS_OUTER_DELIMITER, self::HEADERS_INNER_DELIMITER);
    }

    /**
     * Serializes 'context' and 'metadata' upload API parameters.
     *
     * @return ?string The resulting serialized parameter.
     *
     * @internal
     */
    public static function serializeContext(mixed $context): ?string
    {
        if (is_array($context)) {
            $context = array_map('\Cloudinary\Api\ApiUtils::serializeJson', $context);
        }

        return self::serializeParameter($context, self::CONTEXT_OUTER_DELIMITER, self::CONTEXT_INNER_DELIMITER);
    }

    /**
     * Serializes (encodes) json object to string.
     *
     * @param mixed $jsonParam Json object
     *
     * @return string|null The resulting serialized parameter.
     *
     * @internal
     */
    public static function serializeJson(mixed $jsonParam): ?string
    {
        if ($jsonParam === null) {
            return null;
        }

        // Avoid extra double quotes around strings.
        if (is_string($jsonParam) || is_object($jsonParam) && method_exists($jsonParam, '__toString')) {
            return $jsonParam;
        }

        return json_encode($jsonParam); //TODO: serialize dates
    }

    /**
     * Commonly used util for building Cloudinary API params.
     *
     * @param null $outerDelimiter
     * @param null $innerDelimiter
     *
     * @return string|null The resulting serialized parameter.
     *
     * @internal
     */
    public static function serializeParameter($parameter, $outerDelimiter = null, $innerDelimiter = null): ?string
    {
        if (! is_array($parameter)) {
            return $parameter === null ? null : (string)$parameter; // can be a serializable object
        }

        return ArrayUtils::safeImplodeAssoc($parameter, $outerDelimiter, $innerDelimiter);
    }

    /**
     * Serializes array of nested array parameters.
     *
     * @param mixed $arrayOfArrays The input array.
     *
     * @return ?string The resulting serialized parameter.
     *
     * @internal
     */
    public static function serializeArrayOfArrays(mixed $arrayOfArrays): ?string
    {
        $array = ArrayUtils::build($arrayOfArrays);

        if (! count($array)) {
            return null;
        }

        if (! is_array($array[0])) {
            return self::serializeSimpleApiParam($array);
        }

        $array = array_map(self::class . '::serializeSimpleApiParam', $array);


        return self::serializeParameter($array, self::ARRAY_OF_ARRAYS_DELIMITER);
    }

    /**
     * Serializes asset transformations.
     *
     *
     * @return string The resulting serialized parameter.
     *
     * @internal
     */
    public static function serializeAssetTransformations(array|string|null $transformations): string
    {
        $serializedTransformations = [];

        foreach (ArrayUtils::build($transformations) as $singleTransformation) {
            $serializedTransformations[] = (string)ClassUtils::verifyInstance(
                $singleTransformation,
                AssetTransformation::class
            );
        }

        return ArrayUtils::implodeFiltered(self::ASSET_TRANSFORMATIONS_DELIMITER, $serializedTransformations);
    }

    /**
     * Serializes incoming transformation.
     *
     *
     * @return string The resulting serialized parameter.
     *
     * @internal
     */
    public static function serializeTransformation(array|string $transformationParams): string
    {
        return (string)ClassUtils::forceInstance($transformationParams, Transformation::class);
    }

    /**
     * Finalizes Upload API parameters.
     *
     * Normalizes parameters values, removes empty, adds timestamp.
     *
     * @param array $params Parameters to finalize.
     *
     * @return array Resulting parameters.
     *
     * @internal
     *
     */
    public static function finalizeUploadApiParams(array $params): array
    {
        $additionalParams = [
            'timestamp' => Utils::unixTimeNow(),
        ];

        $params = array_merge($params, $additionalParams);

        ArrayUtils::convertBoolToIntStrings($params);

        return ArrayUtils::safeFilter($params);
    }

    /**
     * Serializes responsive breakpoints.
     *
     *
     * @return false|string|null
     *
     * @internal
     */
    public static function serializeResponsiveBreakpoints(?array $breakpoints): bool|string|null
    {
        if (! $breakpoints) {
            return null;
        }
        $breakpointsParams = [];
        foreach (ArrayUtils::build($breakpoints) as $breakpointSettings) {
            if ($breakpointSettings) {
                $transformation = ArrayUtils::get($breakpointSettings, 'transformation');
                if ($transformation) {
                    $breakpointSettings['transformation'] = self::serializeAssetTransformations($transformation);
                }
                $breakpointsParams[] = $breakpointSettings;
            }
        }

        return json_encode($breakpointsParams);
    }

    /**
     *
     *
     * @internal
     */
    public static function serializeQueryParams(array $parameters = []): string
    {
        return ArrayUtils::implodeAssoc(
            $parameters,
            self::QUERY_STRING_OUTER_DELIMITER,
            self::QUERY_STRING_INNER_DELIMITER
        );
    }

    /**
     * Signs parameters of the request.
     *
     * @param array  $parameters         Parameters to sign.
     * @param string $secret             The API secret of the cloud.
     * @param string $signatureAlgorithm Signature algorithm
     *
     * @return string The signature.
     *
     * @api
     */
    public static function signParameters(
        array $parameters,
        string $secret,
        string $signatureAlgorithm = Utils::ALGO_SHA1
    ): string {
        $parameters = array_map(self::class . '::serializeSimpleApiParam', $parameters);

        ksort($parameters);

        $signatureContent = self::serializeQueryParams($parameters);

        return Utils::sign($signatureContent, $secret, false, $signatureAlgorithm);
    }

    /**
     * Adds signature and api_key to $parameters.
     *
     *
     * @internal
     */
    public static function signRequest(?array &$parameters, CloudConfig $cloudConfig): void
    {
        $parameters['signature'] = self::signParameters(
            $parameters,
            $cloudConfig->apiSecret,
            $cloudConfig->signatureAlgorithm
        );
        $parameters['api_key']   = $cloudConfig->apiKey;
    }
}
