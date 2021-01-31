<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Api\Admin;

use Cloudinary\Api\ApiClient;
use Cloudinary\Api\ApiResponse;
use Cloudinary\Api\ApiUtils;
use Cloudinary\Api\Exception\ApiError;
use Cloudinary\ArrayUtils;
use Cloudinary\Transformation\Transformation;

/**
 * Enables you to manage stored transformations.
 *
 * **Learn more**: <a
 * href=https://cloudinary.com/documentation/admin_api#transformations target="_blank">
 * Transformations method - Admin API</a>
 *
 * @property ApiClient $apiClient Defined in AdminApi class.
 *
 * @api
 */
trait TransformationsTrait
{
    /**
     * Lists stored transformations.
     *
     * @param array $options The optional parameters. See the
     *                       <a href=https://cloudinary.com/documentation/admin_api#get_transformations
     *                       target="_blank"> Admin API</a> documentation.
     *
     * @return ApiResponse
     *
     * @see https://cloudinary.com/documentation/admin_api#get_transformations
     */
    public function transformations($options = [])
    {
        $params = ArrayUtils::whitelist($options, ['named', 'next_cursor', 'max_results']);

        return $this->apiClient->get(ApiEndPoint::TRANSFORMATIONS, $params);
    }

    /**
     * Returns the details of a single transformation.
     *
     * @param string|array $transformation The transformation. Can be either a string or an array of parameters.
     *                                     For example: "w_150,h_100,c_fill" or array("width" => 150, "height" =>
     *                                     100,"crop" => "fill").
     * @param array        $options        The optional parameters. See the admin API documentation.
     *
     * @return ApiResponse
     *
     * @see https://cloudinary.com/documentation/admin_api#get_transformation_details
     */
    public function transformation($transformation, $options = [])
    {
        $params                   = ArrayUtils::whitelist($options, ['next_cursor', 'max_results']);
        $params['transformation'] = ApiUtils::serializeAssetTransformations($transformation);

        return $this->apiClient->get(ApiEndPoint::TRANSFORMATIONS, $params);
    }

    /**
     * Deletes the specified stored transformation.
     *
     * Deleting a transformation also deletes all the stored derived assets based on this transformation (up to 1000).
     * The method returns an error if there are more than 1000 derived assets based on this transformation.
     *
     * @param string|array $transformation The transformation to delete. Can be either a string or an array of
     *                                     parameters. For example:
     *                                     "w_150,h_100,c_fill" or ["width" => 150, "height" => 100,"crop" => "fill"].
     * @param array        $options        The optional parameters. See the
     *                                     <a href=https://cloudinary.com/documentation/admin_api#delete_transformation
     *                                     target="_blank"> Admin API</a> documentation.
     *
     * @return ApiResponse
     *
     * @throws ApiError
     *
     * @see  https://cloudinary.com/documentation/admin_api#delete_transformation
     */
    public function deleteTransformation($transformation, $options = [])
    {
        $params = ['transformation' => ApiUtils::serializeAssetTransformations($transformation)];
        if (isset($options['invalidate'])) {
            $params['invalidate'] = $options['invalidate'];
        }

        return $this->apiClient->delete(ApiEndPoint::TRANSFORMATIONS, $params);
    }

    /**
     * Updates the specified transformation.
     *
     * @param string|array $transformation The transformation. Can be either a string or an array of parameters.
     *                                     For example: "w_150,h_100,c_fill" or array("width" => 150, "height" =>
     *                                     100,"crop" => "fill").
     * @param array        $updates        The update parameters. See the
     *                                     <a href=https://cloudinary.com/documentation/admin_api#update_transformation
     *                                     target="_blank"> Admin API</a> documentation.
     *
     * @return ApiResponse
     *
     * @throws ApiError
     *
     * @see https://cloudinary.com/documentation/admin_api#update_transformation
     */
    public function updateTransformation($transformation, $updates = [])
    {
        $params = ArrayUtils::whitelist($updates, ['allowed_for_strict']);
        if (isset($updates['unsafe_update'])) {
            $params['unsafe_update'] = ApiUtils::serializeAssetTransformations($updates['unsafe_update']);
        }
        $params['transformation'] = ApiUtils::serializeAssetTransformations($transformation);

        return $this->apiClient->put(ApiEndPoint::TRANSFORMATIONS, $params);
    }

    /**
     * Creates a named transformation.
     *
     * @see https://cloudinary.com/documentation/admin_api#create_named_transformation
     *
     * @param string                      $name       The name of the transformation.
     * @param Transformation|string|array $definition The transformation. Can be either a defined Transformation,
     *                                                a string or an array of parameters. For example:
     *                                                "w_150,h_100,c_fill" or ["width" => 150, "height" => 100,
     *                                                "crop" => "fill"].
     *
     * @return ApiResponse
     */
    public function createTransformation($name, $definition)
    {
        $params = [
            'name'           => $name,
            'transformation' => ApiUtils::serializeAssetTransformations($definition),
        ];

        return $this->apiClient->postForm(ApiEndPoint::TRANSFORMATIONS, $params);
    }
}
