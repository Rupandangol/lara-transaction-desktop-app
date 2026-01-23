<?php

namespace App\Http\Controllers;

use App\Services\SaveImageDataToTempService;
use Illuminate\Http\Request;

class UploadTransactionImageController extends Controller
{
    public function __construct()
    {
        $this->middleware('gemini.active');
    }

    public function index()
    {

        return view('user.transaction.upload-transaction-image');
    }

    public function store(Request $request)
    {

        $validated = $request->validate([
            'images' => ['required', 'max:5'],
            'images.*' => ['required', 'image', 'max:2048'],
        ], [
            'images.max' => 'Only 5 image limit per hit',
        ]);
        $service = new SaveImageDataToTempService;
        $service->convert($validated['images']);

        return redirect(route('temp.transaction.index'));
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
