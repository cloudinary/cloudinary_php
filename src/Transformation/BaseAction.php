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

use Cloudinary\ArrayUtils;
use Cloudinary\JsonUtils;
use Cloudinary\StringUtils;
use InvalidArgumentException;

/**
 * Class BaseAction
 *
 * @api
 */
abstract class BaseAction extends BaseComponent
{
    /**
     * @var array $parameters The components (parameters) of the action.
     */
    protected $parameters = [];

    /**
     * @var array $flags The flags of the action.
     */
    protected $flags = [];

    /**
     * @var string $genericAction The generic (raw) action.
     */
    protected $genericAction;

    /**
     * Action constructor.
     *
     * @param mixed ...$parameters
     */
    public function __construct(...$parameters)
    {
        parent::__construct();

        $this->addParameters(...$parameters);
    }

    /**
     * Adds the parameter to the action.
     *
     * @param BaseComponent|null $parameter The parameter to add.
     *
     * @return $this
     */
    public function addParameter(BaseComponent $parameter = null)
    {
        ArrayUtils::addNonEmpty($this->parameters, $parameter ? $parameter->getFullName() : null, $parameter);

        return $this;
    }

    /**
     * Adds parameters to the action.
     *
     * @param array $parameters The parameters to add.
     *
     * @return $this
     */
    public function addParameters(...$parameters)
    {
        if (count($parameters) === 1 && is_string($parameters[0])) {
            $this->setGenericAction($parameters[0]);
        } else {
            foreach ($parameters as $parameter) {
                $this->addParameter($parameter);
            }
        }

        return $this;
    }

    /**
     * Adds (sets) generic (raw) action.
     *
     * @param string $action The generic action string.
     *
     * @return static
     */
    public function setGenericAction($action)
    {
        if (StringUtils::contains($action, '/')) {
            throw new InvalidArgumentException('A single generic action must be supplied');
        }

        $this->genericAction = $action;

        return $this;
    }

    /**
     * Sets the flag.
     *
     * @param FlagParameter $flag  The flag to set.
     * @param bool          $set   Indicates whether to set(true) or unset(false) the flag instead.
     *                             (Used for avoiding if conditions all over the code)
     *
     * @return $this
     */
    public function setFlag(FlagParameter $flag, $set = true)
    {
        if ($set === false) {
            return $this->unsetFlag($flag);
        }

        ArrayUtils::addNonEmpty($this->flags, $flag->getFlagName(), $flag);

        return $this;
    }

    /**
     * Removes the flag.
     *
     * @param FlagParameter $flag The flag to unset.
     *
     * @return $this
     */
    public function unsetFlag(FlagParameter $flag)
    {
        unset($this->flags[$flag->getFlagName()]);

        return $this;
    }

    /**
     * Serializes to json.
     *
     * @return mixed
     */
    public function jsonSerialize()
    {
        $jsonArray = [];

        ArrayUtils::addNonEmpty($jsonArray, 'parameters', self::jsonSerializeParams($this->parameters));
        ArrayUtils::addNonEmpty($jsonArray, 'generic', $this->genericAction);
        ArrayUtils::addNonEmpty($jsonArray, 'flags', self::jsonSerializeParams($this->flags));

        if (empty($jsonArray)) {
            return [];
        }

        ArrayUtils::prependAssoc($jsonArray, 'name', $this->getFullName());

        return $jsonArray;
    }

    /**
     * Collects and flattens action parameters.
     *
     * @return array A flat array of parameters
     *
     * @internal
     */
    public function getStringParameters()
    {
        $flatParameters = [];
        foreach ($this->parameters as $parameter) {
            $flatParameters = ArrayUtils::mergeNonEmpty($flatParameters, $parameter->getStringParameters());
        }

        return $flatParameters;
    }

    /**
     * Serializes to Cloudinary URL format
     *
     * @return string
     */
    public function __toString()
    {
        $flatParameters = $this->getStringParameters();

        $allParameters = array_merge($flatParameters, [self::serializeFlags($this->flags)], [$this->genericAction]);

        sort($allParameters);

        return ArrayUtils::implodeActionParams(...$allParameters);
    }

    /**
     * Serializes and merges flags.
     *
     * @param array $flags The flags to serialize
     *
     * @return string
     */
    protected static function serializeFlags($flags)
    {
        ksort($flags);

        $result = array_map(
            static function (FlagParameter $flag) {
                return ArrayUtils::implodeParamValues(
                    $flag->getFlagName(),
                    rawurlencode(StringUtils::encodeDot($flag->getValue()))
                );
            },
            array_values($flags)
        );

        return (string)new FlagParameter(ArrayUtils::implodeFiltered('.', $result));
    }

    /**
     * Serializes parameters to JSON.
     *
     * @param array $params The parameters to serialize.
     *
     * @return array Serialized parameters.
     */
    protected static function jsonSerializeParams($params)
    {
        $result = array_map(
            static function ($param) {
                return JsonUtils::jsonSerialize($param);
            },
            array_values($params)
        );

        return ArrayUtils::safeFilter($result);
    }
}
