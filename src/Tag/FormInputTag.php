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

/**
 * Class FormInputTag
 *
 * Generates <input type="hidden" name="..." value="...">
 *
 * @internal
 */
class FormInputTag extends BaseTag
{
    const NAME    = 'input';
    const IS_VOID = true;

    /**
     * @var array $attributes An array of tag attributes.
     */
    protected $attributes = [
        'type' => 'hidden',
    ];

    /**
     * FormInputTag constructor.
     *
     * @param string $name  The name of the input tag.
     * @param mixed  $value The value of the input tag.
     */
    public function __construct($name, $value)
    {
        parent::__construct();

        $this->setAttribute('name', $name);
        $this->setAttribute('value', $value);
    }
}
