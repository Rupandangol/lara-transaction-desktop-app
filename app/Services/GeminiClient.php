<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class GeminiClient
{
    public function __construct(
        protected string $prompt = '',
        protected ?array $files = null,
        protected array $dynamicContext = [],
        protected float $temperature = 0.2,
        protected string $responseMimeType = 'application/json'
    ) {}

    public function setPrompt(string $prompt): static
    {
        $this->prompt = $prompt;

        return $this;
    }

    public function attachFiles(array $files): static
    {
        $this->files = $files;

        return $this;
    }

    public function setDynamicContext(array $dynamicContext): static
    {
        $this->dynamicContext = $dynamicContext;

        return $this;
    }

    public function setTemperature(string $temperature): static
    {
        $this->temperature = $temperature;

        return $this;
    }

    public function setResponseMimeType(string $responseMimeType): static
    {
        $this->responseMimeType = $responseMimeType;

        return $this;
    }

    public function send()
    {
        try {
            $parts = [];
            foreach ($this->files ?? [] as $item) {
                $parts[] = [
                    'inline_data' => [
                        'mime_type' => $item->getMimeType(),
                        'data' => base64_encode(file_get_contents($item)),
                    ],
                ];
            }
            foreach ($this->dynamicContext ?? [] as $item) {
                $parts[] = [
                    'text' => $item,
                ];
            }
            $config = [
                'response_mime_type' => $this->responseMimeType,
                'temperature' => $this->temperature,
            ];
            $systemInstructionPart = [[
                'text' => $this->prompt,
            ]];
            $apiKey = Auth::user()->activeAiKey()->api_key;
            $payload = [
                'system_instruction' => [
                    'parts' => $systemInstructionPart,
                ],
                'contents' => [[
                    'parts' => $parts,
                ]],
                'generation_config' => $config,
            ];
            $response = Http::timeout(60)->withOptions([
                'verify' => false,
            ])->withHeaders([
                'content-type' => 'application/json',
                'x-goog-api-key' => $apiKey,
            ])->post(config('services.gemini.url'), $payload);
            if ($response->failed()) {
                $error = data_get($response->json(), 'error.message', 'Unknown Gemini Error');

                return [
                    'status' => 'failed',
                    'message' => $error,
                ];
            }
            $data = $response->json();
            $text = data_get($data, 'candidates.0.content.parts.0.text');
            if (! $text) {
                return [
                    'status' => 'failed',
                    'message' => 'NO text',
                ];
            }

            return $this->extractJson($text);
        } catch (\Exception $e) {
            return [
                'status' => 'failed',
                'message' => $e->getMessage(),
            ];
        }
    }

    private function extractJson($text)
    {

        $trim = trim($text);
        if (! preg_match('/\{.*\}/s', $trim, $matches)) {
            return [
                'status' => 'failed',
                'message' => 'no data',
            ];
        }
        $data = json_decode($matches[0], true);

        return json_last_error() === JSON_ERROR_NONE ?
            [
                'status' => 'success',
                ...$data,
            ] :
            [
                'status' => 'failed',
                'message' => 'no data',
            ];
    }
}
