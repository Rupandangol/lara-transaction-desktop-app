@extends('layout.app')

@section('content-header')
    Admin
@endsection

@section('content')
    <div class="flex p-5">
        <div class="bg-white w-full rounded-md shadow-md mb-5 p-5">
            <h1 class="text-lg font-extrabold text-gray-700 text-end py-5">Admins List</h1>
            <div class="overflow-x-auto">
                <table class="min-w-full border-gray-300 text-gray-800">
                    <thead class="bg-gray-200 text-left font-semibold">
                        <tr>
                            <th class="border px-4 py-2">id</th>
                            <th class="border px-4 py-2">Name</th>
                            <th class="border px-4 py-2">Email</th>
                            <th class="border px-4 py-2">Registered since</th>
                            <th class="border px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($admins as $key=>$item)
                            <tr class="hover:bg-gray-100 transition">
                                <td class="border px-4 py-2">{{ ++$key }}</td>
                                <td class="border px-4 py-2">{{ $item->name }}
                                    @if (auth()->user()->id === $item->id)
                                        <span
                                            class="text-sm bg-green-300 p-1 shadow-md rounded-sm text-gray-500 font-semibold">me</span>
                                    @endif
                                </td>
                                <td class="border px-4 py-2">{{ $item->email }}</td>
                                <td class="border px-4 py-2">{{ $item->created_at }}</td>
                                <td class="border px-4 py-2 text-center">
                                    <div class="inline-flex gap-2">
                                        @if (auth()->user()->id != $item->id)
                                            <form method="post" action="{{ route('admins.destroy', $item->id) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded">
                                                    Delete
                                                </button>
                                            </form>
                                        @else
                                            <span class="bg-gray-500 px-2 text-sm text-white rounded-md shadow-md">-</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-6 mb-4">
                {{ $admins->links() }}
            </div>
        </div>
    </div>
@endsection
