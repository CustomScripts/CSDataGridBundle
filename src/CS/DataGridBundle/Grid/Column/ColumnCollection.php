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

use CS\DataGridBundle\Util\ArrayStack;

class ColumnCollection extends ArrayStack
{
    /**
     * Adds a new Column to the collection
     * @param string  $label
     * @param integer $priority
     */
    public function add($label, $priority = 0)
    {
        $this->_data[$priority] = new Column($label);

        return $this;
    }

    /**
     * Removes a column from from the grid
     *
     * @param mixed $columns
     */
    public function remove($columns)
    {
        $columns = is_array($columns) ? $columns : arraya($columns);

        if (count($this->_data) > 0) {
            foreach ($this->_data as $key => $data) {
                foreach ($columns as $label) {
                    if (strtolower($data->getLabel()) === strtolower($label)) {
                        unset($this->_data[$key]);
                    }
                }
            }
        }

        return $this;
    }

    /**
     * Moves a column to another position
     *
     * @param  string           $label
     * @param  integer          $position
     * @return ColumnCollection
     */
    public function move($label, $position)
    {
        if (count($this->_data) > 0) {
            foreach ($this->_data as $key => $data) {
                if (strtolower($data->getLabel()) === strtolower($label)) {
                    unset($this->_data[$key]);
                    $this->_data[$position] = $data;
                }
            }
        }

        return $this;
    }

    /**
     * Adds columns recursively
     *
     * @param  array            $data
     * @return ColumnCollection
     */
    public function addRecursive($data = array())
    {
        if (is_array($data) && !empty($data)) {
            foreach ($data as $key => $column) {
                $this->add($column, $key);
            }
        }

        return $this;
    }
}
