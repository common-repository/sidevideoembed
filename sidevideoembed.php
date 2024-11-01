<?php
/*
Plugin Name: SideVideoEmbed
Plugin URI: http://zoranmaric.com/plg/sve.zip
Description:  Sidebar Video embed plugin
Version: 1.1.0
Author: Zoran Maric
Author URI: http://zoranmaric.com
License: GPL2
Text Domain: sidevideoembed
Domain Path: /lang
*/
?>
<?php
add_action('plugins_loaded', 'wan_load_textdomain');
function wan_load_textdomain()
{
    load_plugin_textdomain('sidevideoembed', false, dirname(plugin_basename(__FILE__)) . '/lang/');
}

//Name and description translation
$syt_name = __('SideVideoEmbed', 'sidevideoembed');
$syt_desc = __('Sidebar Video embed plugin', 'sidevideoembed');
function syt_stil()
{
    wp_enqueue_style('syt', plugins_url('/css/style.css', __FILE__));
}

add_action('wp_enqueue_scripts', 'syt_stil');

function syt_admin()
{
    wp_enqueue_style('sytadmin', plugins_url('/css/sytadmin.css', __FILE__));
    /* Enqueue Style */
    wp_enqueue_style('zmenadmin');
}

add_action('admin_print_styles', 'syt_admin', 20);

//Plugins start
class syt_plugin extends WP_Widget
{
    // constructor
    function syt_plugin()
    {
        parent::__construct(false, $name = __('SideVideoEmbed', 'sidevideoembed'));
    }

    // widget form creation
    function form($instance)
    {
        // Check values
        if ($instance) {
            $syt_title = esc_attr($instance['syt_title']);
            $syt_cp = esc_attr($instance['syt_cp']);
            $syt_vw = esc_attr($instance['syt_vw']);
            $syt_vh = esc_attr($instance['syt_vh']);
        } else {
            $syt_title = '';
            $syt_cp = '';
            $syt_vw = '';
            $syt_vh = '';
        }
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('syt_title'); ?>"><?php _e('Video Title:', 'sidevideoembed'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('syt_title'); ?>"
                   name="<?php echo $this->get_field_name('syt_title'); ?>" type="text"
                   value="<?php echo $syt_title; ?>"/></p>

        <p>
            <label for="<?php echo $this->get_field_id('syt_cp'); ?>"><?php _e('COPY-PASTE Youtube or Vimeo URL:', 'sidevideoembed'); ?></label>
            <input class="upisatidva" id="<?php echo $this->get_field_id('syt_cp'); ?>"
                   name="<?php echo $this->get_field_name('syt_cp'); ?>" type="text" value="<?php echo $syt_cp; ?>"/>
        </p>

        <p><input class="upisati" id="<?php echo $this->get_field_id('syt_vw'); ?>"
                  name="<?php echo $this->get_field_name('syt_vw'); ?>" type="number" value="<?php echo $syt_vw; ?>"/>
            <label for="<?php echo $this->get_field_id('syt_vw'); ?>"><?php _e('Video width.', 'sidevideoembed'); ?></label>
        </p>

        <p><input class="upisati" id="<?php echo $this->get_field_id('syt_vh'); ?>"
                  name="<?php echo $this->get_field_name('syt_vh'); ?>" type="number" value="<?php echo $syt_vh; ?>"/>
            <label for="<?php echo $this->get_field_id('syt_vh'); ?>"><?php _e('Video height.', 'sidevideoembed'); ?></label>
        </p>

        <p><?php _e('Hope that you will find this plugin useful.', 'sidevideoembed'); ?></p>
        <p align="right"><a target="_blank" href="http://www.zoranmaric.com"> Zoran Maric</a></p>
        <br/>
        <?php
    }

    // Widget update
    function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
        // Fields
        $instance['syt_title'] = strip_tags($new_instance['syt_title']);
        $instance['syt_cp'] = strip_tags($new_instance['syt_cp']);
        $instance['syt_vw'] = strip_tags($new_instance['syt_vw']);
        $instance['syt_vh'] = strip_tags($new_instance['syt_vh']);
        return $instance;
    }

    // Widget display
    function widget($args, $instance)
    {
        extract($args);
        // These are the widget options
        $syt_title = apply_filters('widget_title', $instance['syt_title']);
        $syt_cp = $instance['syt_cp'];
        $syt_vw = $instance['syt_vw'];
        $syt_vh = $instance['syt_vh'];
        $syt_string = $syt_cp;
        //YouTube check
        $syt_search = '/youtube\.com\/watch\?v=([a-zA-Z0-9]+)/smi';
        $syt_replace = "youtube.com/embed/$1";
        $syt_urle = preg_replace($syt_search, $syt_replace, $syt_string);
        $syt_url = preg_replace('/&(.*)/', '', $syt_urle);
        $syt_videoLink = $syt_cp;
        //Vimeo check
        if (preg_match('#https?://vimeo.com/([0-9]+)#i', $syt_videoLink, $syt_match)) {
            $syt_videoId = $syt_match[1];
        }
        // Display the widget
        echo '<div class="zmsyt">';
        // Check if title is set
        if ($syt_title) {
            echo  $syt_title;
        }
        if (preg_match('/youtube/', $syt_cp)) {
            echo "<iframe src='" . $syt_url . "' width='" . $syt_vw . "' height='" . $syt_vh . "' frameborder='0'></iframe>";
        } else {
            echo "<iframe src='https://player.vimeo.com/video/" . $syt_videoId . "' width='" . $syt_vw . "' height='" . $syt_vh . "' frameborder='0' webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>";
        }

        echo '</div>';
    }
}

// register widget
add_action('widgets_init', create_function('', 'return register_widget("syt_plugin");')); ?>