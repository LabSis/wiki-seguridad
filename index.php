<?php
session_start();
ini_set("display_errors", 1);
require_once 'src/conexion.class.php';
require_once 'src/api_bd.class.php';
Conexion::set_default_conexion("labsis_seg", Conexion::init("localhost", "root", "", "labsis_seg", true));
$tmpl_tecnicas = ApiBd::obtener_tecnicas();

/*
$tmpl_tecnicas = array(
    array(
        "nombre" => "Inyección",
        "links" => array(
            array(
                "href" => "tecnica.php?id=1",
                "nombre" => "Inyección SQL"
            ),
            array(
                "href" => "",
                "nombre" => "Inyección LDAP"
            ),
            array(
                "href" => "",
                "nombre" => "Inyección XML"
            ),
            array(
                "href" => "",
                "nombre" => "Inyección NoSQL"
            )
        )
    ),
    array(
        "nombre" => "Sitios cruzados",
        "links" => array(
            array(
                "href" => "tecnica.php?id=2",
                "nombre" => "XSS"
            ),
            array(
                "href" => "",
                "nombre" => "Falsificación de peticiones en sitios cruzados (CSRF)"
            )
        )
    ),
    array(
        "nombre" => "Control de acceso",
        "links" => array(
            array(
                "href" => "tecnica.php?id=2",
                "nombre" => "Pérdida de autenticación y gestión de sesiones"
            ),
            array(
                "href" => "",
                "nombre" => "Inexistente control de acceso a nivel de funcionalidades"
            ),
            array(
                "href" => "",
                "nombre" => "Exposición a datos sensibles"
            ),
            array(
                "href" => "",
                "nombre" => "Referencia directa insegura a objetos"
            )
        )
    ),
    array(
        "nombre" => "Configuración",
        "links" => array(
            array(
                "href" => "tecnica.php?id=2",
                "nombre" => "Configuración de seguridad incorrecta"
            ),
            array(
                "href" => "",
                "nombre" => "Uso de componentes con vulnerabilidades conocidas"
            )
        )
    ),
    array(
        "nombre" => "Inclusión",
        "links" => array(
            array(
                "href" => "tecnica.php?id=2",
                "nombre" => "LFI"
            ),
            array(
                "href" => "",
                "nombre" => "RFI"
            )
        )
    )
);*/
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Tec Bucket</title>
        <link href="css/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
        <link href="css/estilo.css" rel="stylesheet" />
        <script src="js/jquery.js"></script>
        <script src="css/bootstrap/js/bootstrap.min.js"></script>
    </head>
    <body>
        <main class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="row">
                        <?php foreach ($tmpl_tecnicas as $tmpl_tecnica): ?>
                            <div class="col-sm-3">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h3 class="panel-title"><?php echo $tmpl_tecnica["nombre"]; ?></h3>
                                    </div>
                                    <div class="panel-body">
                                        <ul>
                                            <?php foreach ($tmpl_tecnica["links"] as $link): ?>
                                                <li>
                                                    <a href="tecnica.php?id=<?php echo $link["href"]; ?>">
                                                        <?php echo $link["nombre"]; ?>
                                                    </a>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </main>
    </body>
</html>
