<?php

namespace CS\DataGridBundle\Grid\Action;

use CS\DataGridBundle\Util\ArrayStack;
use CS\DataGridBundle\Grid\Action\Action;

class ActionCollection extends ArrayStack
{
    public function add(Action $action)
    {
        $this->_data[] = $action;
    }
}