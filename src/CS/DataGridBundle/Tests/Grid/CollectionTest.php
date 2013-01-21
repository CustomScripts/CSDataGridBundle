<?php

/*
 * This file is part of the CSDataGridBundle package.
 *
 * (c) Pierre du Plessis <info@customscripts.co.za>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CS\DataGridBundle\Tests\Grid;

use CS\DataGridBundle\Grid\Collection;

class CollectionTest extends \PHPUnit_Framework_TestCase {

	protected $stack;

	public function setUp()
	{
		$this->collection = new Collection;

		$this->collection->addRecursive(array('foo', 'bar', 'baz'));
	}

	public function testCollection()
	{
		$elements = $this->collection->all();

		$this->assertCount(3, $elements);

		unset($elements);

		$this->assertTrue($this->collection->has('foo'));
		$this->assertTrue($this->collection->has('bar'));
		$this->assertTrue($this->collection->has('baz'));

		$this->assertEquals('foo', $this->collection->get('foo'));
		$this->assertEquals('bar', $this->collection->get('bar'));
		$this->assertEquals('baz', $this->collection->get('baz'));

		$this->collection->remove('bar');

		$elements = $this->collection->all();

		$this->assertCount(2, $elements);
		$this->assertFalse($this->collection->has('bar'));
	}
}