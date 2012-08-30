<?php

namespace CS\DataGridBundle\Grid\Column;

class Column {

    /**
     * The label of the column
     * @var string $label
     */
	protected $label;

	/**
	 * The name of the column
	 * @var string $name
	 */
	protected $name;

	public function __construct($name)
	{
		$this->label = $name;
		$this->name = $name;
	}

	public function getLabel()
	{
		return $this->label;
	}

	public function getName()
	{
	    return $this->name;
	}

	public function setLabel($label)
	{
	    $this->label = $label;

	    return $this;
	}

	public function __toString()
	{
	    return $this->getLabel();
	}
}
