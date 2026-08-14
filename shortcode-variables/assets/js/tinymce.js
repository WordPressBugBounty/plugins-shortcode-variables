(function() {

    tinymce.PluginManager.add( 'sh_cd_tinymce_button', function( editor, url ) {

            editor.addButton( 'sh_cd_tinymce_button', {
            title: sh_cd_tinymce[ 'button-text' ],
            image: sh_cd_tinymce[ 'button-image' ],
            type: 'menubutton',
            icon: false,
                menu: [
                        {
                            text: 'Your shortcodes',
                            onclick: function() {
                                sh_cd_tinymce_popup( editor, sh_cd_tinymce[ 'values-your' ] );
                            }
                        },
                        {
                            text: 'Premade shortcodes',
                            onclick: function() {
                                sh_cd_tinymce_popup( editor, sh_cd_tinymce[ 'values-premade' ] );
                            }
                        }
                    ]
            });
    });

    /**
     * Render pop up
     * @param editor
     * @param values
     */
    function sh_cd_tinymce_popup( editor, values ) {

        editor.windowManager.open( {
            title:  sh_cd_tinymce[ 'dialog-title' ],
            width: 400,
            height:80,
            body: [
                {
                    type: 'listbox',
                    width: 400,
                    name: 'shortcode',
                    label: sh_cd_tinymce[ 'dialog-label' ],
                    'values': values
                }
                ],
            onsubmit: function( e ) {
                editor.insertContent( e.data.shortcode );
            }
        });
    }

})();