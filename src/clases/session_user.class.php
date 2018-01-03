<?php

/*
 * Clase Abstracta SessionUser.
 * @author Parisi Germán
 * @version 2.0
 */
abstract class SessionUser {
    public abstract function get_id();
    public abstract function get_name();
    public abstract function update_user();
    public abstract function can_access($url, $method);
}
