<?php

namespace WPBin\Core;

use WPBin\Core\Traits\Entity\Validation;

abstract class Entity
{
    use Validation;

    protected $_accessors = [];

    public function __construct(Array $data)
    {
        $this->setByArray($data);
    }

    public function get($property)
    {
        if (!in_array($property, $this->_accessors)) { return null; }

        $args = func_get_args();
        $extended_getter = "get_$property";
        if (count($args) > 1 && method_exists($this, $extended_getter)) {
            return call_user_func_array(
                array($this, $extended_getter),
                array_slice($args, 1)
            );
        }

        return $this->$property;
    }

    public function set($property, $value)
    {
        if (!in_array($property, $this->_accessors)) { return false; }
        return $this->validateAndSet($property, $value);
    }

    public function setByArray(Array $data)
    {
        foreach ($data as $property => $value) {
            $this->set($property, $value);
        }
    }
}