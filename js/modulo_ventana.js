/**
 * Módulo que customiza un modal llamando a callbacks por cada acción.
 * 
 * Versiones:
 * 1.0.1: Agrego tipo ACEPTAR.
 * 1.0.2: Arreglo parametro callbacks de la función mostrar.
 * 1.0.3: Agrego alineación justificada en el cuerpo del modal.
 * 
 * @version 1.0.3
 */
var ModuloVentana = (function () {
    var yo = {};
    var defecto = {
        tipo: "ACEPTAR_CANCELAR",
        funciones: {
            aceptar: function () {
                console.log("Aceptar presionado");
            },
            cancelar: function () {
                console.log("Cencelar presionado");
            },
            modalCerrado: function () {
                console.log("Modal cerrado");
            }
        },
        html: false,
        cerrar: [
            "BOTON_CIERRE", "CADA_ACCION"
        ],
        tamanio: "PEQUENIO",
        contenedor: $("body")
    };
    yo.mostrar = function (parametros) {
        var titulo = (parametros.titulo || null);
        var contenido = parametros.contenido;
        var tipo = (parametros.tipo || defecto.tipo);
        var callbacks = (parametros.callbacks || defecto.funciones);
        var extra = (parametros.extra || null);
        var tamanio = (parametros.tamanio || defecto.tamanio);
        var html = (parametros.html || defecto.html);
        var $contenedor = (parametros.contenedor || defecto.contenedor);
        var cerrar = (parametros.cerrar || defecto.cerrar);
        var $modal = graficar(titulo, contenido, tamanio, cerrar, tipo, html, callbacks, extra);
        $modal.modal("show");
        $contenedor.prepend($modal);
    };
    function graficar(titulo, contenido, tamanio, cerrar, tipo, html, callbacks, extra) {
        var $modal = $('<div class="modal fade"></div>');
        var $dialog = $('<div class="modal-dialog"></div>');
        var $content = $('<div class="modal-content"></div>');
        var $header = $('<div class="modal-header"></div>');
        var $title = $('<h4 class="modal-title"></h4>');
        var $closeButton = $('<button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>');
        var $body = $('<div class="modal-body"></div>');
        var $footer = $('<div class="modal-footer"></div>');
        if (tamanio === ModuloVentana.TAMANIO.PEQUENIO) {
            $dialog.addClass("modal-sm");
        } else if (tamanio === ModuloVentana.TAMANIO.GRANDE) {
            $dialog.addClass("modal-lg");
        }
        $dialog.append($content);
        if (titulo !== null) {
            $title.text(titulo);
            if (cerrar.indexOf(ModuloVentana.CERRAR.BOTON_CIERRE) !== -1) {
                $header.append($closeButton);
            }
            $header.append($title);
            $content.append($header);
        }
        if (html === true) {
            $body.html(contenido);
        } else {
            $body.text(contenido);
        }
        $body.css("text-align", "justify");
        $content.append($body);
        //acciones
        var crearBoton = function (texto, callback, cerrarModal) {
            var $button = $('<button type="button" class="btn btn-default"></button>');
            $button.text(texto);
            $button.attr("name", "boton-" + texto.toLowerCase() + "-modal");
            if (cerrarModal === true) {
                $button.attr("data-dismiss", "modal");
            }
            $button.click(callback);
            $footer.append($button);
        };
        var cerrarEnClick = false;
        if (cerrar.indexOf(ModuloVentana.CERRAR.CADA_ACCION) !== -1) {
            cerrarEnClick = true;
        }
        if (tipo === ModuloVentana.TIPO.SI_NO || tipo === ModuloVentana.TIPO.SI_NO_CANCELAR) {
            crearBoton("Sí", callbacks["si"], cerrarEnClick);
            crearBoton("No", callbacks["no"], cerrarEnClick);
            if (tipo === ModuloVentana.TIPO.SI_NO_CANCELAR) {
                crearBoton("Cancelar", callbacks["cancelar"], cerrarEnClick);
            }
        } else if (tipo === ModuloVentana.TIPO.ACEPTAR_CANCELAR) {
            crearBoton("Aceptar", callbacks["aceptar"], cerrarEnClick);
            crearBoton("Cancelar", callbacks["cancelar"], cerrarEnClick);
        } else if (tipo === ModuloVentana.TIPO.ACEPTAR) {
            crearBoton("Aceptar", callbacks["aceptar"], cerrarEnClick);
        }
        //por defecto la primer opcino es importante...
        $footer.children(".btn").first().addClass("btn-primary");
        $content.append($footer);
        $modal.append($dialog);
        if (cerrar.indexOf(ModuloVentana.CERRAR.FUERA_MODAL) === -1) {
            $modal.modal({backdrop: "static"});
        }
        if (cerrar.indexOf(ModuloVentana.CERRAR.TECLA_ESCAPE) !== -1) {
            $modal.modal({keyboard: true});
        }
        $modal.on('hidden.bs.modal', callbacks["modalCerrado"]);

        $modal.on('shown.bs.modal', function () {
            $closeButton.focus();
        });

        $($modal).css("z-index", 9999)
        return $modal;
    }
    return yo;
})();
//constantes
ModuloVentana.TIPO = {
    SI_NO_CANCELAR: "SI_NO_CANCELAR",
    SI_NO: "SI_NO",
    ACEPTAR_CANCELAR: "ACEPTAR_CANCELAR",
    ACEPTAR: "ACEPTAR"
};
ModuloVentana.CERRAR = {
    BOTON_CIERRE: "BOTON_CIERRE",
    TECLA_ESCAPE: "TECLA_ESCAPE",
    FUERA_MODAL: "FUERA_MODAL",
    CADA_ACCION: "CADA_ACCION"
};
ModuloVentana.TAMANIO = {
    GRANDE: "GRANDE",
    MEDIANO: "MEDIANO",
    PEQUENIO: "PEQUENIO"
};
