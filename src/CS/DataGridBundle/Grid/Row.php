<?php

namespace CS\DataGridBundle\Grid;

use Symfony\Component\Form\Exception;

use CS\DataGridBundle\Grid\GridInterface;

class Row implements \ArrayAccess {

	protected $item;

	protected $grid;

	public function __construct($item = null)
	{
		$this->setItem($item);
	}

	public function setItem($item)
	{
		$this->item = $item;
	}

	public function getItem()
	{
		return $this->item;
	}

	public function setGrid(GridInterface $grid)
	{
		$this->grid = $grid;
	}

	public function getGrid()
	{
		return $this->grid;
	}

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

		if(is_object($value))
		{
		    if($value instanceof \DateTime)
		    {
		        // TODO : update format to read from a central config
		        return $value->format('Y-m-d');
		    } else if(method_exists($value, '__toString'))
		    {
		        return (string) $value;
		    } else {
		        throw new Exception\NotValidException(sprintf('The value for %s in class %s needs to be an instance of the DateTime Object or should implement a __toString method', $property, $reflClass->getName()));
		    }
		} else if(is_string($value) || is_int($value))
		{
		    return $value;
		} else {
		    throw new Exception\NotValidException(sprintf('The value for %s in class %s needs to either return a string, DateTime Object, or object that implements a __toString method', $property, $reflClass->getName()));
		}
	}

	protected function camelize($property)
	{
		return preg_replace_callback('/(^|_|\.)+(.)/', function ($match) { return ('.' === $match[1] ? '_' : '').strtoupper($match[2]); }, $property);
	}

	public function offsetExists($offset)
	{
		try {
			$val = $this->getValue($offset);
		} catch(\Exception $e)
		{
			return false;
		}

		return true;
	}

	public function offsetGet($offset)
	{
	    $cols = $this->getGrid()->columns();

	    if($cols[$offset]->hasCallback())
	    {
	        $value = $cols[$offset]->callback($this->getItem());
	    } else {
	        $value = $this->getValue($offset);
	    }

		return $value;
	}

	public function offsetSet($offset, $value)
	{

	}

	public function offsetUnset($offset)
	{

	}
}
