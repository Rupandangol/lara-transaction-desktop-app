<?php

namespace App\Http\Controllers;

use App\Services\SaveImageDataToTempService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $apiKey = Auth::user()->activeAiKey()->api_key;
        $service = new SaveImageDataToTempService;
        $service->convert($validated['images'], $apiKey);

        return redirect(route('temp.transaction.index'));
    }
}
