<?php

require_once 'config.php';

$sesion = Session::get_instance();

$tmpl_tecnicas = ApiBd::obtener_tecnicas();
$tmpl_algoritmos = ApiBd::obtener_algoritmos();
$tmpl_vulnerabilidades = ApiBd::obtener_vulnerabilidades();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="icon" href="css/favicon.png">
    <link href="css/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    <link href="css/general.css" rel="stylesheet" />
    <link href='css/footer-distributed.css' rel="stylesheet"/>
    <script src="js/jquery.js"></script>
    <script src="css/bootstrap/js/bootstrap.min.js"></script>
    <script src="js/chart.js"></script>
    <title>LabSis - Wiki de seguridad</title>
    <script type="text/javascript">
        $(document).ready(function(){
            $(".agregar-tecnica").click(function(){
                var webPath = $("#webPath").val();

                $("#modalTitle").text("Agregar técnica");
                $("#hidTechniqueParentId").val("");
                $("#txtTechniqueName").val("");
                $("#modalTechnique").find("form").attr("action", webPath + "src/agregar_tecnica.php?tipo=tecnica");
                $("#modalTechnique").find("#submit").text("Crear");

                $("#modalTechnique").modal("show");
            });

            $(".agregar-algoritmo").click(function(){
                var webPath = $("#webPath").val();

                $("#modalTitle").text("Agregar algoritmo, mecanismo o programa");
                $("#hidTechniqueParentId").val("");
                $("#txtTechniqueName").val("");
                $("#modalTechnique").find("form").attr("action", webPath + "src/agregar_tecnica.php?tipo=algoritmo");
                $("#modalTechnique").find("#submit").text("Crear");

                $("#modalTechnique").modal("show");
            });

            $(".agregar-sub-tecnica").click(function(){
                var techniqueId = $(this).data("techniqueId");
                var webPath = $("#webPath").val();

                $("#modalTitle").text("Agregar técnica");
                $("#hidTechniqueParentId").val(techniqueId);
                $("#txtTechniqueName").val("");
                $("#modalTechnique").find("form").attr("action", webPath + "src/agregar_tecnica.php?tipo=tecnica");
                $("#modalTechnique").find("#submit").text("Crear");

                $("#modalTechnique").modal("show");
            });

            $(".editar-sub-tecnica").click(function(){
                var techniqueId = $(this).data("techniqueId");
                var techniqueName = $(this).data("techniqueName");
                var webPath = $("#webPath").val();

                $("#modalTitle").text("Editar técnica");
                $("#hidTechniqueId").val(techniqueId);
                $("#txtTechniqueName").val(techniqueName);
                $("#modalTechnique").find("form").attr("action", webPath + "src/editar_tecnica.php?tipo=tecnica");
                $("#modalTechnique").find("#submit").text("Editar");

                $("#modalTechnique").modal("show");
            });

            $(".agregar-sub-algoritmo").click(function(){
                var techniqueId = $(this).data("algorithmId");
                var webPath = $("#webPath").val();

                $("#modalTitle").text("Agregar algoritmo, mecanismo o programa");
                $("#hidTechniqueParentId").val(techniqueId);
                $("#txtTechniqueName").val("");
                $("#modalTechnique").find("form").attr("action", webPath + "src/agregar_tecnica.php?tipo=algoritmo");
                $("#modalTechnique").find("#submit").text("Crear");

                $("#modalTechnique").modal("show");
            });

            $(".editar-sub-algoritmo").click(function(){
                var techniqueId = $(this).data("algorithmId");
                console.log(techniqueId);
                var techniqueName = $(this).data("algorithmName");
                var webPath = $("#webPath").val();

                $("#modalTitle").text("Editar algoritmo, mecanismo o programa");
                $("#hidTechniqueId").val(techniqueId);
                $("#txtTechniqueName").val(techniqueName);
                $("#modalTechnique").find("form").attr("action", webPath + "src/editar_tecnica.php?tipo=algoritmo");
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
                        data: cantidades,
                        backgroundColor: '#FF7F50'
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
                        tooltips: {
                            callbacks: {
                                label: function (args) {
                                    return "Cantidad: " + args.yLabel;
                                }
                            }
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
            
            // Código para funcionamiento de Drag & Drop, basado en: https://www.html5rocks.com/es/tutorials/dnd/basics/
            
            var $elementoInicialDND = null;
            var $contenedorInicialDND = null;
            var $contenedorFinalDND = null;
            
            function onDragStart($elem, event) {
                $elem.css("opacity", "0.4");
                
                $elementoInicialDND = $elem;
                $contenedorInicialDND = $elem.parents(".panel-dnd");
                event.dataTransfer.effectAllowed = 'move';
                event.dataTransfer.setData('text/html', $elem.html());
            }
            
            function onDragEnd($elem, event) {
                $elem.css("opacity", "1");
            }
            
            function onDragEnter(e) {
                $(this).addClass("dnd-over");
            }
            
            function onDragOver(e) {
                if (e.preventDefault) {
                    e.preventDefault();
                }
                e.dataTransfer.dropEffect = 'move';
            }
            
            function onDragLeave(e) {
                $(this).removeClass("dnd-over");
            }
            
            function onDrop(e) {
                if (e.stopPropagation) {
                    e.stopPropagation();
                }
                
                $elementoInicialDND.remove();
                
                $(this).removeClass("dnd-over");
                var idNuevoPadre = $(this).data("algorithm-id");
                var idAlgoritmo = $elementoInicialDND.data("id");
                
                var $listaEnlacesAArticulos = $(this).find(".lista-enlaces-a-articulos-dnd");
                if ($listaEnlacesAArticulos != null && $listaEnlacesAArticulos.length > 0) {
                    var innerHTMLSourceElemento = e.dataTransfer.getData('text/html');
                    console.log(innerHTMLSourceElemento);
                    $.ajax({
                        url: "src/ajax/mover_algoritmo.php",
                        type: "POST",
                        data: {
                            id_algoritmo: idAlgoritmo,
                            id_nuevo_padre: idNuevoPadre
                        },
                        success: function(r) {
                            var respuesta = JSON.parse(r);
                            if (respuesta != null && respuesta.estado === "ok") {
                                
                                var $li = $("<li class='enlace-a-articulo-dnd' data-id='" + idAlgoritmo + "'></li>");
                                $li.html(innerHTMLSourceElemento);
                                $listaEnlacesAArticulos.append($li);
                            }
                        },
                        error: function(r) {
                            var respuesta = JSON.parse(r);
                            alert(respuesta.mensaje);
                        }
                    });
                }
                
                return false;
            }
            
            $(document).on("dragstart", ".lista-enlaces-a-articulos-dnd .enlace-a-articulo-dnd", function (event) {
                onDragStart($(this), event.originalEvent);
            });
            
            $(document).on("dragend", ".lista-enlaces-a-articulos-dnd .enlace-a-articulo-dnd", function () {
                onDragEnd($(this), event.originalEvent);
            });
            
            var contenedoresFinales = document.querySelectorAll('.panel-dnd');
            [].forEach.call(contenedoresFinales, function(elem) {
                console.log("call");
                elem.addEventListener('dragenter', onDragEnter, false);
                elem.addEventListener('dragover', onDragOver, false);
                elem.addEventListener('dragleave', onDragLeave, false);
                elem.addEventListener('drop', onDrop, false);
            });
            
            
        });
    </script>
</head>
<body>
<?php require_once('header.php') ?>
<div class="container">

    <p>La presente wiki se compone de contenido de seguridad informática. Cualquier error puede escribirnos por Twitter a @SecLabsis.</p>
    <h3>
        <?php if ($sesion->is_active()): ?>
            <i class="agregar-tecnica boton-agregar glyphicon glyphicon-plus" title="Agregar" data-technique-id=""></i>
        <?php endif; ?>
        Técnicas de ataques
    </h3>
    <br/>
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
                            <?php if ($sesion->is_active()): ?>
                                <i class="agregar-sub-tecnica boton-agregar glyphicon glyphicon-plus" title="Agregar" data-technique-id="<?php echo $tmpl_tecnica["id"] ?>"></i>
                                <i class="editar-sub-tecnica boton-editar glyphicon glyphicon-edit" title="Editar" data-technique-id="<?php echo $tmpl_tecnica["id"] ?>" data-technique-name="<?php echo $tmpl_tecnica["nombre"] ?>"></i>
                            <?php endif; ?>
                            <h3 class="panel-title">
                                <span><?php echo $tmpl_tecnica["nombre"]; ?></span>
                            </h3>
                        </div>
                        <div class="panel-body">
                            <ul>
                                <?php foreach ($tmpl_tecnica["links"] as $link): ?>
                                    <li>
                                        <?php if ($sesion->is_active()): ?>
                                            <i class="editar-sub-tecnica boton-editar glyphicon glyphicon-edit" title="Editar" data-technique-id="<?php echo $link["href"] ?>" data-technique-name="<?php echo $link["nombre"] ?>"></i>
                                        <?php endif; ?>
                                        <a href="src/contenedor.php?id=<?php echo $link["href"]; ?>&tipo=tecnica">
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
            <?php if ($i % 4 != 1): ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <h3>
        <?php if ($sesion->is_active()): ?>
            <i class="agregar-algoritmo boton-agregar glyphicon glyphicon-plus" title="Agregar" data-algorithm-id=""></i>
        <?php endif; ?>
        Algoritmos, protocolos y programas
    </h3>
    <br/>
    <div class="row">
        <div class="col-sm-12">
            <?php $i = 1 ?>
            <?php foreach ($tmpl_algoritmos as $tmpl_algoritmo): ?>
                <?php if ($i % 4 == 1): ?>
                    <div class="row">
                <?php endif; ?>
                <div class="col-sm-3">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <?php if ($sesion->is_active()): ?>
                                <i class="agregar-sub-algoritmo boton-agregar glyphicon glyphicon-plus" title="Agregar" data-algorithm-id="<?php echo $tmpl_algoritmo["id"] ?>"></i>
                                <i class="editar-sub-algoritmo boton-editar glyphicon glyphicon-edit" title="Editar" data-algorithm-id="<?php echo $tmpl_algoritmo["id"] ?>" data-algorithm-name="<?php echo $tmpl_algoritmo["nombre"] ?>"></i>
                            <?php endif; ?>
                            <h3 class="panel-title">
                                <span><?php echo $tmpl_algoritmo["nombre"]; ?></span>
                            </h3>
                        </div>
                        <div class="panel-body panel-dnd" data-algorithm-id="<?php echo $tmpl_algoritmo["id"] ?>">
                            <ul class="lista-enlaces-a-articulos-dnd">
                                <?php foreach ($tmpl_algoritmo["links"] as $link): ?>
                                    <li class="enlace-a-articulo-dnd" data-id="<?php echo $link["href"]; ?>">
                                        <?php if ($sesion->is_active()): ?>
                                            <i class="editar-sub-algoritmo boton-editar glyphicon glyphicon-edit" title="Editar" data-algorithm-id="<?php echo $link["href"] ?>" data-algorithm-name="<?php echo $link["nombre"] ?>"></i>
                                        <?php endif; ?>
                                        <a href="src/contenedor.php?id=<?php echo $link["href"]; ?>&tipo=algoritmo">
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
            <?php if ($i % 4 != 1): ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <h3>Métricas de vulnerabilidades encontradas</h3>
            <br/>
            <p>
                La siguiente tabla muestra una clasificación de vulnerabildades de acuerdo a una categorización basada en el top 10 de OWASP (<a href='https://www.owasp.org/index.php/Top_10_2013-Top_10'>2013</a> y <a href='https://www.owasp.org/images/7/72/OWASP_Top_10-2017_%28en%29.pdf.pdf'>2017</a>) y a la etapa en la cual esa vulnerabilidad fue generada.
                Los datos fueron extraídos de experiencias realizadas por el equipo de desarrollo y seguridad informática del LabSis de UTN-FRC.
            </p>
            <div class="row">
                <div class="col-sm-7" style="overflow-x: auto">
                    <table class="table table-striped table-responsive" style="width: auto !important;">
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
                                <td class="cantidad-disenio" data-etapa="disenio" style="min-width: 75px">
                                    <?php echo $tmpl_vulnerabilidad["disenio"] ?>
                                    <?php if($sesion->is_active()): ?>
                                        <input type="button" value="-" class="btn btn-primary btn-xs btn-disminuir" />
                                        <input type="button" value="+" class="btn btn-primary btn-xs btn-aumentar" />
                                    <?php endif; ?>
                                </td>
                                <td class="cantidad-codigo" data-etapa="desarrollo" style="min-width: 75px">
                                    <?php echo $tmpl_vulnerabilidad["codigo"] ?>
                                    <?php if($sesion->is_active()): ?>
                                        <input type="button" value="-" class="btn btn-primary btn-xs btn-disminuir" />
                                        <input type="button" value="+" class="btn btn-primary btn-xs btn-aumentar" />
                                    <?php endif; ?>
                                </td>
                                <td class="cantidad-configuracion" data-etapa="despliegue" style="min-width: 75px">
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
                </div>
                <div class="col-sm-5">
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
        </div>
    </div>


    <div class="row">
        <div class="col-xs-12">
            <h3>Glosario</h3>
            <br/>
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
                <dd>Es una posible interacción entre el software y uno o más actores donde los resultados de la ejecución es dañina para el sistema, para uno o varios de los actores o para los stakeholders pero sin saber exactamente cómo podría suceder dicha interacción.</dd>

                <dt>Agentes de amenaza</dt>
                <dd>Es el interesado en atacar a la aplicación. La detección de este rol es crucial para determinar el nivel de defensa necesario.</dd>

                <dt>Caso de abuso</dt>
                <dd>Es una especificación de la interacción entre el sistema y uno o más actores donde los resultados de la ejecución es dañina para el sistema, para uno o varios de los actores o para los stakeholders.</dd>
            </dl>

            <h3>Enlaces de interés</h3>
            <br/>
            <ul>
                <li><a href="https://www.owasp.org">OWASP: The Open Web Application Security Project</a></li>
                <li><a href="https://www.first.org/cvss/calculator/3.0">Calculadora CVSS v3.0</a></li>
            </ul>
        </div>
    </div>
</div>
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
