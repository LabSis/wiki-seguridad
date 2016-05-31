<?php

ini_set("display_errors", 1);
header('Content-Type: text/html; charset=utf-8');
if (basename($_SERVER['PHP_SELF']) == 'config.php') {
    die('Acceso incorrecto a la aplicación.');
}
//Definir variables de configuración
$config = array(
    'debug' => true, //debe ser true para el momento de desarrollo, pero DEBE SER FALSE PARA LOS USUARIOS
    'default_timezone' => 'America/Argentina/Cordoba',
    #Configuración de la Base de Datos
    'db_host' => 'localhost',
    'db_user' => 'root',
    'db_pass' => '',
    'db_name' => 'labsis_seg'
);
$DEBUG_MODE = $config['debug'];

//Borra la cache... analizarlo
header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');

//Dependiendo de la configuración establezco si se mostrarán o no los errores.
if ($DEBUG_MODE) {
    error_reporting(E_ALL | E_STRICT);
} else {
    error_reporting(0);
}

//La función date necesita que se le configure el timezone
date_default_timezone_set($config['default_timezone']);

//Rutas
$RUTA_SERVIDOR = dirname(__FILE__) . '/';
//Si decidimos usar https habria que cambiar aqui
$RUTA_WEB = 'http://' . $_SERVER['HTTP_HOST'] . '/labsis_seg';

$RUTA_PUBLICA = $RUTA_SERVIDOR . "public/";

$paquetes = array(
    '', 'api');

/**
 * Función de autocarga de las clases.
 * Soporta que las clases estén distribuidas en carpetas siempre y cuando
 * éstas tengan nombres diferentes. Dos clases con nombres iguales y en carpetas
 * diferentes puede provocar ambigüedad. Para solucionar esto se debe usar
 * namespace y la función spl_autoload_register().
 * 
 * NOTA: No soporta clases con métodos estáticos como la clase Lib.
 */
function __autoload($nombre_clase) {
    global $paquetes;
    global $RUTA_SERVIDOR;
    $ruta_clases = $RUTA_SERVIDOR . "/src/clases/";
    $resultado = '';
    $nombre_clase = str_ireplace("DAO", "", $nombre_clase);
    $caracteres = str_split($nombre_clase);
    for ($i = 0; $i < count($caracteres); $i++) {
        if (ctype_upper($caracteres[$i]) && $i !== 0) {
            $resultado .= '_';
        }
        $resultado .= strtolower($caracteres[$i]);
    }
    for ($i = 0; $i < count($paquetes); $i++) {
        $posible_archivo = "{$ruta_clases}/{$paquetes[$i]}/{$resultado}.class.php";
        if (file_exists($posible_archivo)) {
            require_once $posible_archivo;
            break;
        }
    }
}

//Conexión a la base de datos.
Conexion::set_default_conexion($config['db_name'], Conexion::init($config['db_host'], $config['db_user'], $config['db_pass'], $config['db_name'], $DEBUG_MODE));

session_start();

//ID CLIENTE GOOGLE
define("ID_GOOGLE", "187234785771-22pdekf8gkrh0iden96uida9kf91qrae.apps.googleusercontent.com");
