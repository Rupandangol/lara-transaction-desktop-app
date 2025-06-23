<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-100 text-gray-800 font-sans antialiased">

    <div class="flex min-h-screen">

        <!-- Sidebar -->
        @include('layout.sidebar')

        <!-- Main Content -->
        <main class="flex-1 p-6 overflow-y-auto bg-gray-50">
            <div class="p-5">
                @yield('content')
            </div>

        </main>
    </div>

</body>

</html>
