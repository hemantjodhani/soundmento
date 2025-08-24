<?php
/**
 * Soundmento Widget for Elementor
 *
 * @package Soundmento
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;

/**
 * Soundmento Widget Class
 */
class Soundmento_Widget extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'soundmento';
	}

	/**
	 * Get widget title.
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Soundmento', 'soundmento' );
	}

	/**
	 * Get widget icon.
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-play';
	}

	/**
	 * Get widget categories.
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return array( 'general' );
	}

	/**
	 * Get widget keywords for search.
	 *
	 * @return array List of widget keywords.
	 */
	public function get_keywords() {
		return array(
			'soundmento',
			'playlist',
			'music',
			'audio',
			'songs',
			'tracks',
			'media',
			'podcast',
			'player',
		);
	}

	/**
	 * Register widget controls.
	 */
	protected function register_controls() {

		$this->start_controls_section(
			'content_section',
			array(
				'label' => __( 'Items List', 'soundmento' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'som_list_item_text',
			array(
				'label'   => __( 'Item Text', 'soundmento' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'List Item', 'soundmento' ),
			)
		);

		$repeater->add_control(
			'som_list_item_artist',
			array(
				'label'   => __( 'Artist Name', 'soundmento' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Unknown Artist', 'soundmento' ),
			)
		);

		$repeater->add_control(
			'som_list_item_image',
			array(
				'label'   => __( 'Image', 'soundmento' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => array(
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				),
			)
		);

		$repeater->add_control(
			'som_list_item_audio',
			array(
				'label'      => __( 'Audio File', 'soundmento' ),
				'type'       => Controls_Manager::MEDIA,
				'media_type' => array( 'audio' ),
			)
		);

		$this->add_control(
			'items',
			array(
				'label'       => __( 'Items', 'soundmento' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => array(
					array(
						'som_list_item_text'   => __( 'Item #1', 'soundmento' ),
						'som_list_item_artist' => __( 'Unknown Artist', 'soundmento' ),
						'som_list_item_image'  => array( 'url' => \Elementor\Utils::get_placeholder_image_src() ),
						'som_list_item_audio'  => '',
					),
				),
				'title_field' => '{{{ som_list_item_text }}}',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Get audio duration using WordPress core functionality
	 *
	 * @param string $file_url URL of the audio file.
	 * @return string Duration in MM:SS format or empty string.
	 */
	private function get_audio_duration( $file_url ) {
		if ( empty( $file_url ) ) {
			return '';
		}
		$attachment_id = attachment_url_to_postid( $file_url );
		if ( $attachment_id ) {
			$metadata = wp_get_attachment_metadata( $attachment_id );
			if ( isset( $metadata['length_formatted'] ) ) {
				return $metadata['length_formatted'];
			}
			if ( isset( $metadata['length'] ) ) {
				return gmdate( 'i:s', $metadata['length'] );
			}
		}

		if ( ! class_exists( 'getID3' ) ) {
			if ( file_exists( ABSPATH . WPINC . '/ID3/getid3.php' ) ) {
				require_once ABSPATH . WPINC . '/ID3/getid3.php';
			}
		}

		if ( class_exists( 'getID3' ) ) {
			$file_path = str_replace( home_url(), ABSPATH, $file_url );
			if ( file_exists( $file_path ) ) {
				$getid3   = new getID3();
				$fileinfo = $getid3->analyze( $file_path );
				if ( isset( $fileinfo['playtime_string'] ) ) {
					return $fileinfo['playtime_string'];
				}
			}
		}

		return '';
	}

	/**
	 * Render widget output on the frontend.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		if ( ! empty( $settings['items'] ) ) {
			?>
			<div class="som-player">
				<div class="som-player__header">
					<div class="som-player__img som-player__img--absolute som-slider">
						<button class="som-player__button som-player__button--absolute--nw som-playlist">
							<img src="<?php echo esc_url( SOUNDMENTO_PLUGIN_URL . 'assets/icons/playlist-icon.svg' ); ?>" alt="playlist-icon">
						</button>
						<button class="som-player__button som-player__button--absolute--center som-play">
							<img src="<?php echo esc_url( SOUNDMENTO_PLUGIN_URL . 'assets/icons/play-icon.svg' ); ?>" alt="play-icon">
							<img src="<?php echo esc_url( SOUNDMENTO_PLUGIN_URL . 'assets/icons/pause-icon.svg' ); ?>" alt="pause-icon">
						</button>
						<div class="som-slider__content">
							<?php foreach ( $settings['items'] as $item ) : ?>
								<img class="som-img som-slider__img" src="<?php echo esc_url( $item['som_list_item_image']['url'] ); ?>" alt="cover">
							<?php endforeach; ?>
						</div>
					</div>
					<div class="som-player__controls">
						<button class="som-player__button som-back">
							<img class="som-img" src="<?php echo esc_url( SOUNDMENTO_PLUGIN_URL . 'assets/icons/back-icon.svg' ); ?>" alt="back-icon">
						</button>
						<p class="som-player__context som-slider__context">
							<strong class="som-slider__name"><?php echo esc_html( $settings['items'][0]['som_list_item_text'] ); ?></strong>
							<span class="som-player__title som-slider__title"><?php echo esc_html( $settings['items'][0]['som_list_item_artist'] ); ?></span>
						</p>
						<button class="som-player__button som-next">
							<img class="som-img" src="<?php echo esc_url( SOUNDMENTO_PLUGIN_URL . 'assets/icons/next-icon.svg' ); ?>" alt="next-icon">
						</button>
						<div class="som-progres">
							<div class="som-progres__filled" style="width: 0%;"></div>
						</div>
					</div>
				</div>

				<ul class="som-player__playlist som-list">
					<?php foreach ( $settings['items'] as $item ) : ?>
						<li class="som-player__song">
							<img class="som-player__img som-img" src="<?php echo esc_url( $item['som_list_item_image']['url'] ); ?>" alt="cover">
							<p class="som-player__context">
								<b class="som-player__song-name"><?php echo esc_html( $item['som_list_item_text'] ); ?></b>
								<span class="som-flex">
									<span class="som-player__title"><?php echo esc_html( $item['som_list_item_artist'] ); ?></span>
									<?php
									if ( ! empty( $item['som_list_item_audio']['url'] ) ) :
										$duration = $this->get_audio_duration( $item['som_list_item_audio']['url'] );
										if ( ! empty( $duration ) ) {
											echo '<span class="som-player__song-time mx-5">' . esc_html( $duration ) . '</span>';
										}
									?>
								</span>
							</p>
							<audio class="som-audio" src="<?php echo esc_url( $item['som_list_item_audio']['url'] ); ?>"></audio>
							<?php endif; ?>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
			<?php
		}
	}
}