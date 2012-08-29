<?php

namespace CS\DataGridBundle\Util;

class ArrayStack implements \ArrayAccess, \Countable, \Iterator {
	
	protected $_data;
	
	public function offsetExists($offset)
	{
		return isset($this->_data[$offset]);
	}

	public function offsetGet($offset)
	{
		return $this->_data[$offset];
	}

	public function offsetSet($offset, $value)
	{
		if($offset === null)
		{
			$offset = $this->_data ? max(array_keys($this->_data)) : 0;
		}
		
		if(!is_int($offset))
		{
			throw new Exception('Offset must be a valid integer');
		}
		
		if($this->offsetExists($offset))
		{
			do {
				$offset++;
			} while($this->offsetExists($offset));
		}
		
		if(is_array($value))
		{
			foreach($value as $key => $value)
			{
				$this[$key] = $value;
			}
		} else {
		
			$this->_data[$offset] = $value;
		}
	}

	public function offsetUnset($offset)
	{
		if($this->offsetExists($offset))
		{
			unset($this->_data[$offset]);
		}
	}
	
	public function sort()
	{
		ksort($this->_data);
		return $this;
	}
	
	public function count()
	{
		return count($this->_data);
	}
	
	public function next()
	{
		next($this->_data);
	}

	public function current()
	{
		return $this->_data[$this->key()];
	}

	public function key()
	{
		return key($this->_data);
	}

	public function valid()
	{
		$key = $this->key();
		
		return isset($this->_data[$key]);
	}

	public function rewind()
	{
		reset($this->_data);
	}
}
