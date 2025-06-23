      <aside class="w-64 bg-white shadow-md flex flex-col p-5">
            <div class="flex items-center justify-center h-16 border-b">
                <img src="{{ asset('logo/logo.png') }}" alt="Logo" class="h-8 w-auto">
                <!-- Replace logo.svg with your actual logo -->
            </div>

            <nav class="flex-1 px-4 py-6 space-y-2">
                <a href="#" class="flex items-center px-4 py-2 text-gray-700 rounded-md hover:bg-gray-100 transition">
                    <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" stroke-width="2"
                         viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M3 12l2-2m0 0l7-7 7 7M13 5v12" /></svg>
                    Home
                </a>
                <a href="#" class="flex items-center px-4 py-2 text-gray-700 rounded-md hover:bg-gray-100 transition">
                    <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" stroke-width="2"
                         viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M17 20h5V4H2v16h5" /></svg>
                    Users
                </a>
                <a href="#" class="flex items-center px-4 py-2 text-gray-700 rounded-md hover:bg-gray-100 transition">
                    <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" stroke-width="2"
                         viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12 4v16m8-8H4" /></svg>
                    Settings
                </a>
            </nav>

            <div class="p-4 border-t text-sm text-gray-500">
                &copy; {{ date('Y') }} Analytics
            </div>
        </aside>