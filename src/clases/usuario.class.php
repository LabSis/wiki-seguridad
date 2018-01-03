<?php

class Usuario extends SessionUser {

    private $id = 1;
    private $name = "";

    public function get_id() {
        return $this->id;
    }

    public function get_name() {
        return $this->name;
    }

    public function update_user() {
        $this->name = "T";
        return $this;
    }

    public function can_access($url, $method) {
        return true;
    }

}
