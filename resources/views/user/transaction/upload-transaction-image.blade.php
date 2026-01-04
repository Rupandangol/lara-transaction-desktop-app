@extends('layout.app')
@section('content-header')
    <ruby>Transaction image upload</ruby>  
@endsection
@section('content')
    <div class="grid grid-cols-3 gap-4">
        <h1 class="text-2xl font-bold col-span-2 text-center">Image Upload Here</h1>
        <div class="text-end">
            <a href="{{ route('temp.transaction.index') }}"
                class="p-2 bg-amber-700 hover:bg-amber-800 text-center text-white rounded-md shadow-md">Temp Transactions</a>
        </div>
    </div>
    <div class="w-full border mt-5 p-2 rounded-md shadow-md">
        <form id="upload-form" action="{{ route('transaction.upload.transaction.image.store') }}" method="post"
            class="space-y-3" enctype="multipart/form-data">
            @csrf
            <div>
                <label class="font-semibold" for="images">Images *</label>
                <p class="font-semibold text-sm text-gray-500">You can add multiple images up to 5</p>
                <input name="images[]" class="w-full border p-2 mt-2 rounded-md" multiple type="file">
                @foreach ($errors->all() as $item)
                    <div class="font-semibold text-red-500 text-sm">{{ $item }}</div>
                @endforeach
            </div>

            <button id="analyse-btn" type="submit"
                class="p-2 w-full rounded-md bg-green-500 hover:bg-green-600 text-center text-white flex items-center justify-center">
                <div id="btn-loader" class="hidden h-5 w-5 border-2 border-white border-t-transparent rounded-full mr-2 animate-spin"></div>
                <span id="btn-text">Analyse</span>
            </button>
        </form>

    </div>
@endsection

@section('content-footer')
    <script>
        const form = document.getElementById('upload-form');
        const btn = document.getElementById('analyse-btn');
        const btnLoader=document.getElementById('btn-loader')
        const btnText=document.getElementById('btn-text')

        form.addEventListener('submit', function() {
            btn.disabled = true;
            btnLoader.classList.remove('hidden');
            btn.classList.remove('bg-green-500');
            btn.classList.remove('hover:bg-green-600');
            btn.classList.add('bg-gray-500');
            btnText.innerText='Analysing... Please Wait';
        });
    </script>
@endsection
