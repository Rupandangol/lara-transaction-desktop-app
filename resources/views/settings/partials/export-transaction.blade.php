<div class="flex justify-center mt-10 px-4 sm:px-6 lg:px-0">
    <div class="bg-white border border-gray-300 rounded-2xl p-6 sm:p-8 w-full max-w-md sm:max-w-xl shadow-lg">
        <h2 class="text-xl sm:text-2xl font-bold text-gray-800 mb-3">Download Transaction History</h2>
        <p class="text-gray-700 text-sm sm:text-base leading-relaxed mb-5">
            You can export a complete record of your past transactions. This download includes all available data for
            your account.
        </p>
        <p class="text-gray-600 text-xs sm:text-sm mb-6">
            Note: The file will be generated in CSV format and may take a few seconds depending on your transaction
            volume.
        </p>
        <a href="{{ route('transaction.export') }}"
            class="block w-full text-center bg-green-600 hover:bg-green-700 text-white font-medium py-2.5 px-4 rounded-md shadow transition duration-200">
            Download All Transactions
        </a>
    </div>
</div>
