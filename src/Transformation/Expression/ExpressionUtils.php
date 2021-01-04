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

use Cloudinary\Utils;

/**
 * Class ExpressionUtils
 *
 * @internal
 */
class ExpressionUtils
{
    /**
     * @var array $OPERATORS A list of supported operators (arithmetic, logical, relational).
     */
    private static $OPERATORS;

    /**
     * @var array $PREDEFINED_VARIABLES A list of supported predefined variables.
     */
    private static $PREDEFINED_VARIABLES;

    /**
     * @var string $IF_REPLACE_RE Operators and predefined variables serialisation regular expression.
     *
     * @see ExpressionUtils::lazyInit
     */
    private static $IF_REPLACE_RE;

    /**
     * Normalizes expression from user representation to URL form.
     *
     * @param mixed $expression The expression to normalize.
     *
     * @return string|mixed The normalized expression.
     *
     * @uses translateIf()
     *
     */
    public static function normalize($expression)
    {
        if ($expression === null || self::isLiteral($expression)) {
            return $expression;
        }

        if (is_float($expression)) {
            return Utils::floatToString($expression);
        }

        self::lazyInit();

        $expression = preg_replace('/[ _]+/', '_', $expression);

        $expression = preg_replace_callback(
            self::$IF_REPLACE_RE,
            static function (array $source) {
                return self::translateIf($source);
            },
            $expression
        );

        return $expression;
    }

    /**
     * Initializes ExpressionUtils::$IF_REPLACE_RE static member lazily
     *
     * @see ExpressionUtils::$IF_REPLACE_RE
     */
    private static function lazyInit()
    {
        if (! empty(self::$IF_REPLACE_RE)) {
            return; //initialized last, if initialized, all the rest is OK
        }

        if (empty(self::$OPERATORS)) {
            self::$OPERATORS = Operator::friendlyRepresentations();
        }

        if (empty(self::$PREDEFINED_VARIABLES)) {
            self::$PREDEFINED_VARIABLES = PVar::getFriendlyRepresentations();
        }

        if (empty(self::$IF_REPLACE_RE)) {
            self::$IF_REPLACE_RE = '/((\$_*[^_]+)|(\|\||>=|<=|&&|!=|>|=|<|\/|\-|\+|\*|\^)(?=[ _])|(?<![\$\:])(' .
                                   implode('|', array_keys(self::$PREDEFINED_VARIABLES)) .
                                   '))/';
        }
    }

    /**
     * Serializes operators and predefined variables to url representation.
     *
     * @callable
     *
     * @param array $source The source to translate.
     *
     * @return mixed
     *
     * @see normalize()
     */
    protected static function translateIf($source)
    {
        if (isset(self::$OPERATORS[$source[0]])) {
            return self::$OPERATORS[$source[0]];
        }

        if (isset(self::$PREDEFINED_VARIABLES[$source[0]])) {
            return self::$PREDEFINED_VARIABLES[$source[0]];
        }

        return $source[0];
    }

    /**
     * Indicates whether $expression is a literal string (surrounded by '!')
     *
     * @param string $expression The expression
     *
     * @return bool
     */
    protected static function isLiteral($expression)
    {
        return (boolean)preg_match('/^!.+!$/', $expression);
    }
}
