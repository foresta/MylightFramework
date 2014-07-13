<?php

class OHM {

    private $value = null, $hash = null, $key = null, $defval = null, $type;

    public function __construct() {
        $this->type = function($value, $defval) {
            return $value;
        };
    }

    public function value($value) {
        $this->value = $value;
        return $this;
    }

    public function hash($hash, $key = null) {
        if(is_array($hash))
            $this->hash = $hash;
        $this->key = $key;
        return $this;
    }

    public function server($key = null) {
        return $this->global('_SERVER', $key);
    }

    public function get($key = null) {
        return $this->global('_GET', $key);
    }

    public function post($key = null) {
        return $this->global('_POST', $key);
    }

    public function files($key = null) {
        return $this->global('_FILES', $key);
    }

    public function cookie($key = null) {
        return $this->global('_COOKIE', $key);
    }

    public function session($key = null) {
        return $this->global('_SESSION', $key);
    }

    public function env($key = null) {
        return $this->global('_ENV', $key);
    }

    public function key($key) {
        $this->key = $key;
        return $this;
    }

    public function defval($defval) {
        $this->defval = $defval;
        return $this;
    }

    public function boolean() {
        $this->type = function($value, $defval) {
            return is_bool($value) ? $value : $defval;
        };
        return $this;
    }

    public function integer() {
        $this->type = function($value, $defval) {
            if(is_int($value) ||
                    is_numeric($value) && $value === strval(intval($value)))
                return intval($value);
            return $defval;
        };
        return $this;
    }

    public function float() {
        $this->type = function($value, $defval) {
            return is_float($value) ? $value : $defval;
        };
        return $this;
    }

    public function string() {
        $this->type = function($value, $defval) {
            return is_string($value) ? $value : $defval;
        };
        return $this;
    }

    public function map() {
        if(isset($this->hash)) {
            if(isset($this->key) && isset($this->hash[$this->key]))
                return call_user_func($this->type, $this->hash[$this->key],
                    $defval);
        } else {
            if(isset($this->value))
                return call_user_func($this->type, $this->value, $defval);
        }
        return $defval;
    }

    private function global($name, $key) {
        if(isset($$name) && is_array($$name))
            $this->hash = $$name;
        $this->key = $key;
        return $this;
    }
}
