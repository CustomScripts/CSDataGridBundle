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

use CS\DataGridBundle\Grid\GridInterface;
use CS\DataGridBundle\Grid\Entity;

use Symfony\Component\DependencyInjection\ContainerInterface;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service("grid")
 */
class Grid {

	/**
	 * An instance of the service container
	 *
	 * @var ContainerInterface $container
	 * @DI\Inject("service_container")
	 */
	public $container;

	/**
	 * An instance of the current grid
	 *
	 * @var GridInterface $grid
	 */
	protected $grid;

	/**
	 * The entity object that contains the data
	 *
	 * @var Entity $data
	 */
	protected $data;

	public function create(GridInterface $grid = null)
	{
		return $this->setGrid($grid)
			->getGridSource();
	}

	/**
	 * Gets the source for the grid (array, doctrine entity or QueryBuilder)
	 *
	 * @return mixed
	 */
	public function getGridSource()
	{
		$grid = $this->getGrid();

		$source = $grid->getSource();

		switch(gettype($source))
		{
			case 'string':
				$this->data = new Entity\RepositoryEntity($source, $grid, $this->getContainer());
			break;

			case 'object':
				$this->data = new Entity\QueryBuilderEntity($source, $grid);
			break;

			case 'array':
				$this->data = new Entity\ArrayEntity($source, $grid);
			break;

			default:
				throw new \Exception(sprintf('%s must have a getSource() method that return one of string|array|object', get_class($grid))); // TODO: create custom exception class
			break;
		}

		$grid->setEntity($this->data);
		return $this;
	}

	public function fetchData()
	{
		var_dump('aa');
		exit;
		$this->data
			->applyFilters($this->filters())
			->search()
			->fetch();

		return $this;
	}

	public function setGrid(GridInterface $grid)
	{
		$this->grid = $grid;

		//$grid->setContainer($this->getContainer());

		return $this;
	}

	public function getGrid()
	{
		return $this->grid;
	}

	public function getContainer()
	{
		return $this->container;
	}

	public function columns()
	{
		return $this->grid->columns();
	}

	public function data()
	{
		return $this->data->fetch();
	}

	/*public function get($key)
	{
		return $this->getContainer()->get($key);
	}*/

	/*public function filters()
	{
		return $this->grid->filters();
	}*/
}
