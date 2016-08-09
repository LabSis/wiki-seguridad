<?php

require_once 'config.php';

$sesion = Session::get_instance();

ini_set("display_errors", 1);
$tmpl_tecnicas = ApiBd::obtener_tecnicas();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <link href="css/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
        <link href="css/general.css" rel="stylesheet" />
        <script src="js/jquery.js"></script>
        <script src="css/bootstrap/js/bootstrap.min.js"></script>
        <title>LabSis - Seg</title>
    </head>
    <body>
        <main class="container">
            <h1>LabSis - Seg</h1>
            <h3>Técnicas de ataques</h3>
            <div class="row">
                <div class="col-sm-12">
                    <?php $i = 1 ?>
                    <?php foreach ($tmpl_tecnicas as $tmpl_tecnica): ?>
                        <?php if ($i % 4 == 1): ?>
                            <div class="row">
                        <?php endif; ?>
                        <div class="col-sm-3">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title"><?php echo $tmpl_tecnica["nombre"]; ?></h3>
                                </div>
                                <div class="panel-body">
                                    <ul>
                                        <?php foreach ($tmpl_tecnica["links"] as $link): ?>
                                            <li>
                                                <a href="src/tecnica.php?id=<?php echo $link["href"]; ?>">
                                                    <?php echo $link["nombre"]; ?>
                                                </a>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <?php if ($i % 4 == 0): ?>
                            </div>
                        <?php endif; ?>
                        <?php $i++ ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </main>
    </body>
</html>
