<div class="flex justify-center mt-10 px-4 sm:px-6 lg:px-0">
    <div class="bg-white border border-gray-300 rounded-xl p-5 sm:p-6 w-full max-w-md sm:max-w-xl shadow-md">
        <h2 class="text-lg sm:text-xl font-semibold text-gray-800 mb-2">Add Api key</h2>
        <p class="text-gray-700 mb-4 text-sm sm:text-base leading-relaxed">
            Api key listed here
        </p>
        <div class="border p-2 rounded-md shadow-md">
            <form class="space-y-3" action="">
                @csrf
                <div>
                    <label class="font-semibold">Name</label>
                    <input class="w-full rounded-md shadow-md p-2" name="name" type="text">
                </div>
                <div>
                    <label class="font-semibold">Provider</label>
                    <input class="w-full rounded-md shadow-md p-2" name="provider" type="text">
                </div>
                <div>
                    <label class="font-semibold">Api-key</label>
                    <input class="w-full rounded-md shadow-md p-2" name="api_key" type="text">
                </div>
                <button type="submit"
                    class="block w-full text-center bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-md shadow-md transition duration-200">Add
                    api key</button>
            </form>
        </div>
        <div class="border p-2 rounded-md shadow-md">
            <form method="post" action="{{ route('transaction.purge') }}">
                @csrf
                @method('DELETE')
                <div class="block">
                    <label class=" font-semibold " for="api-key">Active api-key</label>
                    <select class="w-full p-2 shadow-md rounded-md mb-2" name="" id="">
                        <option value="">test</option>
                        <option value="">test</option>
                        <option value="">test</option>
                    </select>
                </div>
                <button type="submit"
                    class="block w-full text-center bg-amber-600 hover:bg-amber-700 text-white py-2 px-4 rounded-md shadow-md transition duration-200">Switch
                    api key</button>
            </form>
        </div>
    </div>
</div>
