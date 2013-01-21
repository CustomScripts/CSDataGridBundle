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

use CS\DataGridBundle\Grid\Collection;

class ColumnCollection extends Collection
{
    /**
     * Adds a new Column to the collection
     *
     * @param string  $label
     * @param integer $priority
     */
    public function add($label, $priority = 0)
    {
        // TODO: get column  class from configuration
        $column = new Column ($label);

        $this->offsetSet($priority, $column);

        return $this;
    }
}
