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
use CS\DataGridBundle\Grid\Action\ActionCollection;
use CS\DataGridBundle\Grid\Column\ColumnCollection;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Grid
{
    /**
     * An instance of the service container
     *
     * @var ContainerInterface $container
     */
    protected $container;

    /**
     * An instance of the current grid
     *
     * @var GridInterface $grid
     */
    protected $grid;

    public $columnCollection;

    /**
     * Constructor
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

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

    public function getSource()
    {
        return $this->grid->getSource();
    }

    /**
     * Gets the source for the grid (array, doctrine entity or QueryBuilder)
     *
     * @return mixed
     */
    protected function getGridSource()
    {
        $grid = $this->getGrid();

        $source = $grid->getSource();

        switch (gettype($source)) {
            case 'string':
                $this->data = new Entity\RepositoryEntity($source, $this, $this->getContainer());
            break;

            case 'object':
                $this->data = new Entity\QueryBuilderEntity($source, $this);
            break;

            case 'array':
                $this->data = new Entity\ArrayEntity($source, $this);
            break;

            default:
                throw new \Exception(sprintf('%s must have a getSource() method that return one of string|array|object', get_class($grid))); // TODO: create custom exception class
            break;
        }

        $grid->setEntity($this->data);

        return $this;
    }

    public function getName()
    {
        if (method_exists($this->grid, 'getName')) {
            return $this->grid->getName();
        } elseif (method_exists($this->grid, '__toString')) {
            return (string) $this->grid;
        } else {
            throw new \Exception('The grid must have a getName or __toString method');
        }
    }

    public function fetchData()
    {
        $this->data
            ->applyFilters($this->filters())
            ->search()
            ->fetch();

        return $this;
    }

    public function setGrid(GridInterface $grid)
    {
        $this->grid = $grid;

        $grid->setContainer($this->getContainer());
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

    protected function fetchColumns()
    {
    	if(!$this->columnCollection instanceof ColumnCollection)
    	{
    		$this->columnCollection = new ColumnCollection;

    		$this->columnCollection->addRecursive($this->data->getColumns());

        	$this->grid->getColumns($this->columnCollection);
    	}
    }

    public function getColumns()
    {
    	$this->fetchColumns();
        return $this->columnCollection;
    }

    public function getActions()
    {
        $collection = new ActionCollection;

        $this->grid->getActions($collection);

        return $collection;
    }

    public function data()
    {
        return $this->data->fetch();
    }

    public function __call($method, $arguments)
    {
    	$attributes = $this->grid->getAttributes();

    	if(isset($attributes[$method]))
    	{
    		return $attributes[$method];
    	} else {
    		throw new \InvalidArgumentException(sprintf('The attribute "%s" for grid %s does not exist', $method, get_class($this->grid)));
    	}
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
