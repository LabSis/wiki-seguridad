<?php
require_once 'config.php';

ini_set("display", "errors");

try {
	$ok = ApiBd::crear_tecnica("Permanencia", null);
	$ok = ApiBd::crear_tecnica("Criptografía", null);
	echo "_";
	echo $ok;
} catch (Exception $e) {
	echo $e;
}
