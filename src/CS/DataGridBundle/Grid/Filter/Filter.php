<?php

namespace CS\DataGridBundle\Grid\Filter;

class Filter {

	protected $name;

	protected $value;

	protected $callback;

	protected $options;

	public static function construct($name, $value, $callback = null, array $options = array())
	{
		$filter = new Filter;
		$filter->name = $name;
		$filter->value = $value;

		if(is_callable($callback))
		{
			$filter->callback = $callback;
		}

		$filter->setOptions($options);

		return $filter;
	}

	public function getDefault()
	{
		return isset($this->options['default']) && $this->options['default'] ? $this->getValue() : null;
	}

	public function getName()
	{
		return $this->name;
	}

	public function getValue()
	{
		return $this->value;
	}

	public function setOptions(array $options)
	{
		$this->options = $options;
	}

	public function call($dql)
	{
		$callback = $this->callback;

		return $callback($dql);
	}
}
