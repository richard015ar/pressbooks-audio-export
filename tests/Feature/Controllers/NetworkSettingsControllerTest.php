<?php

namespace Tests\Feature\Controllers;

use PressbooksAudioExport\Controllers\NetworkSettingsController;
use Tests\TestCase;

class NetworkSettingsControllerTest extends TestCase
{
    /**
     * @test
     */
    public function it_saves_open_ai_settings(): void
    {

        $_POST = [
            'openai_api_key' => 'test-api',
        ];

        (new NetworkSettingsController)->saveOptions();

        $this->assertEquals('test-api', get_site_option('openai_api_key'));
    }
}
