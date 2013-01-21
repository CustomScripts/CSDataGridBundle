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

    /**
     * (non-phpdoc)
     *
     * @param mixed $item
     */
    public function __construct($item = null)
    {
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
     * @param  string                                  $property
     * @throws Exception\PropertyAccessDeniedException
     * @throws Exception\InvalidPropertyException
     * @throws Exception\NotValidException
     * @return mixed
     */
    protected function getValue($property)
    {
        $object = $this->getItem();

        $camelProp = $this->camelize($property);
        $reflClass = new \ReflectionObject($object);
        $getter = 'get'.$camelProp;
        $isser = 'is'.$camelProp;

        if ($reflClass->hasMethod($getter)) {
            if (!$reflClass->getMethod($getter)->isPublic()) {
                throw new Exception\PropertyAccessDeniedException(sprintf('Method "%s()" is not public in class "%s"', $getter, $reflClass->getName()));
            }

            $value = $object->$getter();
        } elseif ($reflClass->hasMethod($isser)) {
            if (!$reflClass->getMethod($isser)->isPublic()) {
                throw new Exception\PropertyAccessDeniedException(sprintf('Method "%s()" is not public in class "%s"', $isser, $reflClass->getName()));
            }

            $value = $object->$isser();
        } elseif ($reflClass->hasMethod('__get')) {
            // needed to support magic method __get
            return $object->$property;
        } elseif ($reflClass->hasProperty($property)) {
            if (!$reflClass->getProperty($property)->isPublic()) {
                throw new Exception\PropertyAccessDeniedException(sprintf('Property "%s" is not public in class "%s". Maybe you should create the method "%s()" or "%s()"?', $property, $reflClass->getName(), $getter, $isser));
            }

            $value = $object->$property;
        } elseif (property_exists($object, $property)) {
            // needed to support \stdClass instances
            $value = $object->$property;
        } else {
            throw new Exception\InvalidPropertyException(sprintf('Neither property "%s" nor method "%s()" nor method "%s()" exists in class "%s"', $property, $getter, $isser, $reflClass->getName()));
        }

        if (is_object($value)) {
            if ($value instanceof \DateTime) {
                // TODO : update format to read from a central config
                return $value->format('Y-m-d');
            } elseif (method_exists($value, '__toString')) {
                return (string) $value;
            } else {
                throw new Exception\NotValidException(sprintf('The value for %s in class %s needs to be an instance of the DateTime Object or should implement a __toString method', $property, $reflClass->getName()));
            }
        } elseif (is_string($value) || is_int($value) || is_null($value)) {
            return $value;
        } else {
            throw new Exception\NotValidException(sprintf('The value for %s in class %s needs to either return a string, DateTime Object, or object that implements a __toString method', $property, $reflClass->getName()));
        }
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
