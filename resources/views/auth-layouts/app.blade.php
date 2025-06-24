<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>{{ config('app.name', 'Laravel') }}</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gradient-to-br from-[#101828] via-[#0f172a] to-[#0b1120] text-white">

  <div id="app">
    <!-- Navbar -->
    <nav class="bg-[#101828] shadow-md">
      <div class="container mx-auto px-4 py-4 flex justify-between items-center">
        <a href="{{ url('/') }}" class="text-xl font-bold text-white">
          {{ config('app.name', 'Laravel') }}
        </a>

        <div>
          <ul class="flex space-x-4 items-center text-white">
            @guest
              @if (Route::has('login'))
                <li>
                  <a class="hover:text-blue-400 transition" href="{{ route('login') }}">{{ __('Login') }}</a>
                </li>
              @endif

              @if (Route::has('register'))
                <li>
                  <a class="hover:text-blue-400 transition" href="{{ route('register') }}">{{ __('Register') }}</a>
                </li>
              @endif
            @else
              <li class="relative group">
                <button class="focus:outline-none font-semibold">
                  {{ Auth::user()->name }}
                </button>
                <div class="absolute hidden group-hover:block bg-white text-black right-0 mt-2 rounded shadow-lg w-40">
                  <a href="{{ route('logout') }}"
                     class="block px-4 py-2 hover:bg-gray-100"
                     onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    {{ __('Logout') }}
                  </a>
                  <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                    @csrf
                  </form>
                </div>
              </li>
            @endguest
          </ul>
        </div>
      </div>
    </nav>

    <!-- Main Content -->
    <main class="py-1 px-1">
      @yield('content')
    </main>
  </div>
</body>
</html>
