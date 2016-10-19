<?php

/*
 * Plugin Name: Simple Latest Post
 * Plugin URI: http://thomasvitale.com
 * Description: Add a widget to show the featured image and the content of the latest post of a specific category.
 * Author: Thomas Vitale
 * Author URI: http://thomasvitale.com
 * Text Domain: simple-latest-post
 * Version: 1.0
 * Licence: GPL3
 */

 class SimpleLatestPost extends WP_Widget {

    /**
     * Sets up the widget name and description
     */
    function __construct() {
      $properties = array(
        'name' => __( 'Simple Latest Post', 'simple-latest-post' ),
        'description' => __( 'Show the latest post content of a specific category.', 'simple-latest-post' )
      );
      parent::__construct( 'simple_latest_post', '', $properties );
    }

    /**
  	 * Outputs the options form on admin
  	 */
    public function form( $instance ) {

      ?>

      <!-- Widget Title -->
      <p>
        <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( esc_attr( 'Title:' ), 'simple-latest-post' ); ?></label></label>
        <input
          class="widefat"
          type="text"
          id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
          name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>"
          value="<?php if ( ! empty( $instance['title'] ) ) echo esc_attr( $instance['title'] ); ?>"
        >
       </p>

       <!-- Widget Category -->
       <p>
         <label for="<?php echo esc_attr( $this->get_field_id( 'category' ) ); ?>"><?php _e( esc_attr( 'Category:' ), 'simple-latest-post' ) ?></label>
         <?php wp_dropdown_categories( array(
                  'name' => esc_attr( $this->get_field_name( 'category' ) ),
                  'show_option_none' => __( 'Select category', 'simple-latest-post' ),
                  'orderby' => 'name',
                  'selected' => $instance['category'],
                  'class' => 'widefat'
                 )); ?>
       </p>

      <?php
    }

    /**
  	 * Outputs the content of the widget
  	 */
    public function widget( $args, $instance ) {

      echo $args['before_widget'];

        // The Title
        echo $args['before_title'];
         echo $instance['title'];
        echo $args['after_title'];

        // The Content
        $query_args = array(
          'category__in' => $instance['category'],
          'posts_per_page' => 1
        );

        $post = new WP_Query( $query_args );

        if ( $post->have_posts() ) {
          while( $post->have_posts() ) {
            $post->the_post();
            ?>
            <figure style="margin:0 0 1em 0;">
              <?php
                if ( has_post_thumbnail() ) {
                  the_post_thumbnail();
              } ?>
            </figure>

            <?php the_content(); ?>

            <?php
          }
        }

        wp_reset_query();
        wp_reset_postdata();

      echo $args['after_widget'];

    }

    /**
  	 * Processing widget options on save
  	 */
  	public function update( $new_instance, $old_instance ) {
      $instance = array();
      $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
      $instance['category'] = ( ! empty( $new_instance['category'] ) ) ? strip_tags( $new_instance['category'] ) : '';
  		return $new_instance;
  	}

  }

  add_action( 'widgets_init', function () {
    register_widget( 'SimpleLatestPost' );
  });

?>