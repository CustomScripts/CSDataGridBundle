<?php

namespace CS\DataGridBundle\Grid;

use CS\DataGridBundle\Grid\Filer\FilterCollection;

interface GridInterface {

	public function setSource($source);

	public function getSource();
}
