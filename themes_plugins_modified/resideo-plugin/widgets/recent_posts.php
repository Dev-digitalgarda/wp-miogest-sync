<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */

class Resideo_Recent_Posts_Widget extends WP_Widget {
    function __construct() {
        $widget_ops  = array('classname' => 'resideo_recent_posts_sidebar', 'description' => __('Resideo recent posts', 'resideo'));
        $control_ops = array('id_base' => 'resideo_recent_posts_widget');

        parent::__construct('resideo_recent_posts_widget', __('Resideo Recent Posts', 'resideo'), $widget_ops, $control_ops);
    }

    function form($instance) {
        $defaults = array(
            'title' => '',
            'date'  => '',
            'limit' => ''
        );

        $instance = wp_parse_args((array) $instance, $defaults);

        $has_date = '';
        if ($instance['date'] == 1) {
            $has_date = 'checked="checked"';
        }

        $display = '
            <p>
                <label for="' . esc_attr($this->get_field_id('title')) . '">' . __('Title', 'resideo') . ':</label>
                <input type="text" class="widefat" id="' . esc_attr($this->get_field_id('title')) . '" name="' . esc_attr($this->get_field_name('title')) . '" value="' . esc_attr($instance['title']) . '" />
            </p>
            <p>
                <input id="' . esc_attr($this->get_field_id('date')) . '" name="' . esc_attr($this->get_field_name('date')) . '" type="checkbox" value="1" ' . esc_attr($has_date) . ' />
                <label for="' . esc_attr($this->get_field_id('date')) . '">' . __('Display post date?', 'resideo') . '</label>
            </p>
            <p>
                <label for="' . esc_attr($this->get_field_id('limit')) . '">' . __('Number of posts to show', 'resideo') . ':</label>
                <input type="text" size="3" id="' . esc_attr($this->get_field_id('limit')) . '" name="' . esc_attr($this->get_field_name('limit')) . '" value="' . esc_attr($instance['limit']) . '" />
            </p>
        ';

        print $display;
    }

    function update($new_instance, $old_instance) {
        $instance          = $old_instance;
        $instance['title'] = sanitize_text_field($new_instance['title']);
        $instance['date']  = isset($new_instance['date']) ? 1 : false;
        $instance['limit'] = sanitize_text_field($new_instance['limit']);

        if (function_exists('icl_register_string')) {
            icl_register_string('resideo_recent_posts_widget', 'resideo_recent_posts_widget_title', sanitize_text_field($new_instance['title']));
            icl_register_string('resideo_recent_posts_widget', 'resideo_recent_posts_widget_limit', sanitize_text_field($new_instance['limit']));
        }

        return $instance;
    }

    function widget($args, $instance) {

        if (!isset($args['widget_id'])) {
            $args['widget_id'] = $this->id;
        }

        $title = (!empty( $instance['title'])) ? $instance['title'] : __('Recent Posts', 'resideo');
        $title = apply_filters('widget_title', $title, $instance, $this->id_base);
        $number = (!empty($instance['limit'])) ? absint($instance['limit']) : 5;
        $date = !empty($instance['date']) ? $instance['date'] : false;

        if (!$number) {
            $number = 5;
        }

        $r = new WP_Query(apply_filters('widget_posts_args', array(
            'posts_per_page'      => $number,
            'no_found_rows'       => true,
            'post_status'         => 'publish',
            'ignore_sticky_posts' => true
        )));

        if ($r->have_posts()) :
            echo $args['before_widget'];

            if ($title) {
                echo $args['before_title'] . $title . $args['after_title'];
            } ?>

            <?php while($r->have_posts()) : $r->the_post(); ?>
                <a href="<?php the_permalink(); ?>" class="media mt-2 mt-md-3">
                    <?php if (has_post_thumbnail()) {
                        $image_url = wp_get_attachment_image_src(get_post_thumbnail_id(), 'pxp-thmb');
                        if (!empty($image_url[0])) { ?>
                            <img src="<?php echo esc_url($image_url[0]); ?>" class="mr-3 rounded-lg" alt="<?php get_the_title() ? the_title() : the_ID(); ?>">
                        <?php }
                    } ?>
                    <div class="media-body">
                        <h5><?php get_the_title() ? the_title() : the_ID(); ?></h5>
                        <?php if ($date) { ?>
                            <div class="pxp-post-side-date"><?php the_date(); ?></div>
                        <?php } ?>
                    </div>
                </a>
            <?php endwhile; ?>

            <?php echo $args['after_widget'];

            wp_reset_postdata();

        endif;
    }
}
?>