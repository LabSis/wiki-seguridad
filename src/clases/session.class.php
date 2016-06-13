<?php

/**
 * Clase singleton que representa la sesión actual del usuario activo. Se 
 * encarga de acceder de manera controlada a la variable $_SESSION. 
 * Específicamente recuerda:
    * El usuario que inició sesión: esto es un objeto de la clase SessionUser.
    * Mensajes los cuales duran una petición salvo que se use la función
    redirect(), lo cual los guarda durante dos peticiones (3xx y 200).
    Para más información ver el archivo "mensajes" ubicado en este snippet.
    * La última web visitada (antes llamar a la función remember_last_web()).
    * La ip del cliente (automáticamente).
 *
 * Si el usuario es anónimo, o sea que no inició sesión, también se crea
 * una sesión pero de usuario anónimo.
 *
 * Para ver ejemplos de uso, ver el archivo "ejemplos" de este snippet.
 *
 * @author Diego Barrionuevo y Parisi Germán
 * @version 2.0
 */
class Session {

    const TYPE_INFORMATION_MESSAGE = 'info';
    const TYPE_SUCCESS_MESSAGE = 'success';
    const TYPE_WARNING_MESSAGE = 'warning';
    const TYPE_ERROR_MESSAGE = 'error';

    /**
     *
     * @var \Session
     */
    private static $_instance;

    /**
     * Usuario en la sesión. Si es null entonces el usuario es anónimo.
     * @var \SessionUser
     */
    private $_user;

    /**
     * Array de mensajes. Tiene un array por cada tipo de mensaje.
     * @var string
     */
    private $_messages;

    /**
     * La última web visitada.
     * @var string
     */
    private $_last_web;

    /**
     * Constructor privado. Solo es llamado por el método get_instance().
     */
    private function __construct() {
        $this->_user = null;
        $this->_last_web = null;
        $this->init_messages();
        if(isset($_SESSION['messagesSaved']) && $_SESSION['messagesSaved'] === true){
            $_SESSION['messagesSaved'] = false;
        } else {
            $this->clean_messages();
        }
    }

    private function init_messages(){
        $this->_messages = array(
            self::TYPE_INFORMATION_MESSAGE => array(),
            self::TYPE_SUCCESS_MESSAGE => array(),
            self::TYPE_WARNING_MESSAGE => array(),
            self::TYPE_ERROR_MESSAGE => array()
        );
    }

    /**
     * Retorna un objeto de la clase sesion.
     * @return \Session
     */
    public static function get_instance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new Session();
        }
        return self::$_instance;
    }

    /**
     * Carga un mensaje en la sesión para que esté disponible entre distintas 
     * webs.
     * @param mixed $message Puede ser un array de string o bien un string.
     * @param string $mensaje_type
     */
    public function add_message($message, $mensaje_type) {
        if (is_array($message)) {
            $this->_messages[$mensaje_type] = array_merge($this->_messages[$mensaje_type], $message);
        } else {
            $this->_messages[$mensaje_type][] = $message;
        }
        $_SESSION['messages'] = $this->_messages;
    }

    public function add_error_message($message) {
        $this->add_message($message, self::TYPE_ERROR_MESSAGE);
    }

    public function add_success_message($message) {
        $this->add_message($message, self::TYPE_SUCCESS_MESSAGE);
    }

    public function add_information_message($message) {
        $this->add_message($message, self::TYPE_INFORMATION_MESSAGE);
    }

    public function add_warning_message($message) {
        $this->add_message($message, self::TYPE_WARNING_MESSAGE);
    }

    /**
     * Determina si un usuario puede acceder a una página web.
     * @throw \Exception The server not found the variable 'PHP_SELF'.
     * @return boolean True si puede acceder, False en caso contrario.
     */
    public function can_access() {
        if($this->is_active()){
            if (isset($_SERVER['PHP_SELF'])) {
                if (isset($_SERVER['REQUEST_METHOD'])) {
                    return $this->_user->can_access($_SERVER['PHP_SELF'], $_SERVER['REQUEST_METHOD']);
                } else {
                    throw new SessionException("The server not found the variable 'REQUEST_METHOD'");
                }
            } else {
                throw new SessionException("The server not found the variable 'PHP_SELF'");
            }
        }
        return false;
    }

    /**
     * Limpia los mensajes de un determinado tipo o bien todos.
     * @param string $message_type
     */
    public function clean_messages($message_type = 'all') {
        if ($message_type === 'all') {
            $this->init_messages();
            $_SESSION['messages'] = $this->_messages;
        } else {
            $this->_messages[$message_type] = array();
            $_SESSION['messages'] = $this->_messages;
        }
    }

    /**
     * Retorna la ip del usuario.
     * @return string
     */
    public function get_ip() {
        return filter_input(INPUT_SERVER, "REMOTE_ADDR");
    }

    /**
     * Retorna todos los mensajes.
     * @return array
     */
    public function get_messages() {
        return $this->_messages;
    }

    /**
     * Retorna los mensajes de un determinado tipo.
     * @param string $message_type
     * @return array
     */
    public function get_messages_by_type($message_type) {
        if (isset($_SESSION['messages'])) {
            $this->_messages = $_SESSION['messages'];
            return $this->_messages[$message_type];
        }
        return null;
    }

    /**
     * Retorna el id de usuario.
     * return string
     */
    public function get_id() {
        $user = $this->_user;
        if (isset($user)) {
            return $user->get_id();
        } else {
            throw new SessionException("The anonymous user does not have id");
        }
    }

    /**
     * Retorna la última web visitada.
     *
     * Antes debió establecerse con el método remember_last_web()
     *
     */
    public function get_last_web() {
        if (isset($_SESSION['last_web'])) {
            return $_SESSION['last_web'];
        } else {
            return null;
        }
    }

    /**
     * Devuelve el usuario de la sesión actual
     *  
     * NOTA: No hace ningún control, por lo que puede devolver null.
     * 
     * @return \SessionUser El usuario de la sesión actual.
     */
    public function get_user() {
        if ($this->is_active()) {
            return $this->_user;
        }
        return null;
    }

    /**
     * Retorna el nombre de usuario.
     * @return string
     */
    public function get_user_name() {
        $user = $this->_user;
        if (isset($user)) {
            return $user->get_name();
        } else {
            throw new SessionException("The anonymous user does not have name");
        }
    }

    /**
     * Retorna true si hay mensajes agregados. False en caso contrario.
     * @return boolean 
     */
    public function has_messages() {
        if (isset($_SESSION['messages'])) {
            $this->_messages = $_SESSION['messages'];
            $all_messages = $this->_messages;
            $c = false;
            foreach($all_messages as $messages){
                $c = $c || !empty($messages);
            }
            return $c;
        }
        return false;
    }

    /**
     * Verifica que el usuario en la sesión actual de verdad exista, es decir 
     * verifica si hay un usuario ingresado. Es muy útil para verificar en cada 
     * página si el usuario ha ingresado como tal o no.
     * @return boolean True, si el usuario sí ha ingresado y su sesión aún no 
     * caducó. False, en caso contrario.
     */
    public function is_active() {
        if (isset($_SESSION['user'])) {
            $this->_user = $_SESSION['user'];
            return true;
        }
        return false;
    }

    /**
     * Establece el usuario en la sesión.
     *
     * Se debe llamar cuando inicia sesión el usuario.
     * 
     * @param \SessionUser $user
     * @throws Exception - Invalid argument in log_in method
     */
    public function log_in($user) {
        if (isset($user)) {
            $this->set_user($user);
        } else {
            throw new Exception("Invalid argument in log_in method");
        }
    }

    /**
     * Cierra la sesión actual del usuario.
     * 
     * NOTA: Elimina las variables de sesión, pero no las cookies de navegación.
     */
    public function log_out() {
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]
            );
        }
        session_unset();
        session_destroy();
    }

    /**
     * Redirecciona a otra URL.
     * Almacena temporalmente los mensajes esto permite que no se borren.
     *
     */
    public function redirect($url){
        $_SESSION['messagesSaved'] = true;
        header("Location: $url");
    }

    public function remember_last_web($web = '') {
        if (empty($web)) {
            $this->_last_web = $_SERVER['REQUEST_URI'];
        } else {
            $this->_last_web = $web;
        }
        $_SESSION['last_web'] = $this->_last_web;
    }

    /**
     *
     * Establece el usuario en la sesión. NO debería llamarse directamente.
     *
     * @param \SessionUser $usuario instancia de usuario.
     */
    public function set_user($user) {
        if (isset($user)) {
            $_SESSION['user'] = $user;
            $this->_user = $user;
        }
    }

    /**
     * Actualiza el usuario en la sesión, es decir vuelve a buscar los datos 
     * del usuario actualmente ingresado, y resetea todos sus datos, así la 
     * sesión tiene los datos fieles y reales del usuario al instante. Esto 
     * puede ser útil si el usuario agrega o modifica sus datos.
     * @return boolean True, si tuvo éxito al actualizar los datos del usuario. 
     * False en caso contrario.
     */
    public function update_user() {
        if ($this->is_active()) {
            $user = $this->_user->update_user();
            $this->_user = $user;
            $_SESSION["user"] = $this->_user;
            return true;
        }
        return false;
    }
}
class SessionException extends Exception{
    
}
