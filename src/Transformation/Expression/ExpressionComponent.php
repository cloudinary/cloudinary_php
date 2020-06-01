<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Transformation\Expression;

use Cloudinary\ArrayUtils;

/**
 * Class ExpressionComponent
 */
class ExpressionComponent extends BaseExpressionComponent
{
    /**
     * @var string EXPRESSION_DELIMITER The delimiter between expression components.
     */
    const EXPRESSION_DELIMITER = '_';

    /**
     * @var mixed $leftOperand The left operand.
     */
    protected $leftOperand;

    /**
     * @var string $operator The operator
     */
    protected $operator;

    /**
     * @var mixed The right operand.
     */
    protected $rightOperand;

    /**
     * ExpressionComponent constructor.
     *
     * @param $leftOperand
     * @param $operator
     * @param $rightOperand
     */
    public function __construct($leftOperand, $operator = null, $rightOperand = null)
    {
        parent::__construct();

        $this->leftOperand = $leftOperand;
        $this->setOperator($operator);
        $this->setRightOperand($rightOperand);
    }

    /**
     * Sets the expression operator.
     *
     * @param string $operator The operator
     */
    public function setOperator($operator)
    {
        $this->operator = $operator;
    }

    /**
     * Sets the right operand.
     *
     * @param $rightOperand
     */
    public function setRightOperand($rightOperand)
    {
        $this->rightOperand = $rightOperand;
    }

    /**
     * Serializes to string.
     *
     * @return string
     */
    public function __toString()
    {
        return ArrayUtils::implodeFiltered(
            self::EXPRESSION_DELIMITER,
            [$this->leftOperand, $this->operator, $this->rightOperand]
        );
    }

    /**
     * Serializes to json.
     *
     * @return mixed
     */
    public function jsonSerialize()
    {
        return [
            'left_operand' => $this->leftOperand,
            'operator' => $this->operator,
            'right_operand' => $this->rightOperand,
        ];
    }
}
