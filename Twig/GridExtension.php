<?php

namespace CS\DataGridBundle\Twig;

use JMS\DiExtraBundle\Annotation as DI;
use Twig_Extension;
use Twig_Function_Method;

use CS\DataGridBundle\Grid\Grid;

/**
 * @DI\Service("cs.twig.grid_extension")
 * @DI\Tag("twig.extension")
 */
class GridExtension extends Twig_Extension
{
    /**
     * @DI\Inject("service_container")
     */
    public $container;

    public function getFunctions()
    {
        return array('grid' => new Twig_Function_Method($this, 'renderGrid', array("is_safe" => array("html"))));
    }

    public function renderGrid(Grid $grid)
    {
        return $this->container->get('templating')->render('CSDataGridBundle:Grid:default.html.twig', array('grid' => $grid));
    }

    public function getName()
    {
        return 'cs.twig.grid';
    }
}
