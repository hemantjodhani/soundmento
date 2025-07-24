<?php
require_once SOUNDMENTO_PLUGIN_PATH . 'vendor/autoload.php';
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;

if (!defined('ABSPATH')) {
    exit;
}

class Soundmento_Widget extends Widget_Base
{

    public function get_name()
    {
        return 'soundmento_widget';
    }

    public function get_title()
    {
        return __('Soundmento Widget', 'soundmento');
    }

    public function get_icon()
    {
        return 'eicon-play';
    }

    public function get_categories()
    {
        return ['general'];
    }

    protected function register_controls()
    {

        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Items List', 'soundmento'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'list_item_text',
            [
                'label'   => __('Item Text', 'soundmento'),
                'type'    => Controls_Manager::TEXT,
                'default' => __('List Item', 'soundmento'),
            ]
        );

        $repeater->add_control(
            'list_item_artist',
            [
                'label'   => __('Artist Name', 'soundmento'),
                'type'    => Controls_Manager::TEXT,
                'default' => __('Unknown Artist', 'soundmento'),
            ]
        );

        $repeater->add_control(
            'list_item_image',
            [
                'label'   => __('Image', 'soundmento'),
                'type'    => Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $repeater->add_control(
            'list_item_audio',
            [
                'label'      => __('Audio File', 'soundmento'),
                'type'       => Controls_Manager::MEDIA,
                'media_type' => 'audio',
            ]
        );

        $this->add_control(
            'items',
            [
                'label'       => __('Items', 'soundmento'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => [
                    [
                        'list_item_text'   => __('Item #1', 'soundmento'),
                        'list_item_artist' => __('Unknown Artist', 'soundmento'),
                        'list_item_image'  => ['url' => \Elementor\Utils::get_placeholder_image_src()],
                        'list_item_audio'  => [],
                    ],
                ],
                'title_field' => '{{{ list_item_text }}}',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        if (!empty($settings['items'])) { ?>
            <div class="som-player">
                <div class="som-player__header">
                    <div class="som-player__img som-player__img--absolute som-slider">
                        <button class="som-player__button som-player__button--absolute--nw som-playlist">
                            <img src="http://physical-authority.surge.sh/imgs/icon/playlist.svg" alt="playlist-icon">
                        </button>
                        <button class="som-player__button som-player__button--absolute--center som-play">
                            <img src="http://physical-authority.surge.sh/imgs/icon/play.svg" alt="play-icon">
                            <img src="http://physical-authority.surge.sh/imgs/icon/pause.svg" alt="pause-icon">
                        </button>
                        <div class="som-slider__content">
                            <?php foreach ($settings['items'] as $item) : ?>
                                <img class="som-img som-slider__img" src="<?php echo esc_url($item['list_item_image']['url']); ?>" alt="cover">
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="som-player__controls">
                        <button class="som-player__button som-back">
                            <img class="som-img" src="http://physical-authority.surge.sh/imgs/icon/back.svg" alt="back-icon">
                        </button>
                        <p class="som-player__context som-slider__context">
                            
                            <strong class="som-slider__name"><?php echo esc_html($settings['items'][0]['list_item_text']); ?></strong>
                            <span class="som-player__title som-slider__title"><?php  echo esc_html($settings['items'][0]['list_item_artist']); ?></span>
                        </p>
                        <button class="som-player__button som-next">
                            <img class="som-img" src="http://physical-authority.surge.sh/imgs/icon/next.svg" alt="next-icon">
                        </button>
                        <div class="som-progres">
                            <div class="som-progres__filled" style="width: 0%;"></div>
                        </div>
                    </div>
                </div>

                <ul class="som-player__playlist som-list">
                    <?php foreach ($settings['items'] as $item) : ?>
                        <li class="som-player__song">
                            <img class="som-player__img som-img" src="<?php echo esc_url($item['list_item_image']['url']); ?>" alt="cover">
                            <p class="som-player__context">
                                <b class="som-player__song-name"><?php echo esc_html($item['list_item_text']); ?></b>
                                <span class="som-flex">
                                    <span class="som-player__title"><?php echo esc_html($item['list_item_artist']); ?></span>
                                    <?php if (!empty($item['list_item_audio']['url'])) :
                                        $file_url      = $item['list_item_audio']['url'];
                                        $tmp_file_path = tempnam(sys_get_temp_dir(), 'audio_');
                                        file_put_contents($tmp_file_path, file_get_contents($file_url));
                                        $getID3   = new getID3;
                                        $fileInfo = $getID3->analyze($tmp_file_path);
                                        echo '<span class="som-player__song-time mx-5">' . $fileInfo['playtime_string'] . '</span>';
                                        unlink($tmp_file_path);
                                    ?>
                                </span>
                            </p>
                            <audio class="som-audio" src="<?php echo esc_url($item['list_item_audio']['url']); ?>"></audio>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
<?php
        }
    }
}
