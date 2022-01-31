<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Test\Unit\Tag\Patterns;

use Cloudinary\Test\Unit\Tag\FormTagTest;

/**
 * Class FormTagPatterns
 */
final class FormTagPatterns
{
    /**
     * @return string
     */
    public static function getFormTagPattern()
    {
        $publicId = FormTagTest::IMAGE_NAME;

        $regExp = <<<TAG
#<form class="uploader" enctype="multipart\/form-data" method="POST" action="http[^']+\/v1_1\/test123\/auto\/upload">\R*
<input type="hidden" name="public_id" value="{$publicId}">\R*
<input type="hidden" name="timestamp" value="\d+">\R*
<input type="hidden" name="signature" value="[0-9a-f]+">\R*
<input type="hidden" name="api_key" value="\w+">\R*
<\/form>#
TAG;
        return str_replace(PHP_EOL, '', $regExp);
    }
}
