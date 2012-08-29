<?php

namespace CS\DataGridBundle\Grid\Column;

use CS\DataGridBundle\Util\ArrayStack;

class ColumnCollection extends ArrayStack {
	
	public function add($name, $priority = 0)
	{
		$this->_data[$priority] = new Column($name);
	}
	
	public function remove($name)
	{
		if(count($this->_data) > 0)
		{
			foreach($this->_data as $key => $data)
			{
				if($data->getName() === $name)
				{
					unset($this->_data[$key]);
				}
			}
		}
	}
	
	public function move($name, $position)
	{
		if(count($this->_data) > 0)
		{
			foreach($this->_data as $key => $data)
			{
				if($data->getName() === $name)
				{
					unset($this->_data[$key]);
					$this->_data[$position] = $data;
				}
			}
		}
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
	}
}
