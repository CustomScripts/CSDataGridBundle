<?php

/*
 * This file is part of the CSDataGridBundle package.
 *
 * (c) Pierre du Plessis <info@customscripts.co.za>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CS\DataGridBundle\Grid\Column;

Interface ColumnInterface
{

    public function __construct($name, $callback = null);

    /**
     * Verify if the column has a callback and that it is valid
     */
    public function hasCallback();

    /**
     * Calls the callback on the column
     *
     * @param mixed $value
     */
    public function callback($value);

    /**
     * Sets the callback on the current column
     *
     * @param mixed $callback
     */
    public function setCallback($callback);

    /**
     * Return the label for the current column
     *
     * @return string
     */
    public function getLabel();

    /**
     * Returns the name of the column
     *
     * @return string
     */
    public function getName();

    /**
     * Sets the label for the current column
     *
     * @param string $label
     */
    public function setLabel($label);

    /**
     * Returns the current column as a string
     *
     * @return string
     */
    public function __toString();
}
