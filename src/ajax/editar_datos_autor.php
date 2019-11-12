<?php
require_once '../../config.php';

$sesion = Session::get_instance();
$respuesta = array();

$respuesta['estado'] = "error";
$respuesta['mensaje'] = "";


try{

    if (!$sesion->is_active()) {
        throw new LabsisWikiException("La sesión no está activa");
    }


    $nombre_autor = filter_input(INPUT_POST, 'nombre_autor', FILTER_SANITIZE_SPECIAL_CHARS);
    $alias_autor = filter_input(INPUT_POST, 'alias_autor', FILTER_SANITIZE_SPECIAL_CHARS);
    $email_autor = filter_input(INPUT_POST, 'email_autor', FILTER_SANITIZE_SPECIAL_CHARS);
    $usuario_github = filter_input(INPUT_POST, 'usuario_github', FILTER_SANITIZE_SPECIAL_CHARS);

    $nombre_usuario = $sesion->get_user()->get_name();
    $autor = ApiBd::consultar_autor_por_usuario($nombre_usuario);


    if (!isset($nombre_autor) || (isset($nombre_autor) && empty($nombre_autor))){
        throw new LabsisWikiException("El nombre de autor no puede ser vacío");
    }

    if (ApiBd::editar_autor($autor['id'], $nombre_autor, $alias_autor, $email_autor, $usuario_github)){
        $respuesta["estado"] = "ok";
    }
    else {
        throw new LabsisWikiException("Ha ocurrido un error en la actualizacion");
    }


}
catch (LabsisWikiException $edue){
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



