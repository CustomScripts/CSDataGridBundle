<?php

/*
 * This file is part of the CSDataGridBundle package.
 *
 * (c) Pierre du Plessis <info@customscripts.co.za>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CS\DataGridBundle\Grid\Entity;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Orm\EntityManager;
use Doctrine\ORM\EntityRepository;

use CS\DataGridBundle\Grid\GridInterface;
use CS\DataGridBundle\Grid\Filter\FilterCollection;
use CS\DataGridBundle\Grid\Row;

abstract class Entity implements \Countable, \Iterator {

	protected $container;

	protected $grid;

	protected $em;

	protected $repository;

	protected $dql;

	protected $data;

	private $_position = 0;

	/**
	 * Contains the data from the srouce
	 *
	 * @var mixed $source
	 */
	protected $source;

	public function __construct($source, GridInterface $grid, ContainerInterface $container)
	{
		$this->setSource($source)
			->setContainer($container)
			->setGrid($grid)
			->createQuery();
	}

	public function setSource($source)
	{
		$this->source = $source;
		return $this;
	}

	public function getSource()
	{
		return $this->source;
	}

	public function createQuery()
	{
		return $this;
	}

	public function getDql()
	{
		return $this->dql;
	}

	public function applyFilters(FilterCollection $filters)
	{
		$request_filter = $this->getContainer()->get('request')->get('filter');

		if(count($filters) > 0 && $request_filter)
		{
			foreach($filters as $filter)
			{
				if($filter->getValue() === $request_filter)
				{
					$filter->call($this->getData());
				}
			}
		}

		return $this;
	}

	public function search()
	{
		$search = $this->getContainer()->get('request')->get('search');

		if(method_exists($this->getGrid(), 'getSearch') && $search)
		{
			$this->getGrid()->getSearch($this->getData(), $search);
		}

		return $this;
	}

	public function fetch()
	{
		$query = $this->getData();

		// TODO: implement pagination
		/*if($this->getGrid()->paginate)
		{
			$paginator = $this->getContainer()->get('knp_paginator');
			$this->data = $paginator->paginate(
					$query instanceof QueryBuiler ? $query->getQuery() : $query, // TODO : get correct class instance
					$this->getContainer()->get('request')->query->get('page', 1),
					$this->getGrid()->paginate_limit
			);
		} else {
			$this->data = $this->getResult();
		}*/

		$this->data = $this->getResult();

		return $this;
	}

	public function getMetadata()
	{
		return $this->getEm()->getClassMetadata($this->getGrid()->getSource());
	}

	public function getColumns()
	{
		return $this->getMetadata()->getColumnNames();
	}

	public function setRepository(EntityRepository $repository)
	{
		$this->repository = $repository;
		return $this;
	}

	public function getRepository()
	{
		return $this->repository;
	}

	public function setEm(EntityManager $entity_manager)
	{
		$this->em = $entity_manager;
		return $this;
	}

	public function getEm()
	{
		return $this->em;
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

	public function setGrid(GridInterface $grid)
	{
		$this->grid = $grid;

		return $this;
	}

	public function getGrid()
	{
		return $this->grid;
	}

	public function getItem($position = 0)
	{
		//$items = $this->data->getItems();

		//if(isset($items[$position]) && !empty($items[$position])){

			$row = new Row($this->data[$position]);

			$row->setGrid($this->getGrid());

			return $row;
		/*} else {
			return null;
		}*/
	}

	public function __call($method, $args)
	{
		return call_user_func_array(array($this->data, $method), $args);
	}

	public function count()
	{
		return count($this->data);
	}

	public function valid() {
		return isset($this->data[$this->_position]);
	}

	public function next() {
		$this->_position++;
	}

	public function current() {
		return $this->getItem($this->_position);
	}

	public function rewind() {
		$this->_position = 0;
	}

	public function key() {
		return $this->_position;
	}
}
