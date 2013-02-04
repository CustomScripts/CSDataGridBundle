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

use Symfony\Component\Form\Exception;
use Symfony\Component\PropertyAccess\PropertyAccess;
use CS\DataGridBundle\Grid\Grid;

class Row implements \ArrayAccess
{
    /**
     *
     * @var mixed $item
     */
    protected $item;

    /**
     * An instance of the grid
     *
     * @var GridInterface $grid
     */
    protected $grid;

    protected $accessor;

    /**
     * (non-phpdoc)
     *
     * @param mixed $item
     */
    public function __construct($item = null)
    {
    	$this->accessor = PropertyAccess::getPropertyAccessor();

        $this->setItem($item);
    }

    /**
     * Sets the value of $item
     *
     * @param  mixed $item
     * @return Row
     */
    public function setItem($item)
    {
        $this->item = $item;

        return $this;
    }

    /**
     * @return mixed;
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * Sets the instance of the grid
     *
     * @param  GridInterface $grid
     * @return Row
     */
    public function setGrid(Grid $grid)
    {
        $this->grid = $grid;

        return $this;
    }

    /**
     * Gets the instance of the grid
     *
     * @return GridInterface;
     */
    public function getGrid()
    {
        return $this->grid;
    }

    /**
     * Gets a property value in the current row
     *
     * @param  string $property
     * @return mixed
     */
    protected function getValue($property)
    {
        $object = $this->getItem();

        return $this->accessor->getValue($object, $property);
    }

    /**
     * Converts a strng to CamelCase
     *
     * @param  string $property
     * @return string
     */
    protected function camelize($property)
    {
        return preg_replace_callback('/(^|_|\.)+(.)/', function ($match) { return ('.' === $match[1] ? '_' : '').strtoupper($match[2]); }, $property);
    }

    /**
     * (non-phpdoc)
     *
     * @param mixed $offset
     */
    public function offsetExists($offset)
    {
        try {
            $val = $this->getValue($offset);
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }

    /**
     * (non-phpdoc)
     *
     * @param mixed $offset
     */
    public function offsetGet($offset)
    {
        $cols = $this->getGrid()->getColumns();

        if ($cols[$offset]->hasCallback()) {
            $value = $cols[$offset]->callback($this->getItem());
        } else {
            $value = $this->getValue($offset);
        }

        return $value;
    }

    /**
     * NOT IMPLEMENTED
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        return null;
    }

    /**
     * NOT IMPLEMENTED
     *
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        return null;
    }
}
