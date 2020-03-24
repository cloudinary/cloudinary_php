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

use Cloudinary\ClassUtils;

/**
 * Class EffectAction
 */
class EffectAction extends Action
{
    use EffectActionTrait;

    /**
     * EffectAction constructor.
     *
     * @param       $effect
     * @param mixed ...$args
     */
    public function __construct($effect, ...$args)
    {
        $effect = ClassUtils::verifyInstance($effect, EffectParam::class);

        if (! empty($args)) {
            $effect->add(...$args);
        }

        parent::__construct($effect);
    }
}
