<div class="flex justify-center mt-10 px-4 sm:px-6 lg:px-0">
    <div class="bg-white border border-gray-300 rounded-xl p-5 sm:p-6 w-full max-w-md sm:max-w-xl shadow-md">
        <h2 class="text-lg sm:text-xl font-semibold text-gray-800 mb-2">Purge Transactions</h2>
        <p class="text-gray-700 mb-4 text-sm sm:text-base leading-relaxed">
            This will permanently delete <strong>all your transaction history</strong>. This action cannot be undone.
            Please proceed with caution.
        </p>
        <form method="post" action="{{ route('transaction.purge') }}">
            @csrf
            @method('DELETE')
            <button type="submit"
                class="block w-full text-center bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded-md shadow-md transition duration-200">Delete
                All Transactions</button>
        </form>
    </div>
</div>
