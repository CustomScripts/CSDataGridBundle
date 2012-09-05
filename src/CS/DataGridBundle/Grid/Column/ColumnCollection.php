<?php

namespace CS\DataGridBundle\Grid\Column;

use CS\DataGridBundle\Util\ArrayStack;

class ColumnCollection extends ArrayStack
{
    /**
     * Adds a new Column to the collection
     * @param string $label
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

		if(count($this->_data) > 0)
		{
			foreach($this->_data as $key => $data)
			{
			    foreach($columns as $label)
			    {
				    if(strtolower($data->getLabel()) === strtolower($label))
				    {
    					unset($this->_data[$key]);
    				}
			    }
			}
		}

		return $this;
	}

	public function move($label, $position)
	{
		if(count($this->_data) > 0)
		{
			foreach($this->_data as $key => $data)
			{
				if(strtolower($data->getLabel()) === strtolower($label))
				{
					unset($this->_data[$key]);
					$this->_data[$position] = $data;
				}
			}
		}

		return $this;
	}

	public function addRecursive($data = array())
	{
		if(is_array($data) && !empty($data))
		{
			foreach($data as $key => $column)
			{
				$this->add($column, $key);
			}
		}

		return $this;
	}
}
