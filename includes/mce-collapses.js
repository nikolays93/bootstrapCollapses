(function($){
    tinymce.PluginManager.add(BSCollapses.BUTTON_ID, function( editor, url ) {
        var shortcode_string = 'tabs';
        var values = [];

        wp.mce.tabs = {
            shortcode_data: {},
            getContent: function( e ) {
                return '<div id="tab" style="background: #ccc;" contenteditable="true">test</div>'
            },
            // edit: function( data ) {
            //     var shortcode_data = wp.shortcode.next(shortcode_string, data);
            //     var values = shortcode_data.shortcode.attrs.named;

            //     // values.innercontent = shortcode_data.shortcode.content;
            //     wp.mce.tabs.popupwindow(tinyMCE.activeEditor, values);
            // },
            popupwindow: function( editor, values, qty ) {
                values = values || [];
                editor.windowManager.open( {
                    title: 'New window',
                    body: [],
                    onsubmit: function(e) {
                        // Insert content when the window form is submitted
                        var args = {
                            tag     : shortcode_string,
                            // type    : 'single',
                            attrs : {
                                id : 'test',
                            },
                            content: wp.shortcode.string( {
                                tag     : 'tab',
                                attrs : {
                                    id : 'test',
                                },
                            } )
                        };


                        // // defaults
                        // if(e.data.id) args.attrs.id = e.data.id;
                        // if(advancedOpts.orderby && advancedOpts.orderby != 'menu_order date') args.attrs.orderby = advancedOpts.orderby;
                        editor.insertContent( wp.shortcode.string( args ) );
                    }
                });
            },
        }

        // wp.mce.views.register( shortcode_string, wp.mce.tabs );

        // editor.on('dblClick', function(e) {
        //     console.log( e.target.attributes['id'].value );
        // });

        editor.addButton( BSCollapses.BUTTON_ID, {
            // icon: 'perec',
            type: 'menubutton',
            title: 'Вставить элемент',
            menu: [
                {
                    type: 'menubutton',
                    text: 'Добавить вкладки',
                    onclick: function(){ wp.mce.tabs.popupwindow(editor, values, 9) }
                },
                {
                    text: 'Настройки',
                    onclick: function() {
                        editor.windowManager.open( {
                            title: 'Настройки',
                            body: [{
                                type   : 'checkbox',
                                name   : 'checkbox',
                                label  : 'Подключить bootstrap collapse.js',
                                text   : ' ',
                                checked : false
                            }],
                            onsubmit: function() {
                                return false;
                            }
                        } );
                    }
                }
            ]
        });

    });
// wp.mce.views.register( shortcode_string, wp.mce.company );
}(jQuery));