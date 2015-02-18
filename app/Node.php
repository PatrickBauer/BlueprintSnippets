<?php
/**
 * Created by PhpStorm.
 * User: patrick
 * Date: 30.01.15
 * Time: 17:24
 */

namespace App;


class Node {
    public $subNodes = array();

    public $attributes = array();

    public function addSubNode($node) {
        $this->subNodes[] = $node;
    }

    public function setAttributes($attributes)
    {
        array_map(array($this, 'setAttribute'), $attributes);
    }

    public function setAttribute($attribute) {
        $parts = explode('=', $attribute, 2);

        if(count($parts) !== 2) {
            throw new \Exception('Attribute is not valid.');
        }

        list($key, $value) = $parts;

        $this->attributes[$key] = trim($value, "\"");
    }

}