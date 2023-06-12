<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Api\Search;

/**
 * Class SearchFoldersApi
 *
 * The Cloudinary API folders search method allows you fine control on filtering and retrieving information on all the
 * folders in your cloud with the help of query expressions in a Lucene-like query language.
 *
 * @api
 */
class SearchFoldersApi extends SearchApi
{
    /**
     * @internal
     */
    const FOLDERS = 'folders';

    /**
     * SearchFoldersApi constructor.
     *
     * @param mixed $configuration
     */
    public function __construct($configuration = null)
    {
        parent::__construct($configuration);

        $this->endpoint(self::FOLDERS);
    }
}
