<?php
/**
 * Plugin Name: Simple Icons Widget
 * Plugin URI: http://schoonology.com
 * Description: A widget that wraps the fantastic simple-icons package as a menu.
 * Version: 0.1
 * Author: Michael Schoonmaker
 * Author URI: http://schoonology.com
 *
 * To browse the icons, check out simpleicons.org.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * Register our widget.
 */
add_action( 'widgets_init', 'register_simple_icons_widget' );
function register_simple_icons_widget() {
  register_widget( 'Simple_Icons_Widget' );
}

$simple_icon_data_arr = json_decode(file_get_contents(plugin_dir_path(__FILE__) . 'assets/simple-icons.json'));
$simple_icon_data = array();
foreach ($simple_icon_data_arr as $icon) {
  $simple_icon_data[$icon->name] = $icon;
}


/**
 * The Widget implementation.
 */
class Simple_Icons_Widget extends WP_Widget {

  /**
   * Register widget with WordPress.
   */
  function __construct() {
    parent::__construct(
      'simple_icons_widget', // Base ID
      __('Simple Icons Widget', 'text_domain'), // Name
      array( 'description' => __( 'Displays Simple Icons as a menu.', 'text_domain' ), ) // Args
    );
  }

  /**
   * Front-end display of widget.
   *
   * @see WP_Widget::widget()
   *
   * @param array $args     Widget arguments.
   * @param array $instance Saved values from database.
   */
  public function widget( $args, $instance ) {
    $title = apply_filters( 'widget_title', $instance['title'] );
    $manifest = json_decode($instance['manifest']);
    global $simple_icon_data;

    echo $args['before_widget'];
    if (!empty($title)) {
      echo $args['before_title'] . $title . $args['after_title'];
    }

    if (!empty($manifest)) {
      echo '<ul>';
      foreach ($manifest as $item) {
        $name = $item->name;
        $slug = strtolower($name);
        $slug = str_replace(' ', '', $slug);
        $slug = str_replace('.', '', $slug);
        ?>
        <li style="float:left;"><a href="<?php echo $item->href; ?>">
          <img style="background: #<?php echo $simple_icon_data[$name]->hex; ?>;" width="64px" height="64px" src="<?php echo plugin_dir_url(__FILE__) . 'assets/icons/' . $slug . '/' . $slug . '-128.png' ?>" alt="<?php echo $name; ?>">
        </a></li>
        <?php
      }
      echo '<li style="clear:both;"></li>';
      echo '</ul>';
    } else {
      echo 'Bad Manifest. <em>Please fix.</em>';
    }

    echo $args['after_widget'];
  }

  /**
   * Back-end widget form.
   *
   * @see WP_Widget::form()
   *
   * @param array $instance Previously saved values from database.
   */
  public function form( $instance ) {
    $instance = wp_parse_args((array)$instance, array('title' => '', 'manifest' => '[]'));
    $title = strip_tags($instance['title']);
    $manifest = $instance['manifest'];

    ?>
    <p>
    <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
    <input type="text" class="widefat"
      id="<?php echo $this->get_field_id('title'); ?>"
      name="<?php echo $this->get_field_name('title'); ?>"
      value="<?php echo esc_attr($title); ?>">
    <textarea class="widefat" rows="16" cols="20"
      id="<?php echo $this->get_field_id('manifest'); ?>"
      name="<?php echo $this->get_field_name('manifest'); ?>"
    ><?php echo esc_textarea($manifest); ?></textarea>
    </p>
    <?php
  }

  /**
   * Sanitize widget form values as they are saved.
   *
   * @see WP_Widget::update()
   *
   * @param array $new_instance Values just sent to be saved.
   * @param array $old_instance Previously saved values from database.
   *
   * @return array Updated safe values to be saved.
   */
  public function update( $new_instance, $old_instance ) {
    $instance = array();
    $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
    $instance['manifest'] = ( ! empty( $new_instance['manifest'] ) ) ? $new_instance['manifest'] : '[]';

    return $instance;
  }

}
?>
