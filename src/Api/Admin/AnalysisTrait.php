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
     * @param string      $inputType    The type of input for the asset to analyze ('uri').
     * @param string      $analysisType The type of analysis to run ('google_tagging', 'captioning', 'fashion').
     * @param string|null $uri          The URI of the asset to analyze.
     * @param array|null  $parameters   Additional parameters.
     *
     *
     *
     * @see AdminApi::analyzeAsync()
     *
     * @see https://cloudinary.com/documentation/media_analyzer_api_reference
     */
    public function analyze(
        string $inputType,
        string $analysisType,
        ?string $uri = null,
        ?array $parameters = null
    ): ApiResponse {
        return $this->analyzeAsync($inputType, $analysisType, $uri, $parameters)->wait();
    }

    /**
     * Analyzes an asset with the requested analysis type asynchronously.
     *
     * @param string      $inputType    The type of input for the asset to analyze ('uri').
     * @param string      $analysisType The type of analysis to run ('google_tagging', 'captioning', 'fashion').
     * @param string|null $uri          The URI of the asset to analyze.
     * @param array|null  $parameters   Additional parameters.
     *
     * @see https://cloudinary.com/documentation/media_analyzer_api_reference
     */
    public function analyzeAsync(
        string $inputType,
        string $analysisType,
        ?string $uri = null,
        ?array $parameters = null
    ): PromiseInterface {
        $endPoint = [ApiEndPoint::ANALYSIS, 'analyze', $inputType];

        $params = ['analysis_type' => $analysisType, 'uri' => $uri, 'parameters' => $parameters];

        return $this->apiV2Client->postJsonAsync($endPoint, $params);
    }
}
