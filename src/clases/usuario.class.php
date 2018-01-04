<?php

class Usuario extends SessionUser {

    public function __construct($id, $name) {
        $this->_id = $id;
        $this->_name = $name;
    }

    public function get_id() {
        return $this->_id;
    }

    public function get_name() {
        return $this->_name;
    }

    public function update_user() {
        return $this;
    }

    public function can_access($url, $method) {
        return true;
    }

}
