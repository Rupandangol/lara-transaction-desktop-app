@include('layout.header')

<body class="bg-gray-100 text-gray-800 font-sans antialiased">

    <div class="flex min-h-screen">

        <!-- Sidebar -->
        @include('layout.sidebar')

        <!-- Main Content Area -->
        <main class="flex-1 bg-gray-50 overflow-y-auto">
            <!-- Top bar (optional) -->
            <header class="bg-white shadow px-6 py-4 flex justify-between items-center">
                <h1 class="text-xl font-semibold text-gray-800">@yield('content-header')</h1>
                {{-- Add profile dropdown / logout button here --}}
            </header>

            <!-- Page content -->
            <section class="p-6">
                <div class="container mx-auto px-4">
                    @yield('content')
                </div>
            </section>
        </main>

    </div>
    @yield('content-footer')
</body>
@include('layout.footer')
