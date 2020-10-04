<?php
/*
    Plugin Name: Image Scroller
    Plugin URI: https://github.com/dmitrijkiltau/wordpress-image-scroller
    Description: A simple image scroller.
    Author: Dmitrij Kiltau <dmitrij@kiltau.com>
    Version: 1.0.0
    Author URI: https://kiltau.com/
    Text Domain: image-scroller
*/

class DK_Image_Scroller {

	public $plugin_name = 'image-scroller';

	public function __construct() {
		add_action( 'init', array( $this, 'init_shortcode' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}

	/**
	 * Init shortcode.
	 */
	public function init_shortcode() {
		add_shortcode( 'image_scroller', array( $this, 'render_shortcode' ) );
	}

	/**
	 * Enqueue plugin scripts and styles.
	 */
	public function enqueue_scripts() {
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'styles.css' );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'scripts.js' );
	}

	/**
	 * Render images from image ids.
	 *
	 * @param $ids array
	 * @param $links array
	 * @param $target string
	 *
	 * @return object
	 */
	public function render_images( $ids, $links, $target ) {
		$html  = '';
		$width = 0;

		foreach ( $ids as $index => $id ) {
			// Get image from media.
			$image = wp_get_attachment_image_src( $id, 'full' );

			// Add image width to container width.
			$width += $image[1];

			// Add img html tag to images string.
			$image_html = '<img class="scroll-image" src="' . $image[0] . '" alt />';

			// Check if link exists.
			if ( array_key_exists( $index, $links ) ) {
				$link_target = $target !== '' ? ' target="' . $target . '"' : '';

				// Check if link is not empty.
				if ( $links[ $index ] !== '' ) {
					$image_html = '<a href="' . $links[ $index ] . '"' . $link_target . '>' . $image_html . '</a>';
				}
			}

			$html .= $image_html;
		}

		return (object) array(
			'html'  => $html,
			'width' => $width
		);
	}

	/**
	 * Render scroll animation.
	 *
	 * @param $container_id string
	 * @param $animation_name string
	 * @param $width int
	 * @param $duration string
	 *
	 * @return false|string
	 */
	public function render_animation( $container_id, $animation_name, $width, $duration ) {
		$container_style = '#' . $container_id . '{';
		$container_style .= 'animation-name: ' . $animation_name . ';';

		if ( $duration !== '' ) {
			$container_style .= 'animation-duration: ' . $duration . ';';
		}

		ob_start();
		?>
        <style type="text/css">
            <?php
            echo $container_style . '}';

            if ($width !== 0) : ?>
            @keyframes <?php echo $animation_name; ?> {
                100% {
                    transform: translateX(-<?php echo $width; ?>px);
                }
            }

            <?php endif; ?>
        </style>
		<?php
		return ob_get_clean();
	}

	/**
	 * Render shortcode.
	 *
	 * @param $atts array
	 *
	 * @return false|string
	 */
	public function render_shortcode( $atts ) {
		$atts = shortcode_atts( array(
			'ids'      => '',
			'duration' => '',
			'links'    => '',
			'target'   => '',
			'reverse'  => false,
			'pausable' => false
		), $atts );

		// Generate a random id in case there is more than one scroller on the page.
		$id             = uniqid();
		$container_id   = 'scroll-container-' . $id;
		$animation_name = 'scroll-' . $id;

		// Get the images and width.
		$ids    = explode( ',', $atts['ids'] );
		$links  = explode( ',', $atts['links'] );
		$images = $this->render_images( $ids, $links, $atts['target'] );
		$width  = $images->width;

		$class_name = '';

		if ( $atts['reverse'] !== false ) {
			$class_name = ' reverse';
		}

		if ( $atts['pausable'] !== false ) {
			$class_name = ' pausable';
		}

		echo $this->render_animation( $container_id, $animation_name, $width, $atts['duration'] );

		ob_start();
		?>
        <div class="image-scroller<?php echo $class_name ?>" data-width="<?php echo $width; ?>">
            <div class="scroll-container" id="<?php echo $container_id; ?>">
                <div class="image-container">
					<?php echo $images->html; ?>
                </div>
            </div>
        </div>
		<?php
		return ob_get_clean();
	}
}

new DK_Image_Scroller();