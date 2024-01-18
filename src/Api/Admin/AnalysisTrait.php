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
use GuzzleHttp\Promise\PromiseInterface;

/**
 * Represents Analysis API methods.
 *
 * @property ApiClient $apiV2Client Defined in AdminApi class.
 *
 * @api
 */
trait AnalysisTrait
{
    /**
     * Analyzes an asset with the requested analysis type.
     *
     * @param string $inputType    The type of input for the asset to analyze ('uri').
     * @param string $analysisType The type of analysis to run ('google_tagging', 'captioning', 'fashion').
     * @param string $uri          The URI of the asset to analyze.
     *
     * @return ApiResponse
     *
     * @see AdminApi::analyzeAsync()
     *
     * @see https://cloudinary.com/documentation/media_analyzer_api_reference
     */
    public function analyze($inputType, $analysisType, $uri = null)
    {
        return $this->analyzeAsync($inputType, $analysisType, $uri)->wait();
    }

    /**
     * Analyzes an asset with the requested analysis type asynchronously.
     *
     * @param string $inputType    The type of input for the asset to analyze ('uri').
     * @param string $analysisType The type of analysis to run ('google_tagging', 'captioning', 'fashion').
     * @param string $uri          The URI of the asset to analyze.
     *
     * @return PromiseInterface
     *
     * @see https://cloudinary.com/documentation/media_analyzer_api_reference
     */
    public function analyzeAsync($inputType, $analysisType, $uri = null)
    {
        $endPoint = [ApiEndPoint::ANALYSIS, 'analyze'];

        $params = ['input_type' => $inputType, 'analysis_type' => $analysisType, 'uri' => $uri];

        return $this->apiV2Client->postJsonAsync($endPoint, $params);
    }
}
