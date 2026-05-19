<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login - Petha</title>

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body class="bg-green-700 h-screen flex items-center justify-center px-4 overflow-hidden">

    <!-- WRAPPER -->
    <div class="w-full max-w-[320px] sm:max-w-[340px]">

        <!-- LOGO -->
        <div class="text-center text-white mb-5">

            <div class="flex justify-center mb-2">
                <div class="w-11 h-11 rounded-full border-2 border-white flex items-center justify-center">
                    <i class="fa-solid fa-utensils text-sm"></i>
                </div>
            </div>

            <h1 class="text-2xl sm:text-3xl font-semibold tracking-wide">
                Petha
            </h1>

        </div>

        <!-- CARD -->
        <div class="bg-[#F5F5F5] rounded-[28px] shadow-lg px-5 py-6 sm:px-6">

            <!-- TITLE -->
            <h2 class="text-2xl sm:text-[30px] font-bold text-[#2D6A4F] text-center mb-2">
                Login
            </h2>

            <!-- SUBTITLE -->
            <p class="text-center text-gray-500 text-xs sm:text-sm mb-6">
                Masuk untuk menemukan makanan halal favoritmu
            </p>

            <!-- SUCCESS -->
            @if(session('success'))
                <div class="bg-green-100 text-green-700 text-xs sm:text-sm px-3 py-2 rounded-xl mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <!-- ERROR -->
            @if(session('error'))
                <div class="bg-red-100 text-red-700 text-xs sm:text-sm px-3 py-2 rounded-xl mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <!-- FORM -->
            <form method="POST" action="/login" class="space-y-5">

                @csrf

                <!-- EMAIL -->
                <div class="flex items-center border-b border-green-400 pb-2">

                    <i class="fa-regular fa-envelope text-[#2D6A4F] text-sm w-5"></i>

                    <input
                        type="email"
                        name="email"
                        placeholder="Email"
                        class="w-full bg-transparent px-3 text-sm text-gray-700 placeholder-gray-400 focus:outline-none"
                    >
                </div>

                <!-- PASSWORD -->
                <div class="flex items-center border-b border-green-400 pb-2">

                    <i class="fa-solid fa-lock text-[#2D6A4F] text-sm w-5"></i>

                    <input
                        type="password"
                        name="password"
                        placeholder="Kata Sandi"
                        class="w-full bg-transparent px-3 text-sm text-gray-700 placeholder-gray-400 focus:outline-none"
                    >
                </div>

                <!-- BUTTON -->
                <button
                    type="submit"
                    class="w-full bg-[#3FA34D] hover:bg-green-700 transition text-white py-2.5 rounded-full font-semibold text-sm shadow-sm"
                >
                    Login
                </button>

                <!-- REGISTER -->
                <p class="text-center text-xs sm:text-sm text-gray-700">

                    Belum Memiliki Akun?

                    <a href="/register?role=pencari"
                    class="text-[#2D6A4F] font-semibold hover:underline">
                        Daftar
                    </a>

                </p>

            </form>

        </div>

    </div>

</body>
</html>