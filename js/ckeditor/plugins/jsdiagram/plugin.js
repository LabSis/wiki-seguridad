CKEDITOR.plugins.add( 'jsdiagram', {

    init: function( editor ) {
        editor.addCommand( 'jsdiagram', new CKEDITOR.dialogCommand( 'jsDiagramDialog' ) );
        editor.ui.addButton( 'Diagrama', {
            label: 'Insertar diagrama',
            command: 'jsdiagram',
            toolbar: 'insert,0',
            icon: this.path + 'icons/jsdiagram.png',
        });


        CKEDITOR.dialog.add( 'jsDiagramDialog', this.path + 'dialogs/jsDiagramDialog.js' );

    },

});