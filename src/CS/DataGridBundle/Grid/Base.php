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
use CS\DataGridBundle\Grid\Entity\Entity;
use CS\DataGridBundle\Grid\Column\ColumnCollection;
use CS\DataGridBundle\Grid\Action\ActionCollection;

abstract class Base implements GridInterface
{
    /**
     * Method to return the source for the data
     *
     * @see CS\DataGridBundle\Grid.GridInterface::getSource()
     */
    public function getSource(){}

    /**
     * (non-PHPdoc)
     * @see CS\DataGridBundle\Grid.GridInterface::setSource()
     */
    public function setSource($source)
    {
        var_dump($source);
        exit;
        $this->source = $source;
    }

    /**
     * Sets the datasource entity to the current grid
     *
     * @param Entity $entity
     */
    public function setEntity(Entity $entity)
    {
        $this->entity = $entity;
    }

    /**
     * @return the current DataSource entity
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * @return the column collection for rendering on the grid
     */
    public function columns()
    {
        $collection = new ColumnCollection;

        $collection->addRecursive($this->entity->getColumns());

        if (method_exists($this, 'getColumns')) {
            $this->getColumns($collection);
        }

        return $collection;
    }

    /**
     * @return the ActionCollection for rendering on the grid
     */
    public function actions()
    {
        $collection = new ActionCollection;

        if (method_exists($this, 'getActions')) {
            $this->getActions($collection);
        }

        return $collection;
    }

    /**
     * Sets an instance of the container
     *
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;

        return $this;
    }

    /**
     * @return the service container
     */
    public function getContainer()
    {
        return $this->container;
    }
}
