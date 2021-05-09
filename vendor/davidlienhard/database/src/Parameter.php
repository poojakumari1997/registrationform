<?php
/**
 * contains parameter class for Database
 *
 * @package         tourBase
 * @subpackage      Core\Database
 * @author          David Lienhard <david@t-error.ch>
 * @version         1.0.0, 11.11.2020
 * @since           1.0.0, 11.11.2020, created
 * @copyright       tourasia
 */

declare(strict_types=1);

namespace DavidLienhard\Database;

use DavidLienhard\Database\ParameterInterface;

/**
 * class to set parameters for a database query
 *
 * @author          David Lienhard <david@t-error.ch>
 * @version         1.0.0, 11.11.2020
 * @since           1.0.0, 11.11.2020, created
 * @copyright       tourasia
 */
class Parameter implements ParameterInterface
{
    /**
     * type of the parameter
     * @var     string      $type
     */
    private $type;

    /**
     * value of the parameter
     * @var     mixed       $value
     */
    private $value;

    /**
     * sets the parameters of this object
     *
     * @author          David Lienhard <david.lienhard@tourasia.ch>
     * @version         1.0.0, 11.11.2020
     * @since           1.0.0, 11.11.2020, created
     * @copyright       tourasia
     * @param           string          $type           type of the parameter (can be i, s, d or b)
     * @param           mixed           $value          value of the parameter
     * @return          void
     * @throws          \InvalidArgumentException       if given type is invalid
     * @uses            self::$type
     * @uses            self::$value
     */
    public function __construct(string $type, $value)
    {
        if (!in_array($type, [ "i", "s", "d", "b"], true)) {
            throw new \InvalidArgumentException("type must be i, s, d or b. '".$type."' given");
        }

        $this->type = $type;
        $this->value = $value;
    }

    /**
     * returns the type of this parameter
     *
     * @author          David Lienhard <david.lienhard@tourasia.ch>
     * @version         1.0.0, 11.11.2020
     * @since           1.0.0, 11.11.2020, created
     * @copyright       tourasia
     * @return          string
     * @uses            self::$type
     */
    public function getType() : string
    {
        return $this->type;
    }

    /**
     * returns the value of this parameter
     *
     * @author          David Lienhard <david.lienhard@tourasia.ch>
     * @version         1.0.0, 11.11.2020
     * @since           1.0.0, 11.11.2020, created
     * @copyright       tourasia
     * @return          mixed
     * @uses            self::$value
     */
    public function getValue()
    {
        return $this->value;
    }
}
