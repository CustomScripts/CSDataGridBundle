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

class ArrayStack implements \ArrayAccess, \Countable, \Iterator
{
    /**
     * An array containing the data of the current stack
     *
     * @var array $elements
     */
    private $elements = array();

    /**
     * Internal pointer for elements
     *
     * @var integer
     */
    private $position = 0;

    /**
     * (non-phpdoc)
     *
     * @param mixed $offset
     */
    public function offsetExists($offset)
    {
        return isset($this->elements[$offset]);
    }

    /**
     * (non-phpdoc)
     *
     * @param mixed $offset
     */
    public function offsetGet($offset)
    {
        if (is_int($offset)) {
            return $this->elements[$offset];
        } elseif (is_string($offset)) {
            foreach ($this->elements as $value) {
                if ((string) $value === $offset) {
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
     * @param  mixed                              $offset
     * @param  mixed                              $value
     * @throws Exception\InvalidArgumentException
     */
    public function offsetSet($offset, $value)
    {
        if ($offset === null) {
            $offset = $this->elements ? max(array_keys($this->elements)) : 0;
        }

        if (!is_int($offset)) {
            throw new Exception\InvalidArgumentException('Offset must be a valid integer');
        }

        if ($this->offsetExists($offset)) {
            do {
                $offset++;
            } while ($this->offsetExists($offset));
        }

        if (is_array($value)) {
            foreach ($value as $key => $value) {
                $this[$key] = $value;
            }
        } else {

            $this->elements[$offset] = $value;
        }
    }

    /**
     * (non-phpdoc)
     *
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        if ($this->offsetExists($offset)) {
            unset($this->elements[$offset]);
        }
    }

    /**
     * Sorts the data by key
     *
     * @return ArrayStack
     */
    public function sort()
    {
        ksort($this->elements);

        return $this;
    }

    /**
     * (non-phpdoc)
     *
     * @return integer
     */
    public function count()
    {
        return count($this->elements);
    }

    /**
     * (non-phpdoc)
     */
    public function next()
    {
    	$this->position++;
    }

    /**
     * (non-phpdoc)
     *
     * @return mixed
     */
    public function current()
    {
        return $this->elements[$this->position];
    }

    /**
     * (non-phpdoc)
     *
     * @return mixed
     */
    public function key()
    {
        return $this->position;
    }

    /**
     * (non-phpdoc)
     *
     * @return boolean
     */
    public function valid()
    {
        return isset($this->elements[$this->position]);
    }

    /**
     * (non-phpdoc)
     */
    public function rewind()
    {
        $this->position = 0;
    }

    /**
     * Returns the first element
     *
     * @return Ambigous <NULL, unknown>
     */
    public function first()
    {
        return $this->elements[0];
    }

    /**
     * Returns the last element
     *
     * @return Ambigous <NULL, unknown>
     */
    public function last()
    {
        $key = max(array_keys($this->elements));

        return $this->offsetGet($key);
    }

    /**
     * Return an array with all the elements
     *
     * @return array
     */
    public function all()
    {
        return $this->elements;
    }

    /**
     * Moves an element to another position
     *
     * @param string  $label
     * @param integer $position
     * @return $this
     */
    public function move($label, $position)
    {
        if ($this->count() > 0) {
            foreach ($this->all() as $key => $data) {
                if (strtolower((string) $data) === strtolower($label)) {
                    $this->offsetUnset($key);
                    $this->offsetSet($position, $data);
                }
            }
        }

        return $this;
    }
}
