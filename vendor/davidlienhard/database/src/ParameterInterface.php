<?php
/**
 * contains parameter interface for Database
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

/**
 * interface to set parameters for a database query
 *
 * @author          David Lienhard <david@t-error.ch>
 * @version         1.0.0, 11.11.2020
 * @since           1.0.0, 11.11.2020, created
 * @copyright       tourasia
 */
interface ParameterInterface
{
    /**
     * sets the parameters of this object
     *
     * @author          David Lienhard <david.lienhard@tourasia.ch>
     * @version         1.0.0, 11.11.2020
     * @since           1.0.0, 11.11.2020, created
     * @copyright       tourasia
     * @param           string          $type           type of the parameter (can be i, s, d or d)
     * @param           mixed           $value          value of the parameter
     * @return          void
     */
    public function __construct(string $type, $value);

    /**
     * returns the type of this parameter
     *
     * @author          David Lienhard <david.lienhard@tourasia.ch>
     * @version         1.0.0, 11.11.2020
     * @since           1.0.0, 11.11.2020, created
     * @copyright       tourasia
     * @return          string
     */
    public function getType() : string;

    /**
     * returns the value of this parameter
     *
     * @author          David Lienhard <david.lienhard@tourasia.ch>
     * @version         1.0.0, 11.11.2020
     * @since           1.0.0, 11.11.2020, created
     * @copyright       tourasia
     * @return          string
     */
    public function getValue();
}
