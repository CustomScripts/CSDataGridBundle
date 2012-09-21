<?php

/*
 * This file is part of the CSDataGridBundle package.
 *
 * (c) Pierre du Plessis <info@customscripts.co.za>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CS\DataGridBundle\Grid\Filter;

use CS\DataGridBundle\Grid\Filter\Filter;

class FilterCollection implements \Countable, \Iterator {

	private $filters = array();

	public function addFilter()
	{

		$this->filters[] = call_user_func_array(array('Filter', 'construct'), func_get_args());
	}

	public function count()
	{
		return count($this->filters);
	}

	public function current()
	{
		return current($this->filters);
	}

	public function next()
	{
		return next($this->filters);
	}

	public function reset()
	{
		return reset($this->filters);
	}

	public function key()
	{
		return key($this->filters);
	}

	public function valid()
	{
		$key = $this->key();

		return ($key !== null && $key !== false && array_key_exists($key, $this->filters));
	}

	public function rewind()
	{
		return reset($this->filters);
	}
}
