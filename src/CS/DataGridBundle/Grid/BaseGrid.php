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

use Symfony\Component\DependencyInjection\ContainerInterface;
use CS\DataGridBundle\Grid\Filter\FilterCollection;
use CS\DataGridBundle\Grid\Entity\Entity;
use CS\DataGridBundle\Grid\Column\ColumnCollection;

abstract class BaseGrid implements GridInterface {

	public function getSource(){}
	
	public function setSource($source)
	{
		var_dump($source);
		exit;
		$this->source = $source;
	}
	
	public function addColumn($name, $priority = 100, \Closure $callback = null)
	{
		$this->columns[$priority] = $name;

		if(is_callable($callback))
		{
			$this->addColumnCallback($name, $callback);
		}
	}

	public function addColumnCallback($column, \Closure $callback)
	{
		if(is_callable($callback))
		{
			$this->callbacks[$column] = $callback;
		} else {
			throw new \Exception('Please specify a valid callback!');
		}
	}

	public function hasCallback($column)
	{
		return isset($this->callbacks[$column]);
	}

	public function getCallback($column)
	{
		return $this->callbacks[$column];
	}

	public function setEntity(Entity $entity)
	{
		$this->entity = $entity;
	}

	public function getEntity()
	{
		return $this->entity;
	}

	/*public function filters()
	{
		$filters = new FilterCollection;

		if(method_exists($this, 'getFilters'))
		{
			$this->getFilters($filters);
		}
		return $filters;
	}*/

	public function columns()
	{
		$collection = new ColumnCollection;
		
		$collection->addRecursive($this->entity->getColumns());

		if(method_exists($this, 'getColumns'))
		{
			$this->getColumns($collection);
		}

		return $collection;
	}
	
	public function setContainer(ContainerInterface $container)
	{
		$this->container = $container;
		return $this;
	}

	public function getContainer()
	{
		return $this->container;
	}
	
	public function __get($property)
	{
		return false;
	}
}
