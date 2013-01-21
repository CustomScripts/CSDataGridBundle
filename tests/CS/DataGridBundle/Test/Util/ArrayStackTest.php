<?php

/*
 * This file is part of the CSDataGridBundle package.
 *
 * (c) Pierre du Plessis <info@customscripts.co.za>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CS\DataGridBundle\Test\Util;
use CS\DataGridBundle\Util\ArrayStack;

class ArrayStackTest extends \PHPUnit_Framework_TestCase
{
    protected $stack;

    public function setUp()
    {
        $this->stack = new ArrayStack;

        $this->stack[] = 'foo';
        $this->stack[] = 'bar';
        $this->stack[5] = 'baz';
        $this->stack[10] = 'fizz';
        $this->stack[5] = 'buzz';
        $this->stack[5] = 'fizzbuzz';
    }

    public function testKeys()
    {
        $elements = $this->stack->all();

        $this->assertCount(6, $elements);

        $this->assertArrayHasKey(0, $elements);
        $this->assertArrayHasKey(1, $elements);
        $this->assertArrayHasKey(5, $elements);
        $this->assertArrayHasKey(6, $elements);
        $this->assertArrayHasKey(7, $elements);
        $this->assertArrayHasKey(10, $elements);
    }

    public function testStack()
    {
        $this->assertEquals('foo', $this->stack[0]);
        $this->assertEquals('bar', $this->stack[1]);
        $this->assertEquals('baz', $this->stack[5]);
        $this->assertEquals('buzz', $this->stack[6]);
        $this->assertEquals('fizzbuzz', $this->stack[7]);
        $this->assertEquals('fizz', $this->stack[10]);
    }

    public function testSort()
    {
        $this->stack->sort();

        $this->testStack();
    }

    public function testFirst()
    {
        $this->assertEquals('foo', $this->stack->first());
    }

    public function testLast()
    {
        $this->assertEquals('fizz', $this->stack->last());
    }

    public function testCount()
    {
        $this->assertCount(6, $this->stack);
    }
}
