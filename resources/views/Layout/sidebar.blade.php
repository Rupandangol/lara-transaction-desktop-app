<aside class="w-64 bg-gray-900 text-white flex flex-col">

    <!-- Logo -->
    <div class="flex items-center justify-around h-16 border-b border-gray-700">
        <img src="{{ asset('logo/logo.png') }}" alt="Logo" class="h-20 w-auto">
        <form action="{{route('logout')}}" method="post">
            @csrf
            <button class="bg-gray-500 text-white shadow-md rounded p-1" type="submit">Logout</button>
        </form>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 px-4 py-6 space-y-2">

        <a href="#"
           class="flex items-center px-4 py-2 rounded-md hover:bg-indigo-600 transition">
            <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" stroke-width="2"
                 viewBox="0 0 24 24"><path d="M3 12l2-2m0 0l7-7 7 7M13 5v12" /></svg>
            <span>Home</span>
        </a>

        <a href="{{ route('transaction.index') }}"
           class="flex items-center px-4 py-2 rounded-md hover:bg-indigo-600 transition">
            <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" stroke-width="2"
                 viewBox="0 0 24 24"><path d="M17 20h5V4H2v16h5" /></svg>
            <span>Transaction</span>
        </a>

        <a href="#"
           class="flex items-center px-4 py-2 rounded-md hover:bg-indigo-600 transition">
            <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" stroke-width="2"
                 viewBox="0 0 24 24"><path d="M12 4v16m8-8H4" /></svg>
            <span>Settings</span>
        </a>

    </nav>

    <!-- Footer -->
    <div class="p-4 border-t border-gray-700 text-sm text-gray-400">
        &copy; {{ date('Y') }} Analytics
    </div>
</aside>
