<?php

/*
 * This file is part of the CSDataGridBundle package.
 *
 * (c) Pierre du Plessis <info@customscripts.co.za>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CS\DataGridBundle\Grid\Action;

use CS\DataGridBundle\Util\ArrayStack;
use CS\DataGridBundle\Grid\Action\Action;

class ActionCollection extends ArrayStack
{
    /**
     * Adds a new action to the collection
     *
     * @param  Action           $action
     * @return ActionCollection
     */
    public function add(Action $action)
    {
        $this[] = $action;

        return $this;
    }
}
