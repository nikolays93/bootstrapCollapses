<?php

namespace CDevelopers\Collapses;

class BootstrapCollapses
{
    const BUTTON_ID = 'collapses';

    private $tab_id = 'b-tabs';
    private $tab_i = 0;
    private $active = 1;

    private static $registred = false;
    private static $tabs = array();

    function __construct($foo = null)
    {
        if( !self::$registred ) {
            add_shortcode( 'tabs', array($this, 'render_tabs') );
            add_shortcode( 'tab', array($this, 'render_tab') );
            self::$registred = true;
        }
    }

    function render_tabs($atts = array(), $content = '')
    {
        $atts = shortcode_atts( array(
            'id' => 'b-tabs',
            'class' => 'tabs',
            'active' => '1',
            ), $atts, 'tabs' );

        $this->tab_id = $atts['id'];
        $this->active = $atts['active'];

        $result = array();
        $result[] = '<section class="b-tabs">';
        $result[] = sprintf('<ul class="nav nav-%s" role="tablist" id="%s">',
            esc_attr( $atts['class'] ),
            esc_attr( $atts['id'] ) );
        $result[] = do_shortcode($content);
        $result[] = '</ul>';

        $result[] = '<div class="tab-content">';
        foreach (self::$tabs[ $this->tab_id ] as $i => $tab) {
            $active = ($i + 1 == $this->active) ? ' fade in active' : ''; // show
            $result[] = sprintf('<div role="tabpanel" class="tab-pane fade%s" id="tab-%s-%d">',
                $active,
                esc_attr($this->tab_id),
                $i + 1);
            $result[] = apply_filters( 'the_content', $tab );
            $result[] = '</div>';
        }
        $result[] = '</div>';

        $result[] = '</section>';

        $this->tab_i = 0;
        return implode("\r\n", $result);
    }

    function render_tab($atts = array(), $content = '')
    {
        $atts = shortcode_atts( array(
            'title' => 'Example tab title',
            ), $atts, 'tab' );

        self::$tabs[ $this->tab_id ][] = $content;
        $this->tab_i++;
        $active = ($this->tab_i == $this->active) ? ' active' : '';

        return sprintf('<li class="nav-item%s"><a class="nav-link" href="#tab-%s-%d" role="tab" data-toggle="tab">%s</a></li>',
            $active,
            $this->tab_id,
            $this->tab_i,
            $atts['title']
            );
    }

    static function mce_plugin_init()
    {
        if ( 'true' == get_user_option( 'rich_editing' ) ) {
            ?>
            <!-- TinyMCE Bootstrap Collapses Plugin -->
            <script type='text/javascript'>
                var BSCollapses = {
                    'BUTTON_ID': '<?=self::BUTTON_ID;?>'
                };
            </script>
            <!-- TinyMCE Bootstrap Collapses Plugin -->
            <?php
            add_filter( 'mce_external_plugins', array(__CLASS__, 'add_tinymce_script') );
            add_filter( 'mce_buttons', array(__CLASS__, 'register_mce_button') );
        }
    }

    static function add_tinymce_script( $plugin_array )
    {
        $plugin_array[ self::BUTTON_ID ] = Utils::get_plugin_url('assets') . '/mce-collapses.js';
        return $plugin_array;
    }

    static function register_mce_button( $buttons )
    {
        $buttons[] = self::BUTTON_ID;
        return $buttons;
    }
}
new BootstrapCollapses();
// add_action('admin_head', array(__NAMESPACE__ . '\BootstrapCollapses', 'mce_plugin_init'));
// foreach ( array('post.php','post-new.php') as $hook ) {
//      add_action( "admin_head-$hook", array(__NAMESPACE__ . '\BootstrapCollapses', 'mce_plugin_init') );
// }