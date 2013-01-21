<?php

namespace CS\DataGridBundle\Twig\Extension;

use Twig_Extension;
use Twig_Function_Method;
use Symfony\Component\DependencyInjection\ContainerInterface;
use CS\DataGridBundle\Grid\Grid;

class GridExtension extends Twig_Extension
{
    /**
     * @var ContainerInterface
     */
    protected $templating;

    /**
     * Sets the container instance
     * @param ContainerInterface $container
     */
    public function setTemplating(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * (non-PHPDoc)
     *
     * @return array
     */
    public function getFunctions()
    {
        return array('grid' => new Twig_Function_Method($this, 'renderGrid', array("is_safe" => array("html"))));
    }

    /**
     * Renders an instance of the grid
     *
     * @param Grid $grid
     */
    public function renderGrid(Grid $grid)
    {
        return $this->container->get('templating')->render('CSDataGridBundle:Grid:default.html.twig', array('grid' => $grid));
    }

    /**
     * (non-PHPDoc)
     *
     * @return string
     */
    public function getName()
    {
        return 'cs_data_grid.twig.grid';
    }
}
