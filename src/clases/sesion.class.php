<?php

/**
 * Clase singleton que representa la sesión actual del usuario activo. Se 
 * encarga de acceder de manera controlada a la variable $_SESSION. 
 * Específicamente recuerda:
 * * El usuario que inició sesión.
 * * Los mensajes cargados para existir entre webs diferentes.
 * * La web de la cual proviene.
 * * La ip del cliente.
 * 
 * Cuando el usuario es anónimo, o sea que no inició sesión, también se crea
 * una sesión pero de usuario anónimo.
 *
 * @author Diego Barrionuevo y Parisi Germán
 * @version 1.2
 */
class Sesion {

    const TIPO_MENSAJE_INFORMACION = 'info';
    const TIPO_MENSAJE_EXITO = 'exito';
    const TIPO_MENSAJE_ALERTA = 'alerta';
    const TIPO_MENSAJE_ERROR = 'error';

    /**
     *
     * @var \Sesion
     */
    private static $_instancia;

    /**
     *
     * @var \Usuario
     */
    private $_usuario;

    /**
     *
     * @var string
     */
    private $_mensajes;

    /**
     *
     * @var string
     */
    private $_ultima_web;

    private function __construct() {
        $this->_usuario = null;
        $this->_mensajes = array(
            self::TIPO_MENSAJE_INFORMACION => array(),
            self::TIPO_MENSAJE_EXITO => array(),
            self::TIPO_MENSAJE_ALERTA => array(),
            self::TIPO_MENSAJE_ERROR => array()
        );
        $this->_ultima_web = null;
    }

    /**
     * Retorna un objeto de la clase sesion.
     * @return \Sesion
     */
    public static function get_instancia() {
        if (is_null(self::$_instancia)) {
            self::$_instancia = new Sesion();
        }
        return self::$_instancia;
    }

    /**
     * Establece el usuario en la sesión.
     * 
     * @param \Usuario $usuario
     */
    public function iniciar_sesion($usuario) {
        $this->set_usuario($usuario);
    }

    /**
     * Actualiza el usuario en la sesión, es decir vuelve a buscar los datos 
     * del usuario actualmente ingresado, y resetea todos sus datos, así la 
     * sesión tiene los datos fieles y reales del usuario al instante. Esto 
     * puede ser útil si el usuario agrega o modifica sus datos.
     * @return boolean True, si tuvo éxito al actualizar los datos del usuario. 
     * False en caso contrario.
     */
    public function actualizar() {
//        if ($this->activo()) {
//            $usuario_actualizado = UsuarioDAO::consultar_usuario_por_id($this->_usuario->get_id());
//            if (!is_null($usuario_actualizado)) {
//                $this->set_usuario($usuario_actualizado);
//                return true;
//            }
//        }
        return false;
    }

    /**
     * Verifica que el usuario en la sesión actual de verdad exista, es decir 
     * verifica si hay un usuario ingresado o si caducó la sesión. Setea como 
     * parte de sus atributos el usuario actual (algo similar a lo que sucede 
     * al iniciar la sesión del usuario). Es muy útil para verificar en cada 
     * página si es usuario ha ingresado como tal o no.
     * @return boolean True, si el usuario sí ha ingresado y su sesión aún no 
     * caducó. False, en caso contrario.
     */
    public function activo() {
        if (isset($_SESSION['usuario'])) {
            $this->_usuario = $_SESSION['usuario'];
            return true;
        }
        return false;
    }

    /**
     * Determina si un usuario puede acceder a una página web.
     * @return boolean True si puede acceder, False en caso contrario.
     */
    public function puede_acceder() {
//        $recurso = filter_input(INPUT_SERVER, "PHP_SELF");
//        if ($this->activo()) {
//            $id = $this->_usuario->get_id();
//            $permisos = PermisoDAO::get_permisos($id);
//        } else {
//            $permisos = PermisoDAO::get_permisos_anonimos();
//        }
//        $ok = false;
//        for ($i = 0; $i < count($permisos); $i++) {
//            $permiso = $permisos[$i];
//            if ($permiso->get_url() == $recurso) {
//                $ok = true;
//                break;
//            }
//        }
//        return $ok;
    }

    /**
     * Retorna los permisos para un usuario logueado.
     * Lanza una excepción indicando que el usuario no ha iniciado sesión.
     */
    public function get_permisos() {
        $permisos = null;
        if ($this->activo()) {
            $id = $this->_usuario->get_id();
            $permisos = PermisoDAO::get_permisos($id);
        } else {
            throw new Exception('El usuario es anónimo.');
        }
        return $permisos;
    }

    /**
     * Setea como miembro de sí, la instancia del usuario pasada como parámetro. 
     * A su vez, guarda en la sesión ésta instancia.
     * @param Usuario $usuario Instancia de usuario.
     */
    public function set_usuario($usuario) {
        if (!is_null($usuario)) {
            $_SESSION['usuario'] = $usuario;
            $this->_usuario = $usuario;
        }
    }

    /**
     * Devuelve el usuario de la sesión actual
     *  
     * NOTA: No hace ningún control, por lo que puede devolver null.
     * 
     * @return \Usuario El usuario de la sesión actual.
     */
    public function get_usuario() {
        if ($this->activo()) {
            return $this->_usuario;
        }
        return null;
    }

    /**
     * Devuelve el rol del usuario actual.
     * 
     * Puede devolver null si no hay usuario.
     * 
     * @return \Rol
     */
    public function get_rol() {
        if (isset($this->_usuario)) {
            return $this->_usuario->get_rol();
        }
        return null;
    }

    /**
     * Retorna el nombre de usuario.
     * Hace una consulta a la base de datos si es necesario.
     * @return string
     */
    public function get_nombre_usuario() {
        $id_usuario = $this->_usuario->get_id();
        if (!isset($this->_nombre_usuario)) {
            $this->_nombre_usuario = UsuarioDAO::consutlar_nombre_usuario_por_id($id_usuario);
        }
        return $this->_nombre_usuario;
    }

    /**
     * Cierra la sesión actual del usuario.
     * 
     * NOTA: Elimina las variables de sesión, pero no las cookies de navegación.
     */
    public function cerrar_sesion() {
        session_destroy();
        $_SESSION = array();
    }

    /**
     * Cierra de manera seguro y absoluta la sesión actual del usuario.
     * 
     * NOTA: Eliminar todas las cookies producto de la sesión.
     */
    public function cerrar_sesion_completamente() {
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]
            );
        }
        $this->cerrar_sesion();
    }

    /**
     * Carga un mensaje en la sesión para que esté disponible entre distintas 
     * webs.
     * @param mixed $mensaje Puede ser un array de string o bien un string.
     * @param string $tipo_mensaje
     */
    public function cargar_mensaje($mensaje, $tipo_mensaje) {
        if (is_array($mensaje)) {
            $this->_mensajes[$tipo_mensaje] = array_merge($this->_mensajes[$tipo_mensaje], $mensaje);
        } else {
            $this->_mensajes[$tipo_mensaje][] = $mensaje;
        }
        $_SESSION['mensajes'] = $this->_mensajes;
    }

    public function cargar_mensaje_error($mensaje) {
        $this->cargar_mensaje($mensaje, self::TIPO_MENSAJE_ERROR);
    }

    public function cargar_mensaje_exito($mensaje) {
        $this->cargar_mensaje($mensaje, self::TIPO_MENSAJE_EXITO);
    }

    public function cargar_mensaje_informacion($mensaje) {
        $this->cargar_mensaje($mensaje, self::TIPO_MENSAJE_INFORMACION);
    }

    public function cargar_mensaje_alerta($mensaje) {
        $this->cargar_mensaje($mensaje, self::TIPO_MENSAJE_ALERTA);
    }

    /**
     * Retorna true si hay mensajes agregados. False en caso contrario.
     * @return boolean 
     */
    public function hay_mensajes() {
        if (isset($_SESSION['mensajes'])) {
            $this->_mensajes = $_SESSION['mensajes'];
            return true;
        }
        return false;
    }

    /**
     * Retorna los mensajes de un determinado tipo.
     * @param string $tipo_mensaje
     * @return array
     */
    public function mostrar_mensaje($tipo_mensaje) {
        if (isset($_SESSION['mensajes'])) {
            $this->_mensajes = $_SESSION['mensajes'];
            return $this->_mensajes[$tipo_mensaje];
        }
        return null;
    }

    /**
     * Retorna todos los mensajes.
     * @return array
     */
    public function get_mensajes() {
        return $this->_mensajes;
    }

    /**
     * Limpia los mensajes de un determinado tipo o bien todos.
     * @param string $tipo_mensaje
     */
    public function limpiar_mensajes($tipo_mensaje = 'todos') {
        if ($tipo_mensaje === 'todos') {
            unset($_SESSION['mensajes']);
            $this->_mensajes = array();
        } else {
            $this->_mensajes[$tipo_mensaje] = array();
            $_SESSION['mensajes'] = $this->_mensajes;
        }
    }

    public function recordar_ultima_web($web = '') {
        if (empty($web)) {
            $this->_ultima_web = $_SERVER['REQUEST_URI'];
        } else {
            $this->_ultima_web = $web;
        }
        $_SESSION['ultima_web'] = $this->_ultima_web;
    }

    public function volver_ultima_web() {
        if (isset($_SESSION['ultima_web'])) {
            $this->_ultima_web = $_SESSION['ultima_web'];
            Util::ir($this->_ultima_web);
        } else {
            Util::ir_inicio();
        }
    }

    public function get_ultima_web() {
        global $RUTA_WEB;
        if (isset($this->_ultima_web)) {
            return $this->_ultima_web;
        } else {
            return $RUTA_WEB . "/index.php";
        }
    }

    /**
     * Retorna la ip del usuario.
     * @return string
     */
    public function get_ip() {
        return filter_input(INPUT_SERVER, "REMOTE_ADDR");
    }

}
