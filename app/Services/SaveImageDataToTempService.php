<?php

namespace App\Services;

use App\Enum\ChannelEnum;
use App\Enum\StatusEnum;
use App\Enum\TagEnum;
use App\Models\TempTransaction;
use Illuminate\Support\Facades\Auth;
use Native\Laravel\Facades\Alert;

final class SaveImageDataToTempService
{
    public function convert(array $images)
    {
        $geminiClient = new GeminiClient;
        $response = $geminiClient
            ->setPrompt($this->prompt())
            ->attachFiles($images)
            ->send();
        if ($response['status'] == 'failed') {
            Alert::new()
                ->title('Gemini Issue')
                ->show($response['message'] ?? '');

            return [];
        }
        $data = $response['data'];
        foreach ($data ?? [] as $item) {
            TempTransaction::create([
                'date_time' => $item['date'] ?? now()->format('Y-m-d H:i:s'),
                'description' => $item['vendor'] ?? 'Unclear',
                'debit' => $item['total_amount'] ?? 0,
                'tag' => $item['tag'] ?? TagEnum::OTHERS,
                'status' => StatusEnum::COMPLETE,
                'channel' => ChannelEnum::CASH,
                'user_id' => Auth::user()->id,
            ]);
        }

        return TempTransaction::where('user_id', Auth::user()->id)->get();
    }

    public function prompt()
    {
        return <<<'TEXT'
    You are an OCR parser specialized in parsing bills. Multiple bill images will be sent.

    TASK:
    Extract data from bill images.
    
    TAGS (allowed values only):
    [
    "bill_sharing",
    "family_expenses",
    "food_and_drink",
    "entertainment",
    "utilities",
    "travel",
    "shopping",
    "groceries",
    "lend",
    "personal_use",
    "ride_sharing",
    "others"
    ]


    OUTPUT FORMAT (STRICT):
    {
        "data":[
            {
                "date":"YYYY-MM-DD or null",
                "vendor":"string or null",
                "total_amount":"number or null",
                "tag":"one of the allowed tag values above, or null"
            }
        ]
    }
    
    RULES:
    - Return ONLY valid JSON.
    - Do not write any text outside the JSON object.
    - Do NOT include markdown, comments, or explanation.
    - Use null for missing, unreadable, or uncertain fields.
    - For tag: choose ONLY one from the allowed list. If unsure, return null.
    - Do NOT create any keys that are not in the structure.
    - Follow the structure EXACTLY.
    - Do NOT add trailing commas.
    TEXT;
    }
}
