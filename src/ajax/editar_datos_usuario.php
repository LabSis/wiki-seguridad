<?php
require_once '../../config.php';

$sesion = Session::get_instance();
$respuesta = array();

$respuesta['estado'] = "error";
$respuesta['mensaje'] = "";


try{

    if (!$sesion->is_active()) {
        throw new EditarDatosUsuarioException("La sesión no está activa");
    }

    $clave_actual = filter_input(INPUT_POST, 'clave_actual', FILTER_SANITIZE_SPECIAL_CHARS);
    $clave_nueva = filter_input(INPUT_POST, 'clave_nueva', FILTER_SANITIZE_SPECIAL_CHARS);
    $clave_repetida = filter_input(INPUT_POST, 'clave_repetida', FILTER_SANITIZE_SPECIAL_CHARS);

    $nombre_usuario = $sesion->get_user()->get_name();


    if (!ApiBd::iniciar_sesion($nombre_usuario, $clave_actual)){
        throw new LabsisWikiException("La clave es incorrecta");
    }

    if (!isset($clave_nueva) || (isset($clave_nueva) && empty($clave_nueva))){
        throw new LabsisWikiException("La nueva clave no puede ser vacía");
    }

    if ($clave_nueva != $clave_repetida){
        throw new LabsisWikiException("Las claves no coinciden");
    }

    if (ApiBd::cambiar_clave_usuario($nombre_usuario, $clave_nueva)){
        $respuesta["estado"] = "ok";
    }
    else {
        throw new LabsisWikiException("Ha ocurrido un error en la actualizacion");
    }

    $respuesta["estado"] = "ok";


}
catch (EditarDatosUsuarioException $edue){
    $respuesta['estado'] = "error";
    $respuesta['mensaje'] = $edue->getMessage();
}
catch (Exception $e){
    $respuesta['estado'] = "error";
    if (DEV_MODE){
        $respuesta['mensaje'] = $e->getMessage();
    }
    else {
        $respuesta['mensaje'] = "Ha ocurrido un error";
    }

}

echo json_encode($respuesta);



class EditarDatosUsuarioException extends Exception{};