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

    public function prompt(): string
    {
        return <<<'XML'
<prompt>
    <role>
        You are an OCR parser specialized in parsing bills.
        Multiple bill images will be sent.
    </role>

    <task>
        Extract structured data from each bill image.
    </task>

    <allowed_tags>
        <tag>bill_sharing</tag>
        <tag>family_expenses</tag>
        <tag>food_and_drink</tag>
        <tag>entertainment</tag>
        <tag>utilities</tag>
        <tag>travel</tag>
        <tag>shopping</tag>
        <tag>groceries</tag>
        <tag>lend</tag>
        <tag>personal_use</tag>
        <tag>ride_sharing</tag>
        <tag>others</tag>
    </allowed_tags>

    <output_contract>
        <format>json</format>
        <schema>
            <data>
                <item>
                    <date>YYYY-MM-DD or null</date>
                    <vendor>string or null</vendor>
                    <total_amount>number or null</total_amount>
                    <tag>one allowed tag or null</tag>
                </item>
            </data>
        </schema>
    </output_contract>

    <rules>
        <rule>Return ONLY valid JSON.</rule>
        <rule>Do not include text outside the JSON object.</rule>
        <rule>No markdown, comments, or explanations.</rule>
        <rule>Use null for missing, unreadable, or uncertain fields.</rule>
        <rule>Choose ONLY one tag from allowed_tags or null.</rule>
        <rule>Do NOT create additional keys.</rule>
        <rule>Follow the schema EXACTLY.</rule>
        <rule>No trailing commas.</rule>
    </rules>
</prompt>
XML;
    }
}
