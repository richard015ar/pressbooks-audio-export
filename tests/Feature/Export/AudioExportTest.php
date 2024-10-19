<?php

namespace Tests\Feature\Export;

use GuzzleHttp\Exception\GuzzleException;
use PressbooksAudioExport\Export\AudioExport;
use Tests\TestCase;
use utilsTrait;

/**
 * @group export
 */
class AudioExportTest extends TestCase
{
    use utilsTrait;

    /** @test */
    public function it_fails_to_convert_due_to_openAi_key(): void
    {
        $this->_book();

        $export = new AudioExport;

        $this->assertNull($export->getOutputPath());

        $this->assertFalse($export->convert());
    }

    /** @test
     * @throws GuzzleException
     */
    public function test_export_file_is_valid(): void
    {
        $this->_book();

        $export = new AudioExport;

        $export->convert();

        $this->assertTrue(
            $export->validate()
        );
    }
}
