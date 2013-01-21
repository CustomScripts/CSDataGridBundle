<?php

/*
 * This file is part of the CSDataGridBundle package.
 *
 * (c) Pierre du Plessis <info@customscripts.co.za>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CS\DataGridBundle\Grid;

use CS\DataGridBundle\Grid\Action\ActionCollection;
use CS\DataGridBundle\Grid\Column\ColumnCollection;

interface GridInterface
{
    public function getSource();

    public function getActions(ActionCollection $collection);

    public function getColumns(ColumnCollection $collection);
}
