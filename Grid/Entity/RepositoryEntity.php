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

use Doctrine\ORM\EntityRepository;

use CS\DataGridBundle\Grid\Entity\Entity;
use CS\DataGridBundle\Grid\Row;

class RepositoryEntity extends Entity
{
    /**
     * get the alias of the table
     *
     * @return string
     */
    public function getAlias()
    {
        return $this->getMetadata()->table['name'];
    }

    /**
     * (non-PHPdoc)
     *
     * @see CS\DataGridBundle\Grid\Entity.Entity::createQuery()
     */
    public function createQuery()
    {
        $table = $this->getAlias();

        $this->dql = $this->getRepository()->createQueryBuilder($table);

        // TODO : implement the sort order for row data
        /*$order = $this->getGrid()->order;

        if ($order) {
            if (!stripos($order, '.')) {
                $order = $table.'.'.$order;
            }

            $this->dql->orderBy($order);
        }*/

        return $this;
    }

    public function getData()
    {
        return $this->dql;
    }

    public function getResult()
    {
        return $this->dql->getQuery()->getResult();
    }

    /*public function fetch()
    {
        $query = $this->getDql()->getQuery();

        if ($this->getGrid()->paginate) {
            $paginator = $this->getContainer()->get('knp_paginator');
            $this->data = $paginator->paginate(
                    $query,
                    $this->getContainer()->get('request')->query->get('page', 1),
                    $this->getGrid()->paginate_limit
            );
        } else {
            $this->data = $query->getResult();
        }

        return $this;
    }*/

    public function getMetadata()
    {
        return $this->getEm()->getClassMetadata($this->getGrid()->getSource());
    }

    /**
     * Get all the columns for the cuurent entity
     * @see CS\DataGridBundle\Grid\Entity.Entity::getColumns()
     * @return array
     */
    public function getColumns()
    {
        $columns = $this->getMetadata()->getColumnNames();
        $mappings = $this->getMetadata()->getAssociationMappings();

        if (count($mappings) > 0) {
            foreach ($mappings as $col => $mapping) {
                if ($mapping['type'] === \Doctrine\ORM\Mapping\ClassMetaData::ONE_TO_ONE || $mapping['type'] === \Doctrine\ORM\Mapping\ClassMetaData::MANY_TO_ONE) {
                    $columns[] = $col;
                }
            }
        }

        return $columns;
    }

    public function setRepository(EntityRepository $repository)
    {
        $this->repository = $repository;

        return $this;
    }

    public function getRepository()
    {
        return $this->getEm()->getRepository($this->getSource());
    }

    public function getEm()
    {
        return $this->getContainer()->get('doctrine.orm.entity_manager');
    }

}
