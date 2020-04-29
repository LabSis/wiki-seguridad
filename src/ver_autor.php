<?php
require_once '../config.php';

$sesion = Session::get_instance();

$metodo = filter_input(INPUT_SERVER, "REQUEST_METHOD");
if (strcasecmp($metodo, "POST") === 0) {

    //header("Location: tecnica.php?id=$id_contenedor");
} else if (strcasecmp($metodo, "GET") === 0) {
    $id_autor = filter_input(INPUT_GET, "id_autor", FILTER_SANITIZE_SPECIAL_CHARS, FILTER_VALIDATE_INT);
    if(isset($id_autor)){
        $autor = ApiBd::obtener_autor($id_autor);
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Autor</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="<?php echo $WEB_PATH ?>css/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    <link href="<?php echo $WEB_PATH ?>css/general.css" rel="stylesheet" />
    <link href='<?php echo $WEB_PATH ?>css/footer-distributed.css' rel="stylesheet"/>
    <script type="text/javascript" src="<?php echo $WEB_PATH ?>/js/jquery.js"></script>
    <script type="text/javascript" src="<?php echo $WEB_PATH ?>/js/ckeditor/ckeditor.js"></script>
    <script type="text/javascript" src="<?php echo $WEB_PATH ?>/css/bootstrap/js/bootstrap.min.js"></script>
    <script>

    </script>
</head>
<body>
<?php require_once('../header.php') ?>
<main class="container">
    <?php require_once $SERVER_PATH . $TEMPLATES_REL_PATH . 'maquetado/menu.tmpl.php' ?>
    <div class="row">
        <div class="col-sm-12">
            <h2>Autor</h2>
            <hr/>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-md-6">
            <h4>Nombre: <?php echo $autor['nombre'] ?></h4>

            <?php if (isset($autor['alias']) && !empty($autor['alias'])) { ?>
                <h4>Alias: <?php echo $autor['alias'] ?> </h4>
            <?php } ?>
            <?php if (isset($autor['email']) && !empty($autor['email'])) { ?>
                <h4>Email: <a href="mailto: <?php echo $autor['email'] ?>"><?php echo $autor['email'] ?></a></h4>
            <?php } ?>
            <?php if (isset($autor['usuario_github']) && !empty($autor['usuario_github'])) { ?>
                <h4>GitHub: <a href="https://github.com/<?php echo $autor['usuario_github'] ?>">Github</a></h4>
            <?php } ?>
        </div>
        <div class="col-xs-12 col-md-6">
            <?php if (isset($autor['foto']) && !empty($autor['foto'])) { ?>

            <?php } ?>
        </div>


    </div>
</main>
<?php require_once '../footer.php' ?>
</body>
</html>

