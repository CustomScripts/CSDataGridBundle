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

use CS\DataGridBundle\Util\ArrayStack;
use CS\DataGridBundle\Exception;

class Collection extends ArrayStack
{
	/**
	 * Adds a new element to the collection
	 *
	 * @param string  $label
	 * @param integer $priority
	 */
	public function add($label, $priority = 0)
	{
		$this->offsetSet($priority, $label);

		return $this;
	}

	/**
	 * Gets an element by name
	 *
	 * @param string $label
	 * @throws Exception\InvalidElementException
	 * @return mixed
	 */
	public function get($label)
	{
		foreach($this->all() as $key => $value)
		{
			if((string) $value === $label)
			{
				return $value;
			}
		}

		throw new Exception\InvalidElementException(sprintf("The element %s does not exist", $label));
	}

	/**
	 * Checks if an element exists
	 *
	 * @param string $label
	 * @return bool
	 */
	public function has($label)
	{
		try {
			$element = $this->get($label) !== false;
		} catch(Exception\InvalidElementException $e) {
			return false;
		}

		return true;
	}

	/**
	 * Removes an element from the collection
	 *
	 * @param array|string $label
	 */
	public function remove($label)
	{
		$labels = is_array($label) ? $label : (array) $label;

		unset($label);

		if ($this->count() > 0) {
			foreach ($this->all() as $key => $data) {
				foreach ($labels as $label) {
					if (strtolower((string) $data) === strtolower($label)) {
						$this->offsetUnset($key);
					}
				}
			}
		}

		return $this;
	}

	/**
	 * Adds columns recursively
	 *
	 * @param  array            $data
	 * @return $this
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