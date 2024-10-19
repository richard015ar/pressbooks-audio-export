<?php

namespace PressbooksAudioExport\Controllers;

class BookSettingsController extends BaseController
{
    public function renderOpenAISettings(): void
    {
        if (isset($_POST['audio_export'])) {
            $this->saveOptions();
        }

        echo $this->renderView(
            'openaibooks', [
                'openaiApiKey' => get_option('openai_api_key', ''),
                'openAiVoice' => get_option('openai_voice', null),
                'openAiApiKeyNetwork' => get_site_option('openai_api_key', ''),
                'nonce' => wp_nonce_field('save', 'audio_export', true, false),
            ]
        );
    }

    public function saveOptions(): void
    {
        if (
            ! wp_verify_nonce($_POST['audio_export'], 'save') ||
            ! isset($_POST['openai_voice'])
        ) {
            return;
        }

        if (isset($_POST['openai_api_key'])) {
            update_option('openai_api_key', sanitize_text_field($_POST['openai_api_key']));
        }

        update_option('openai_voice', sanitize_text_field($_POST['openai_voice']));
    }
}
