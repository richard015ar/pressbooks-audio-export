<?php

namespace PressbooksAudioExport;

use Pressbooks\Container;
use PressbooksAudioExport\Controllers\BookSettingsController;
use PressbooksAudioExport\Controllers\NetworkSettingsController;
use PressbooksAudioExport\Export\AudioExport;

class Bootstrap
{
    private static ?Bootstrap $instance = null;

    public static function run(): void
    {
        if (! self::$instance) {
            self::$instance = new self;

            self::$instance->setUp();
        }
    }

    public function setUp(): void
    {
        $this->registerExport();
        $this->registerMenu();
        $this->registerBlade();
    }

    private function registerBlade(): void
    {
        Container::get('Blade')->addNamespace(
            'PressbooksAudioExport',
            dirname(__DIR__).'/resources/views'
        );
    }

    private function registerMenu(): void
    {
        if (is_network_admin()) {
            $networkSettingsController = new NetworkSettingsController;
            add_filter('wpmu_options', [$networkSettingsController, 'renderOpenAISettings'], 11);
            add_action('update_wpmu_options', [$networkSettingsController, 'saveOptions']);
        }

        if (is_admin()) {
            $bookSettingsController = new BookSettingsController;
            add_action('admin_menu', function () use ($bookSettingsController) {
                add_menu_page(
                    'audio-export',
                    __('OpenAI Settings', 'pressbooks-audio-export'),
                    __('OpenAI Settings', 'pressbooks-audio-export'),
                    'audio_export',
                    [$bookSettingsController, 'renderOpenAISettings']
                );
            });
        }
    }

    private function registerExport(): void
    {
        $openaiApiKeyNetwork = get_site_option('openai_api_key', '');

        if (! $openaiApiKeyNetwork) {
            $openApiKeySite = get_option('openai_api_key', '');
            if (! $openApiKeySite) {
                return;
            }
        }

        add_filter('pb_export_formats', fn (array $formats) => [
            ...$formats,
            'exotic' => [
                ...$formats['exotic'] ?? [],
                'audio' => __('MP3 Audio (AI)', 'pressbooks-audio-export'),
            ],
        ]);

        add_filter('pb_export_filetype_names', fn (array $formats) => [
            ...$formats,
            'audio' => __('MP3 Audio (AI)', 'pressbooks-audio-export'),
        ]);

        add_filter('pb_export_filetype_shortnames', fn (array $formats) => [
            ...$formats,
            'audio' => __('MP3 Audio (AI)', 'pressbooks-audio-export'),
        ]);

        add_filter('pb_latest_export_filetypes', fn (array $formats) => [
            ...$formats,
            'audio' => '.mp3',
        ]);

        add_filter('pb_active_export_modules', function (array $modules) {
            $requested = (bool) $_POST['export_formats']['audio'] ?? false;

            return $requested
                ? [...$modules, AudioExport::class]
                : $modules;
        });
    }
}
