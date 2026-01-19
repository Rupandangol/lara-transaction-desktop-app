<div class="flex justify-center mt-10 px-4 sm:px-6 lg:px-0">
    <div class="bg-white border border-gray-300 rounded-xl p-5 sm:p-6 w-full max-w-md sm:max-w-xl shadow-md">
        <h2 class="text-lg sm:text-xl font-semibold text-gray-800 mb-2">Add Api key</h2>
        <p class="text-gray-700 mb-4 text-sm sm:text-base leading-relaxed">
            Api key listed here
        </p>
        <div class="flex gap-2 mb-4">
            <button type="button" id="btn-list" class="px-4 py-2 rounded-md shadow-md bg-green-600 text-white">
                List
            </button>
            <button type="button" id="btn-add" class="px-4 py-2 rounded-md shadow-md bg-gray-200 text-gray-700">
                Add New Api key
            </button>
        </div>
        <div id="add-section" class="border p-2 rounded-md shadow-md {{ $errors->any() ? '' : 'hidden' }}">
            <form class="space-y-3" method="post" action="{{ route('user-ai-key.store') }}">
                @csrf
                <div>
                    <label class="font-semibold">Name</label>
                    <input value="{{ old('name') ?? '' }}" class="w-full rounded-md shadow-md p-2" name="name"
                        type="text">
                    @if ($errors->has('name'))
                        <code class="text-red-600">{{ $errors->first('name') }}</code>
                    @endif
                </div>
                <div>
                    <label class="font-semibold">Provider</label>
                    <select class="w-full rounded-md shadow-md p-2" name="provider">
                        @foreach ($ai_provider as $item)
                            <option value="{{ $item }}">{{ $item }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('provider'))
                        <code class="text-red-600">{{ $errors->first('provider') }}</code>
                    @endif
                </div>
                <div>
                    <label class="font-semibold">Api-key</label>
                    <input class="w-full rounded-md shadow-md p-2" name="api_key" type="text">
                    @if ($errors->has('api_key'))
                        <code class="text-red-600">{{ $errors->first('api_key') }}</code>
                    @endif
                </div>
                <div>
                    <label class="font-semibold">Status</label>
                    <select class="w-full rounded-md shadow-md p-2" name="is_active">
                        <option value='1'>Active</option>
                        <option value='0'>InActive</option>
                    </select>
                    @if ($errors->has('is_active'))
                        <code class="text-red-600">{{ $errors->first('is_active') }}</code>
                    @endif
                </div>
                <button type="submit"
                    class="block w-full text-center bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-md shadow-md transition duration-200">Add
                    api key</button>
            </form>
        </div>

        <div id="list-section" class="border p-2 rounded-md shadow-md {{ $errors->any() ? 'hidden' : '' }}">
            <table class="min-w-full border border-gray-300 text-sm text-gray-800">
                <thead class="bg-gray-200 text-left font-semibold">
                    <tr>
                        <th class="border px-4 py-2">ID</th>
                        <th class="border px-4 py-2">Name</th>
                        <th class="border px-4 py-2">Provider</th>
                        <th class="border px-4 py-2">Api</th>
                        <th class="border px-4 py-2">Status</th>
                        <th class="border px-4 py-2">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($user_ai_keys as $item)
                        <tr class="hover:bg-gray-100 transition">
                            <td class="border px-4 py-2">{{ $item->id }}</td>
                            <td class="border px-4 py-2">{{ $item->name }}</td>
                            <td class="border px-4 py-2">{{ $item->provider }}</td>
                            <td class="border px-4 py-2">{{ $item->masked_key }}</td>
                            <td class="border px-4 py-2">
                                <form action="{{ route('user-ai-key.switch', $item->id) }}" method="post">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit">
                                        <span class="p-2
                                            {{ $item->is_active ? 'bg-red-500 hover:bg-red-800' : 'bg-green-500 hover:bg-green-800' }}
                                            rounded-md text-white">
                                            {{ $item->is_active ? 'Off' : 'On' }}
                                        </span>
                                    </button>
                                </form>
                            </td>
                            <td class="border px-4 py-2">
                                <form method="post" action="{{ route('user-ai-key.destroy', $item->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="block w-full text-center bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded-md shadow-md transition duration-200">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr class="hover:bg-gray-100 transition">
                            <td class="border px-4 py-2 text-center" colspan="6">No date</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-6 mb-4">
                {{ $user_ai_keys->links() }}
            </div>
        </div>
    </div>
</div>
<script>
    const btnAdd = document.getElementById('btn-add');
    const btnList = document.getElementById('btn-list');

    const addSection = document.getElementById('add-section');
    const listSection = document.getElementById('list-section');

    btnAdd.addEventListener('click', () => {
        addSection.classList.remove('hidden');
        listSection.classList.add('hidden');

        btnAdd.classList.add('bg-green-600', 'text-white');
        btnAdd.classList.remove('bg-gray-200', 'text-gray-700');

        btnList.classList.add('bg-gray-200', 'text-gray-700');
        btnList.classList.remove('bg-green-600', 'text-white');
    });

    btnList.addEventListener('click', () => {
        listSection.classList.remove('hidden');
        addSection.classList.add('hidden');

        btnList.classList.add('bg-green-600', 'text-white');
        btnList.classList.remove('bg-gray-200', 'text-gray-700');

        btnAdd.classList.add('bg-gray-200', 'text-gray-700');
        btnAdd.classList.remove('bg-green-600', 'text-white');
    });
</script>
