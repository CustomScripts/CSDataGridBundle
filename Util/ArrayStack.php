<?php

/*
 * This file is part of the CSDataGridBundle package.
 *
 * (c) Pierre du Plessis <info@customscripts.co.za>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CS\DataGridBundle\Util;

use CS\DataGridBundle\Exception;

class ArrayStack implements \ArrayAccess, \Countable, \Iterator {

	/**
	 * An array containing the data of the current stack
	 *
	 * @var array $_data
	 */
	protected $_data = array();

	/**
	 * (non-phpdoc)
	 *
	 * @param mixed $offset
	 */
	public function offsetExists($offset)
	{
		return isset($this->_data[$offset]);
	}

	/**
	 * (non-phpdoc)
	 *
	 * @param mixed $offset
	 */
	public function offsetGet($offset)
	{
	    if(is_int($offset))
	    {
	        return $this->_data[$offset];
	    } else if(is_string($offset))
	    {
	        foreach($this->_data as $value)
	        {
	            if((string) $value === $offset)
	            {
	                return $value;
	            }
	        }
	        return null;
	    } else {
	        return null;
	    }
	}

	/**
	 * (non-phpdoc)
	 *
	 * @param mixed $offset
	 * @param mixed $value
	 * @throws Exception\InvalidArgumentException
	 */
	public function offsetSet($offset, $value)
	{
		if($offset === null)
		{
			$offset = $this->_data ? max(array_keys($this->_data)) : 0;
		}

		if(!is_int($offset))
		{
			throw new Exception\InvalidArgumentException('Offset must be a valid integer');
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

	/**
	 * (non-phpdoc)
	 *
	 * @param mixed $offset
	 */
	public function offsetUnset($offset)
	{
		if($this->offsetExists($offset))
		{
			unset($this->_data[$offset]);
		}
	}

	/**
	 * Sorts the data by key
	 *
	 * @return ArrayStack
	 */
	public function sort()
	{
		ksort($this->_data);
		return $this;
	}

	/**
	 * (non-phpdoc)
	 *
	 * @return integer
	 */
	public function count()
	{
		return count($this->_data);
	}

	/**
	 * (non-phpdoc)
	 */
	public function next()
	{
		return next($this->_data);
	}

	/**
	 * (non-phpdoc)
	 *
	 * @return mixed
	 */
	public function current()
	{
		return $this->_data[$this->key()];
	}

	/**
	 * (non-phpdoc)
	 *
	 * @return mixed
	 */
	public function key()
	{
		return key($this->_data);
	}

	/**
	 * (non-phpdoc)
	 *
	 * @return boolean
	 */
	public function valid()
	{
		$key = $this->key();

		return isset($this->_data[$key]);
	}

	/**
	 * (non-phpdoc)
	 */
	public function rewind()
	{
		reset($this->_data);
	}
}
