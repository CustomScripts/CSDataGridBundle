<?php

namespace CS\DataGridBundle\Grid\Filter;

interface FilterCollectionInterface {

	public function addFilter($name, $value, \Closure $callback, $options = array());

}
