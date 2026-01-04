<?php

namespace App\Http\Controllers;

use App\Services\SaveImageDataToTempService;
use Illuminate\Http\Request;

class UploadTransactionImageController extends Controller
{
    public function index()
    {
        return view('user.transaction.upload-transaction-image');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'images' => ['required', 'max:5'],
            'images.*' => ['required', 'image', 'max:2048'],
        ],[
            'images.max'=>'Only 5 image limit per hit'
        ]);
        $service = new SaveImageDataToTempService;
        $service->convert($validated['images']);

        return redirect(route('temp.transaction.index'));
    }
}
