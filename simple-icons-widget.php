<?php
/**
 * simple-icons-widget wraps the fantastic simple-icons package.
 *
 * To browse the icons, check out simpleicons.org.
 */
class Simple_Icons_Widget extends WP_Widget {

  /**
   * Register widget with WordPress.
   */
  function __construct() {
    parent::__construct(
      'simple_icons_widget', // Base ID
      __('Simple Icons Widget', 'text_domain'), // Name
      array( 'description' => __( 'Displays Simple Icons as a menu', 'text_domain' ), ) // Args
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

    echo $args['before_widget'];
    if ( ! empty( $title ) )
      echo $args['before_title'] . $title . $args['after_title'];

    echo '<pre><code>' . $instance['data'] . '</pre></code>';

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
    if ( isset( $instance[ 'title' ] ) ) {
      $title = $instance[ 'title' ];
    }
    else {
      $title = __( '', 'text_domain' );
    }
    ?>
    <p>
    <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
    <textarea id="<?php echo $this->get_field_id( 'data' ); ?>" name="<?php echo $this->get_field_name( 'data' ); ?>" value="$instance['data']">
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
    $instance['data'] = ( ! empty( $new_instance['data'] ) ) ? $new_instance['data'] : '[]';

    return $instance;
  }

} // class Simple_Icons_Widget

// register Simple_Icons_Widget widget
function register_simple_icons_widget() {
    register_widget( 'Simple_Icons_Widget' );
}
add_action( 'widgets_init', 'register_simple_icons_widget' );
?>
