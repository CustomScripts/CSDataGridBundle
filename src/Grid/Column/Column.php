<?php

/*
 * This file is part of the CSDataGridBundle package.
 *
 * (c) Pierre du Plessis <info@customscripts.co.za>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CS\DataGridBundle\Grid\Column;

class Column
{
    /**
     * The label of the column
     * @var string $label
     */
    protected $label;

    /**
     * The name of the column
     * @var string $name
     */
    protected $name;

    /**
     * Sets a callback for the specific column's value
     * @var Closure
     */
    protected $callback;

    /**
     * Constructor to create a new Column
     *
     * @param string $name
     * @param mixed  $callback
     */
    public function __construct($name, $callback = null)
    {
        $this->name = $name;

        $this->setLabel($name)
            ->setCallback($callback);
    }

    /**
     * Verify if the column has a callback and that it is valid
     */
    public function hasCallback()
    {
        return $this->callback && is_callable($this->callback);
    }

    /**
     * Calls the callback on the column
     *
     * @param mixed $value
     */
    public function callback($value)
    {
        if (!$this->hasCallback()) {
            throw new \Exception(sprintf('The column %s does\'t have a valid callback', $this->getName()));
        }

        return call_user_func($this->callback, $value);
    }

    /**
     * Sets the callback on the current column
     *
     * @param mixed $callback
     */
    public function setCallback($callback)
    {
        $this->callback = $callback;

        return $this;
    }

    /**
     * Return the label for the current column
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Returns the name of the column
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the label for the current column
     *
     * @param string $label
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Returns the current column as a string
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }
}
