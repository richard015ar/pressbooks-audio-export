<?php

namespace PressbooksAudioExport\Export;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Collection;
use Pressbooks\Book;
use Pressbooks\Modules\Export\Export;

use function Pressbooks\Utility\rmrdir;

class AudioExport extends Export
{
    protected string $extension = '.mp3';

    protected ?string $tmpDir = null;

    protected int $bookId;

    protected Client $httpClient;

    public function __construct()
    {
        $this->tmpDir = $this->createTmpDir();
        $this->bookId = get_current_blog_id();
        $this->httpClient = new Client;
    }

    public function __destruct()
    {
        if ($this->tmpDir && rmrdir($this->tmpDir)) {
            $this->tmpDir = null;
        }
    }

    public function getTemporaryDirectory(): ?string
    {
        return $this->tmpDir;
    }

    /**
     * @throws GuzzleException
     */
    public function convert(): bool
    {
        if (! is_dir($this->tmpDir)) {
            $this->logError('The temporary directory ($tmpDir) should be set at this point.');

            return false;
        }

        try {
            $textToSpeech = $this->getTextToConvert();
            $chunks = $this->splitTextIntoChunks($textToSpeech, 4000);
            $audioFiles = [];

            foreach ($chunks as $index => $chunk) {
                $filenameTmp = $this->tmpDir.'/audio_part_'.$index.'.mp3';
                $this->convertTextChunkToAudio($chunk, $filenameTmp);
                $audioFiles[] = $filenameTmp;
            }

            $filename = $this->timestampedFileName($this->extension);
            $this->concatenateAudioFiles($audioFiles, $filename);

            $this->outputPath = $filename;

        } catch (\Exception $e) {
            $this->logError('Error exporting: '.$e->getMessage());

            return false;
        }

        return true;
    }

    private function getTextToConvert(): string
    {
        $structure = Book::getBookStructure();

        $textToSpeech = '';

        foreach ($structure['part'] as $part) {
            $shouldExport = $part['export'] && $part['has_post_content'];

            $chapters = Collection::make($part['chapters'])
                ->filter(fn (array $item) => $item['export'])
                ->map(fn (array $item) => [
                    'id' => $item['ID'],
                    'identifier' => $this->identifier(postId: $item['ID'], prefix: 'I_'),
                    'identifier_ref' => $this->identifier(postId: $item['ID'], prefix: 'R_'),
                    'title' => wp_strip_all_tags($item['post_title']),
                ]);

            if (! $shouldExport && $chapters->isEmpty()) {
                continue;
            }

            $textToSpeech .= $part['post_title']."\n";

            $chapters->each(function (array $chapter) use (&$textToSpeech) {
                $textToSpeech .= $chapter['title']."\n";
                $textToSpeech .= wp_strip_all_tags(get_post($chapter['id'])->post_content)."\n";
            });
        }

        return $textToSpeech;
    }

    private function splitTextIntoChunks(string $text, int $maxLength): array
    {
        $words = preg_split('/\s+/', $text);
        $chunks = [];
        $currentChunk = '';

        foreach ($words as $word) {
            if (strlen($currentChunk.' '.$word) > $maxLength) {
                $chunks[] = trim($currentChunk);
                $currentChunk = $word;
            } else {
                $currentChunk .= ' '.$word;
            }
        }

        if (! empty($currentChunk)) {
            $chunks[] = trim($currentChunk);
        }

        return $chunks;
    }

    /**
     * @throws GuzzleException
     */
    private function convertTextChunkToAudio(string $chunk, string $filename): void
    {
        $openaiApiKey = get_site_option('openai_api_key', get_option('openai_api_key', ''));
        $openAiVoice = get_option('openai_voice', 'alloy');

        $this->httpClient->post('https://api.openai.com/v1/audio/speech', [
            'headers' => [
                'Authorization' => 'Bearer '.$openaiApiKey,
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'model' => 'tts-1',
                'input' => $chunk,
                'voice' => $openAiVoice,
            ],
            'sink' => $filename,
        ]);
    }

    private function concatenateAudioFiles(array $files, string $outputFile): void
    {
        $finalAudioContent = '';

        foreach ($files as $file) {
            $finalAudioContent .= file_get_contents($file);
        }

        file_put_contents($outputFile, $finalAudioContent);
    }

    private function identifier(int $postId, string $prefix): string
    {
        return "{$prefix}{$this->bookId}_{$postId}";
    }

    public function validate(): bool
    {
        return true;
    }
}
