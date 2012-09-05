<?php

namespace CS\DataGridBundle\Grid\Action;

use Gedmo\Exception\InvalidArgumentException;

class Action implements \ArrayAccess {

    /**
     * @var string The label of the action
     */
    protected $label;

    /**
     * Boolean to indicate if this action applies to rows of the grid
     * @var boolean
     */
    protected $require_row = false;

    /**
     * Boolean to indicate if multiple rows are allowed
     * @var boolean
     */
    protected $multiple_row = false;

    /**
     * Should a confirmation be shown before this action is applied
     * @var string The message to show in the confirmation box
     */
    protected $confirm;

    /**
     * The class for the icon of the action, if any
     * @var string
     */
    protected $icon;

    /**
     * An array of all the extra attributes for the action html (E.G id, class, style etc)
     * @var array
     */
    protected $attributes = array();

    /**
     * All event handles (click, hover etc) for the action
     * @var array
     */
    protected $events;

    /**
     * The action that should be performed, either redirect to a page, handle JavaScript etc
     * @var string
     */
    protected $action;

    /**
     * Should the action be available when there is no data
     * @var boolean
     */
    protected $show_when_empty;

    /**
     * Creates a new action
     *
     * @param string $label the label of the action
     * @return Action
     */
    public function __construct($label)
    {
        $this->label = $label;

        $this->attributes(array('id' => uniqid(), 'class' => 'btn'));

        return $this;
    }

    /**
     * @param boolean $empty toggle the action on or off when there is no data available
     */
    public function showWhenEmpty($empty = false)
    {
        if($empty && ($this->multiple_row || $this->require_row))
        {
            throw new \Exception(sprintf('The action "%s" cannot be shown when it requires data from a row', $this->label));
        }

        $this->show_when_empty = (boolean) $empty;
    }

    /**
     * Sets the action to only apply if a row is selected
     *
     * @param boolean $multiple allow this action to be applied to mutiple rows
     * @return Action
     */
    public function requireRow($multiple = false)
    {
        if($this->show_when_empty)
        {
            throw new \Exception(sprintf('The action "%s" cannot require a row when it is shown with no data', $this->label));
        }

        $this->require_row = true;

        $this->multiple_row = (bool) $multiple;

        return $this;
    }

    /**
     * Sets the message for the confirmation box
     *
     * @param string $message
     * @return Action
     */
    public function confirm($message = '')
    {
        $this->confirm = $message ?: "Are you sure?";

        return $this;
    }

    /**
     * Sets the class of the icon to use on the action
     *
     * @param string $class
     * @return Action
     */
    public function icon($class = null)
    {
        if(!is_null($class))
        {
            $this->icon = ((substr($class, 0, 4) !== 'icon') ? 'icon-' : '').$class;
        }

        return $this;
    }

    /**
     * Sets the attributes for the current actions
     *
     * @param array $attributes
     * @return Action
     */
    public function attributes($attributes = array())
    {
        $this->attributes = array_merge($this->attributes, $attributes);

        return $this;
    }

    /**
     * Sets the path for the action
     *
     * @param string $action
     * @return Action
     */
    public function setAction($action = '#')
    {
        $this->action = $action;

        return $this;
    }

    /**
     * Handles al the events set for the action
     *
     * @param string $method
     * @param array $args
     * @return Action
     */
    public function __call($method, $args)
    {
        if(substr(strtolower($method), 0, 2) === 'on')
        {
            $event = substr(strtolower($method), 2);

            if(!isset($args[0]) || gettype($args[0]) !== 'string')
            {
                throw new \InvalidArgumentException(sprintf('The first parameter for method %s in class %s must be a string', $method, get_class($this)));
            }

            $this->events[$event] = $args[0];
        }

        return $this;
    }

    public function offsetExists($offset)
    {
        return property_exists($this, $offset);
    }

    public function offsetSet($offset, $value)
    {
        return $this->{$offset} = $value;
    }

    public function offsetGet($offset)
    {
        return $this->{$offset};
    }

    public function offsetUnset($offset)
    {
        $this->{$offset} = null;
    }
}