<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Transformation;

/**
 * Defines the page extraction action.
 */
class Page extends BasePageAction
{
    /**
     * @var string MAIN_PARAMETER Represents the main qualifier of the action. (some actions do not have main qualifier)
     */
    const MAIN_QUALIFIER = PageQualifier::class;

    use PageNumberTrait;
    use PageRangeTrait;
    use PageAllTrait;
}
