<?php

/********

CONFIG VERSION 2.3

********/

ini_set("display_errors", 1);

/*** HEADER UTF-8 ***/
header('Content-Type: text/html; charset=utf-8');

    // Todas las rutas de directorio terminan en /
    // Rutas absolutas
$PROTOCOL = 'http://';
$SERVER_PATH = dirname(__FILE__) . '/';
$WEB_PATH = $PROTOCOL . $_SERVER['HTTP_HOST'] . '/wiki-seguridad/';
$LOGIN_PATH = $WEB_PATH;
$PUBLIC_PATH = $SERVER_PATH . "public/";

    // Rutas relativas
$CLASSES_REL_PATH = 'src/clases/';
$TEMPLATES_REL_PATH = 'tmpl/';
$CTRL_REL_PATH = 'src/';
$AJAX_REL_PATH = 'ctrl/ajax/';

/*** Función de error ***/
function ERROR($message = "",  $absolute_path = null, $status_code = 500){
    global $WEB_PATH;
    if(!isset($absolute_path)){
        $absolute_path = $WEB_PATH . "error.php";
    }
    http_response_code($status_code); // Ver que el código no se envía porque dsp hago una redirección. Hay que arreglar esto.
    header("Location: $absolute_path?message=$message"); // Si no se encuentra, Apache te lanzará uno por defecto (404).
}

/*** No se puede acceder directamente al config.php ***/
if (basename($_SERVER['PHP_SELF']) === 'config.php') {
    ERROR();
}

/*** DEV_MODE flag ***/
define ("DEV_MODE", true);

if(DEV_MODE){
    ini_set("display_errors", 1);
} else {
    ini_set("display_errors", 0);
}
error_reporting(E_ALL);

/*** TIMEZONE ***/
date_default_timezone_set('America/Argentina/Cordoba');

/*** Headers de caché ***/
header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');

/*** Autoload de clases ***/

$PACKAGES = array('', 'api');

/**
 * Función de autocarga de las clases.
 * Soporta que las clases estén distribuidas en carpetas siempre y cuando
 * éstas tengan nombres diferentes. Dos clases con nombres iguales y en carpetas
 * diferentes puede provocar ambigüedad. Para solucionar esto se debe usar
 * namespace.
 * 
 */
spl_autoload_register(function ($nombre_clase) {
    global $PACKAGES, $SERVER_PATH, $CLASSES_REL_PATH;
    $ruta_clases = $SERVER_PATH . $CLASSES_REL_PATH;
    $resultado = '';

    // Tengo en cuenta los namespaces
    $partes = explode('\\', $nombre_clase);
    $nombre_clase = $partes[count($partes) - 1];

    // Continúa ignorando el namespace
    $caracteres = str_split($nombre_clase);
    for ($i = 0; $i < count($caracteres); $i++) {
        if (ctype_upper($caracteres[$i]) && $i !== 0) {
            $resultado .= '_';
        }
        $resultado .= strtolower($caracteres[$i]);
    }
    for ($i = 0; $i < count($PACKAGES); $i++) {
        $posible_archivo = "{$ruta_clases}{$PACKAGES[$i]}/{$resultado}.class.php";
        if (file_exists($posible_archivo)) {
            require_once $posible_archivo;
            break;
        }
    }
});

/*** Base de datos ***/
$DB_HOST = 'localhost';
$DB_USER = 'ile';
$DB_PASS = 'ileile';
$DB_NAME = 'labsis_seg';

Conexion::set_default_conexion($DB_NAME, Conexion::init($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME, DEV_MODE));

/*** Sesión ***/
$current_time = time();
$session_time = 1800; // Tiempo que dura la sesión en segundos
session_name('PaBexID'); // Esto debería establecerse en el php.ini
ini_set('session.cookie_httponly', 1); // Esto debería establecerse en el php.ini
//session_save_path('/etc/php5/apache2');
session_start();
if(isset($_SESSION['LAST_ACTIVITY']) && ($current_time - $_SESSION['LAST_ACTIVITY'] > $session_time)){
    session_unset();
    session_destroy();
}
$_SESSION['LAST_ACTIVITY'] = $current_time;

