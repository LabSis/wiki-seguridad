<?php

require_once 'config.php';

$sesion = Session::get_instance();

$tmpl_tecnicas = ApiBd::obtener_tecnicas();
$tmpl_vulnerabilidades = ApiBd::obtener_vulnerabilidades();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <link href="css/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
        <link href="css/general.css" rel="stylesheet" />
        <link href='css/footer-distributed.css' rel="stylesheet"/>
        <script src="js/jquery.js"></script>
        <script src="css/bootstrap/js/bootstrap.min.js"></script>
        <script src="js/chart.js"></script>
        <script src="js/modulo_ventana.js"></script>
        <title>LabSis - Pentesting</title>
        <script type="text/javascript">
            $(document).ready(function(){
                $(".add").click(function(){
                    var techniqueId = $(this).data("techniqueId");
                    var webPath = $("#webPath").val();

                    $("#modalTitle").text("Agregar técnica");
                    $("#hidTechniqueParentId").val(techniqueId);
                    $("#txtTechniqueName").val("");
                    $("#modalTechnique").find("form").attr("action", webPath + "src/add_technique.php");
                    $("#modalTechnique").find("#submit").text("Crear");

                    $("#modalTechnique").modal("show");
                });
                $(".edit").click(function(){
                    var techniqueId = $(this).data("techniqueId");
                    var techniqueName = $(this).data("techniqueName");
                    var webPath = $("#webPath").val();

                    $("#modalTitle").text("Editar técnica");
                    $("#hidTechniqueId").val(techniqueId);
                    $("#txtTechniqueName").val(techniqueName);
                    $("#modalTechnique").find("form").attr("action", webPath + "src/edit_technique.php");
                    $("#modalTechnique").find("#submit").text("Editar");
                    
                    $("#modalTechnique").modal("show");
                });

                // Calcular la columna total de vulnerabilidades.
                var dimensionesCantidades = [];
                var dimensionesEtiquetas = [];
                var categoriasCantidades = [];
                var categoriasEtiquetas = [];
                $(".total-fila").each(function() {
                    var $this = $(this);
                    var $tr = $this.parent("tr");
                    var cantidadDisenio = parseInt($tr.find(".cantidad-disenio").text());
                    var cantidadCodigo = parseInt($tr.find(".cantidad-codigo").text());
                    var cantidadConfiguracion = parseInt($tr.find(".cantidad-configuracion").text());
                    var cantidadTotal = cantidadDisenio + cantidadCodigo + cantidadConfiguracion;
                    $this.text(cantidadTotal);
                    categoriasCantidades.push(cantidadTotal);

                    // Pongo en un array las etiquetas.
                    var etiqueta = $tr.find(".titulo-categoria").text().trim();
                    etiqueta = etiqueta.substring(0, 12);
                    categoriasEtiquetas.push(etiqueta);
                });
                $(".total-columna").each(function(){
                    var $this = $(this);
                    var $tbody = $this.parents("table").find("tbody");
                    var cantidad = 0;
                    $tbody.find("tr td:nth-child(" + ($this.index() + 1) + ")").each(function(){
                        cantidad += parseInt($(this).text());
                    });
                    $this.text(cantidad);

                    // Pongo en un array las etiquetas.
                    var $thead = $this.parents("table").find("thead");
                    $thead.find("tr");
                    var etiqueta = $thead.find("tr th:nth-child(" + ($this.index() + 1) + ")").text().trim();
                    if (etiqueta !== "Total") {
                        dimensionesEtiquetas.push(etiqueta);
                        dimensionesCantidades.push(cantidad);
                    }
                });
                
                // Gráficos.
                function generarGraficoDeDimensiones(ctx, cantidades, etiquetas) {
                    var data = {
                        datasets: [{
                            data: cantidades,
                            backgroundColor: [
                                '#E14828',
                                '#2318A6',
                                '#47760E'
                            ]
                        }],
                        labels: etiquetas
                    };
                    var torta = new Chart(ctx,{
                        type: 'pie',
                        data: data,
                        options: []
                    });
                }
                function generarGraficoDeCategorias(ctx, cantidades, etiquetas) {
                    var data = {
                        datasets: [{
                            data: cantidades
                        }],
                        labels: etiquetas
                    };
                    var barra = new Chart(ctx,{
                        type: 'bar',
                        data: data,
                        options: {
                            legend: {
                                display: false
                            },
                            scales: {
                                yAxes: [{
                                    display: true,
                                    ticks: {
                                        beginAtZero: true
                                    }
                                }]
                            }
                        }
                    });
                }
                var ctx1 = $("#canvas1");
                generarGraficoDeDimensiones(ctx1, dimensionesCantidades, dimensionesEtiquetas);
                var ctx2 = $("#canvas2");
                generarGraficoDeCategorias(ctx2, categoriasCantidades, categoriasEtiquetas);

                // Botón para disminuir o aumentar en uno la cantidad.
                function cambiarCantidadVulnerabilidad(idVulnerabilidad, etapa, cantidad) {
                    ModuloVentana.mostrar({
                        titulo: "Atención",
                        contenido: "¿Está seguro que desea cambiar la cantidad?",
                        tipo: ModuloVentana.TIPO.SI_NO,
                        callbacks: {
                            si: function () {
                                $.ajax({
                                    url: "src/ajax/cambiar_cantidad_vulnerabilidad.php",
                                    type: "POST",
                                    data: {
                                        id_vulnerabilidad: idVulnerabilidad,
                                        etapa: etapa,
                                        cantidad: cantidad
                                    },
                                    success: function(r) {
                                        if (r === "Ok") {
                                            location.href = "";
                                        }
                                    },
                                    error: function(r) {
                                        if (r === "Ok") {
                                            location.href = "";
                                        }
                                    }
                                });
                            },
                        },
                        cerrar: [ModuloVentana.CERRAR.BOTON_CIERRE, ModuloVentana.CERRAR.TECLA_ESCAPE, ModuloVentana.CERRAR.FUERA_MODAL, ModuloVentana.CERRAR.CADA_ACCION]
                    });
                }
                $(".btn-disminuir").click(function() {
                    var id = $(this).parents("tr").attr("id");
                    var etapa = $(this).parent().data("etapa");
                    cambiarCantidadVulnerabilidad(id, etapa, -1);
                });

                $(".btn-aumentar").click(function() {
                    var id = $(this).parents("tr").attr("id");
                    var etapa = $(this).parent().data("etapa");
                    cambiarCantidadVulnerabilidad(id, etapa, 1);
                });
            });
        </script>
    </head>
    <body>
        <?php require_once('header.php') ?>
        <main class="container">
            <h3>Vulnerabilidades</h3>
            <p>
                La siguiente tabla muestra una clasificación de vulnerabildades de acuerdo a una categorización basada en el top 10 de OWASP (<a href='https://www.owasp.org/index.php/Top_10_2013-Top_10'>2013</a> y <a href='https://www.owasp.org/images/7/72/OWASP_Top_10-2017_%28en%29.pdf.pdf'>2017</a>) y a la etapa en la cual esa vulnerabilidad fue generada.
                Los datos fueron extraídos de experiencias realizadas por el equipo de desarrollo y seguridad informática del LabSis de UTN-FRC.
            </p>
            <div class="row">
                <table class="table table-striped col-sm-8" style="width: auto !important;">
                    <thead>
                        <tr>
                            <th>Categoría</th>
                            <th>Diseño</th>
                            <th>Desarrollo</th>
                            <th>Despliegue</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tmpl_vulnerabilidades as $tmpl_vulnerabilidad): ?>
                            <tr id="<?php echo $tmpl_vulnerabilidad["id"] ?>">
                                <td class="titulo-categoria">
                                    <a href="src/contenedor.php?id=<?php echo $tmpl_vulnerabilidad["id"] ?>&tipo=vulnerabilidad">
                                        <?php echo $tmpl_vulnerabilidad["nombre"] ?>
                                    </a>
                                </td>
                                <td class="cantidad-disenio" data-etapa="disenio">
                                    <?php echo $tmpl_vulnerabilidad["disenio"] ?>
                                    <?php if($sesion->is_active()): ?>
                                        <input type="button" value="-" class="btn btn-primary btn-xs btn-disminuir" />
                                        <input type="button" value="+" class="btn btn-primary btn-xs btn-aumentar" />
                                    <?php endif; ?>
                                </td>
                                <td class="cantidad-codigo" data-etapa="desarrollo">
                                    <?php echo $tmpl_vulnerabilidad["codigo"] ?>
                                    <?php if($sesion->is_active()): ?>
                                        <input type="button" value="-" class="btn btn-primary btn-xs btn-disminuir" />
                                        <input type="button" value="+" class="btn btn-primary btn-xs btn-aumentar" />
                                    <?php endif; ?>
                                </td>
                                <td class="cantidad-configuracion" data-etapa="despliegue">
                                    <?php echo $tmpl_vulnerabilidad["configuracion"] ?>
                                    <?php if($sesion->is_active()): ?>
                                        <input type="button" value="-" class="btn btn-primary btn-xs btn-disminuir" />
                                        <input type="button" value="+" class="btn btn-primary btn-xs btn-aumentar" />
                                    <?php endif; ?>
                                </td>
                                <td class="total-fila">
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td>Total</td>
                            <td class="total-columna"></td>
                            <td class="total-columna"></td>
                            <td class="total-columna"></td>
                            <td class="total-columna"></td>
                        </tr>
                    </tfoot>
                </table>
                <div class="col-sm-4">
                    <div class="row">
                        <div class="col-sm-12">
                            <canvas id="canvas1" height="250" width="520"></canvas>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <canvas id="canvas2" height="600" width="820"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <h3>Técnicas de ataques<i class="add glyphicon glyphicon-plus" title="Agregar" ></i></h3>
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
                                    <h3 class="panel-title">
                                        <?php echo $tmpl_tecnica["nombre"]; ?>
                                        <?php if ($sesion->is_active()): ?>
                                            <i class="add glyphicon glyphicon-plus" title="Agregar" data-technique-id="<?php echo $tmpl_tecnica["id"] ?>"></i>
                                            <i class="edit glyphicon glyphicon-edit" title="Editar" data-technique-id="<?php echo $tmpl_tecnica["id"] ?>" data-technique-name="<?php echo $tmpl_tecnica["nombre"] ?>"></i>
                                        <?php endif; ?>
                                    </h3>
                                </div>
                                <div class="panel-body">
                                    <ul>
                                        <?php foreach ($tmpl_tecnica["links"] as $link): ?>
                                            <li>
                                                <a href="src/contenedor.php?id=<?php echo $link["href"]; ?>&tipo=tecnica">
                                                    <?php echo $link["nombre"]; ?>
                                                </a>
                                                <?php if ($sesion->is_active()): ?>
                                                    <i class="edit glyphicon glyphicon-edit" title="Editar" data-technique-id="<?php echo $link["href"] ?>" data-technique-name="<?php echo $link["nombre"] ?>"></i>
                                                <?php endif; ?>
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

            <h3>Glosario</h3>
            <dl class="dl-horizontal">
                <dt>Vulnerabilidad</dt>
                <dd>Una vulnerabilidad es una debilidad de una aplicación, la cual, puede ser una falla de diseño, un error de desarrollo o una mala configuración en el despliegue que puede provocar o ayudar a provocar un daño a los interesados de la misma. Los interesados incluyen al propietario de la aplicación, usuarios de la aplicación y a otras entidades que confían en la aplicación.</dd>

                <dt>Técnica de ataque</dt>
                <dd>Es una abstracción que abarca uno o más pasos para encontrar o explotar una vulnerabilidad en una aplicación.</dd>

                <dt>Vector de ataque</dt>
                <dd>Es la implementación de una técnica de ataque teniendo en cuenta el contexto de la aplicación objetivo.</dd>

                <dt>Pentesting</dt>
                <dd>Son ataques simulados a una aplicación lo que permite evaluar la seguridad de la misma. Debe haber un acuerdo entre ambas partes (pentester y cliente) y se debe definir los lineamientos del pentesting.</dd>

                <dt>Metodología</dt>
                <dd>Es un conjunto de procedimientos ordenados que permiten ejecutar un pentesting.</dd>

                <dt>Herramienta de ataque</dt>
                <dd>Permite automatizar parcial o totalmente la ejecución de una o más técnicas de ataque.</dd>

                <dt>Herramienta de defensa</dt>
                <dd>Permite defender una aplicación contra técnicas de ataque ya sea detectando, disminuyendo o evitando el impacto de la misma.</dd>

                <dt>Riesgo</dt>
                <dd>El riesgo es el producto del impacto y de la probabilidad para una determinada vulnerabilidad. El impacto puede ser diferente desde el punto de vista técnico como al negocio, por eso, puede ser necesario diferenciar el impacto técnico del impacto al negocio.</dd>

                <dt>Score de vulnerabilidad</dt>
                <dd>Cada vulnerabilidad debe ser puntuada, de esa manera se puede establecer una lista de prioridades. Por ejemplo, un sistema de puntuación es el <a href="https://www.first.org/cvss/calculator/3.0">CVSS</a> (Common Vulnerability Scoring System).</dd>

                <dt>Amenaza</dt>
                <dd>Es un posible riesgo que no se sabe si técnicamente se pudiera cumplir.</dd>

                <dt>Agentes de amenaza</dt>
                <dd>Es el interesado en atacar a la aplicación. La detección de este rol es crucial para determinar el nivel de defensa necesario.</dd>

                <dt>Caso de abuso</dt>
                <dd>Es un escenario no común de uno o más casos de uso que un usuario con conocimientos avanzados podría ejecutar.</dd>
            </dl>

            <h3>Enlaces de interés</h3>
            <ul>
                <li><a href="https://www.owasp.org">OWASP: The Open Web Application Security Project</a></li>
                <li><a href="https://www.first.org/cvss/calculator/3.0">Calculadora CVSS v3.0</a></li>
            </ul>
        </main>
        <?php require_once 'footer.php' ?>

        <div class="modal fade" id="modalTechnique" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <form action="<?php echo $WEB_PATH ?>src/add_technique.php" method="POST">
                        <input type="hidden" value="" name="hidTechniqueId" id="hidTechniqueId" />
                        <input type="hidden" value="" name="hidTechniqueParentId" id="hidTechniqueParentId" />
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="modalTitle" >Agregar técnica</h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="comment">Nombre:</label>
                                <input type="text" class="form-control" name="txtTechniqueName" id="txtTechniqueName">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                            <button type="submit" id="submit" class="btn btn-primary">Crear</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>
