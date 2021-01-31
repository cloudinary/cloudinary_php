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
    const MAIN_QUALIFIER = EffectQualifier::class;

    use EffectActionTrait;

    /**
     * EffectAction constructor.
     *
     * @param       $effect
     * @param mixed ...$args
     */
    public function __construct($effect, ...$args)
    {
        parent::__construct(ClassUtils::verifyInstance($effect, EffectQualifier::class, null, ...$args));
    }
}
