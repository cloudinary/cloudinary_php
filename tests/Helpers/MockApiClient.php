<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Test\Helpers;

use Cloudinary\Api\ApiClient;

/**
 * Class MockApiClient
 */
class MockApiClient extends ApiClient
{
    use MockApiClientTrait;
}
