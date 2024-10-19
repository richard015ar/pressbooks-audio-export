<?php

namespace PressbooksAudioExport\Controllers;

class NetworkSettingsController extends BaseController
{
    public function renderOpenAISettings(): void
    {
        echo $this->renderView(
            'openai', [
                'openaiApiKey' => get_site_option('openai_api_key', ''),
            ]
        );
    }

    public function saveOptions(): void
    {
        if (! isset($_POST['openai_api_key'])) {
            return;
        }

        update_site_option('openai_api_key', sanitize_text_field($_POST['openai_api_key']));
    }
}
