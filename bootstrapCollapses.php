<?php

class BootstrapCollapses
{
    private $current = '';

    function __construct( Array $tabs )
    {
        self::recursiveArrHandle( $tabs );
    }

    static function recursiveArrHandle( Array $tabs, $parent = '' )
    {
        if( !empty($tabs['content']) ) echo $tabs['content'];

        if( !empty($tabs['tabs']) ) $key = 'tabs';
        if( !empty($tabs['pills']) ) $key = 'pills';
        if( !empty($tabs['collapseList']) ) $key = 'collapseList';

        if( $key ) {
            switch ( $key ) {
                case 'tabs':
                case 'pills':
                    self::navs( $tabs[ $key ], $key, $parent );
                    self::navContent( $tabs[ $key ], $parent );
                    break;

                case 'collapseList':
                    $label = isset($tabs['label']) ? $tabs['label'] : '';
                    self::collapseList( $tabs[ $key ], $key, $tabs['label'], $parent );
                    self::navContent( current($tabs[ $key ]), $parent);
                    break;
            }
        }
    }

    static function navItem( $key, $label, $active = false )
    {
        printf('
            <li class="nav-item">
            <a class="%s" data-toggle="tab" href="#%s" role="tab">%s</a>
            </li>',
            $active ? 'nav-link active' : 'nav-link',
            $key,
            $label
        );
    }

    static function navs( Array $tabs, $class = 'tabs', $parent = '' )
    {
        if( ! is_array($tabs) ) return;

        printf('<ul class="nav nav-%s" role="tablist">', $class);

        $i = 0;
        foreach ($tabs as $key => $tab) {
            self::navItem($parent ? $parent . '_' . $key : $key, $tab['label'], $i === 0);
            $i++;
        }

        printf("</ul><!-- /.nav-%s -->", $class);
    }

    static function navContent( Array $tabs, $parent = '' )
    {
        if( ! is_array($tabs) ) return;

        echo '<div class="tab-content">';

        $i = 0;
        foreach ($tabs as $key => $tab) {
            if( $parent ) $key = $parent . '_' . $key;

            printf('<div class="%s" id="%s" role="tabpanel">',
                ( 0 === $i ) ? 'tab-pane active' : 'tab-pane',
                $key
            );

            self::recursiveArrHandle($tab, $key);

            echo '</div>';

            $i++;
        }

        echo "</div><!-- /.pill-content -->";
    }

    static function collapseList( Array $list, $key, $label, $parent )
    {
        if( ! is_array($list) ) return;

        $i = 0;
        foreach ($list as $key => $items) {
            printf('
                <label for="%1$s">
                    <span class="list-label">%2$s</span>
                    <select name="%1$s" id="%1$s" class="form-control">',
                    $key,
                    $label);

            foreach ($items as $itemkey => $item) {
                printf('
                        <option value="#%s">%s</option>',
                        $parent ? $parent . '_' . $itemkey : $itemkey,
                        $item['label']);
                $i++;
            }

            printf('
                    </select>
                </label><!-- /[for="%s"] -->', $key);
            ?>
            <script type="text/javascript">
                jQuery(document).ready(function($) {
                    $('#<?php echo $key; ?>').on('change', function(event) {
                        var $elem = $( $(this).val() );
                        $elem.closest('.tab-content').children('.tab-pane').each(function(i, el) {
                            $(el).removeClass('active');
                        });
                        $elem.addClass('active');
                    });
                });
            </script>
            <?php
        }
    }
}