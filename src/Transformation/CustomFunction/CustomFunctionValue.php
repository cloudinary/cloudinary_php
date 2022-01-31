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
 * Class CustomFunctionValue
 */
class CustomFunctionValue extends QualifierMultiValue
{
    /**
     * @var array $argumentOrder The order of the arguments.
     */
    protected $argumentOrder = ['preprocess', 'type', 'source'];

    /**
     * CustomFunctionValue constructor.
     *
     * @param null $source
     * @param null $type
     * @param null $preprocess
     */
    public function __construct($source = null, $type = null, $preprocess = null)
    {
        parent::__construct();

        $this->setSimpleValue('source', $source);
        $this->setSimpleValue('type', $type);
        $this->setSimpleValue('preprocess', $preprocess);
    }
}
