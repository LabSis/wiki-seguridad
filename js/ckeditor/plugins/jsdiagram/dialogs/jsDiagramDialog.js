CKEDITOR.dialog.add( 'jsDiagramDialog', function( editor ) {
    return {
        title: 'Diagrama',
        contents: [
            {
                id: 'tab-principal',
                label: 'Insertar diagrama',
                type: 'text',
                elements: [
                    {
                        type: 'textarea',
                        id: 'fuente-diagrama',
                        label: 'Dibuje su diagrama',
                        validate: CKEDITOR.dialog.validate.notEmpty( "El diagrama no puede estar vacío" )
                    },
                    {
                        type: 'button',
                        id: 'btn-dibujar-diagrama',
                        label: 'Dibujar',
                        onClick: function() {
                            let dialog = this.getDialog()
                            let contenedorDialogHtml = dialog.getContentElement('tab-principal', 'div-previsualizacion')
                            let contenedorHtml = this.getElement().getDocument().getById(contenedorDialogHtml.domId)
                            contenedorHtml.setHtml("")
                            var diagram = Diagram.parse(dialog.getValueOf( 'tab-principal', 'fuente-diagrama' ));
                            diagram.drawSVG(contenedorDialogHtml.domId, {theme: 'simple'});

                        }
                    },
                    {
                        type: 'html',
                        id: 'div-previsualizacion',
                        html: ""
                    }

                ]
            },

        ],
        onOk: function() {
                let dialog = this;
                let texto = dialog.getValueOf( 'tab-principal', 'fuente-diagrama' )
                // texto = texto.replace(/(?:\r\n|\r|\n)/g, '<br>');

                let divDiagrama  = editor.document.createElement( 'div' );
                divDiagrama.addClass("divDiagramaSecuencia")
                divDiagrama.setAttribute("content", texto)

                // ver si esta imaen puede ser el diagrama propiamente dicho
                let diagramaSecuencia  = editor.document.createElement( 'diagrama-secuencia' );
                diagramaSecuencia.setText(texto)
                divDiagrama.append(diagramaSecuencia)
                editor.insertElement( divDiagrama );

                $(editor.document.$.documentElement.getElementsByTagName("diagrama-secuencia")).each(function(i,e){
                    if ($(e).children("svg").length <= 0){
                        $(e).sequenceDiagram({theme: 'simple'});
                    }
                })

                // $(editor.document.$.documentElement.getElementsByTagName("diagrama-secuencia")).
        },

    };

});