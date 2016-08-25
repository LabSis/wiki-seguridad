<?php
require_once '../config.php';

$session = Session::get_instance();

$method = filter_input(INPUT_SERVER, "REQUEST_METHOD");
if (strcasecmp($method, "POST") === 0) {
    $technique_parent_id = filter_input(INPUT_POST, "hidTechniqueParentId");
    $technique_name = filter_input(INPUT_POST, "txtTechniqueName");

    $ok = true;
    if (isset($technique_parent_id) && isset($technique_name)) {
        if($technique_parent_id == ""){
            $ok = ApiBd::add_technique($technique_name, null);
        } else {
            $ok = ApiBd::add_technique($technique_name, $technique_parent_id);
        }
    } else {
        $ok = false;
    }
    if ($ok) {
        $session->add_success_message("La técnica fue creada con éxito");
    } else {
        $session->add_success_message("Error al crear la técnica");
    }
    header("Location: ../index.php");
}

