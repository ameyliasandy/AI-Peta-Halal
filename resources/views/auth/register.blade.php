<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Registrasi</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-green-700 min-h-screen flex items-center justify-center">

<div class="w-full max-w-md px-4">

    <!-- LOGO -->
    <div class="text-center mb-6 text-white">
        <div class="flex justify-center mb-2">
            <!-- icon -->
            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 opacity-80" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 3v10M12 3v10m4-10v10M5 21h14" />
            </svg>
        </div>
        <h1 class="text-3xl font-semibold">Petha</h1>
    </div>

    <!-- CARD -->
    <div class="bg-gray-100 rounded-3xl shadow-lg p-6 sm:p-8">

        <h2 class="text-3xl font-bold text-green-700 text-center mb-6">
            Registrasi
        </h2>

        <form method="POST" action="/register" class="space-y-5">
            @csrf

            <!-- Nama -->
            <div class="flex items-center border-b-2 border-green-300">
                <span class="mr-3 text-green-600">
                    👤
                </span>
                <input name="name" placeholder="Nama Pengguna"
                    class="w-full bg-transparent p-2 focus:outline-none">
            </div>

            <!-- Email -->
            <div class="flex items-center border-b-2 border-green-300">
                <span class="mr-3 text-green-600">
                    ✉️
                </span>
                <input name="email" placeholder="Email"
                    class="w-full bg-transparent p-2 focus:outline-none">
            </div>

            <!-- Password -->
            <div class="flex items-center border-b-2 border-green-300">
                <span class="mr-3 text-green-600">
                    🔒
                </span>
                <input type="password" name="password" placeholder="Kata Sandi"
                    class="w-full bg-transparent p-2 focus:outline-none">
            </div>

            <!-- Confirm -->
            <div class="flex items-center border-b-2 border-green-300">
                <span class="mr-3 text-green-600">
                    🔐
                </span>
                <input type="password" name="password_confirmation" placeholder="Konfirmasi Password"
                    class="w-full bg-transparent p-2 focus:outline-none">
            </div>

            <!-- No HP -->
            <div class="flex items-center border-b-2 border-green-300">
                <span class="mr-3 text-green-600">
                    📞
                </span>
                <input name="no_hp" placeholder="Nomor Handphone"
                    class="w-full bg-transparent p-2 focus:outline-none">
            </div>

            <!-- ROLE -->
            <input type="hidden" name="role" value="{{ request('role') ?? 'pencari' }}">

            <!-- BUTTON -->
            <button class="w-full bg-green-600 text-white py-3 rounded-full font-semibold hover:bg-green-700 transition">
                Daftar
            </button>

            <!-- LOGIN LINK -->
            <p class="text-center text-sm mt-2">
                Sudah Memiliki Akun?
                <a href="/login" class="text-green-700 font-semibold">Log in</a>
            </p>

        </form>

    </div>
</div>

</body>
</html>