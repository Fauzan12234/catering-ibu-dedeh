<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Operasional Ibu Dedeh</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700&display=swap" rel="stylesheet">
    <style> body { font-family: 'Manrope', sans-serif; background-color: #f9fafb; } </style>
</head>
<body class="h-screen flex items-center justify-center">
    <div class="bg-white p-10 rounded-2xl shadow-[0_10px_40px_-10px_rgba(0,0,0,0.08)] w-full max-w-md border border-gray-100">
        <div class="text-center mb-8">
            <img src="{{ asset('media/logo.png') }}" alt="Logo" class="h-14 mx-auto mb-4 object-contain">
            <h1 class="text-2xl font-bold text-gray-900">Sistem Operasional</h1>
            <p class="text-sm text-gray-500 mt-1">Silakan masuk ke akun Anda</p>
        </div>

        @if(session('error'))
            <div class="bg-red-50 text-red-600 p-3 rounded-lg text-sm text-center mb-6 border border-red-100">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('login.process') }}" method="POST" class="space-y-5">
            @csrf
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Username</label>
                <input type="text" name="username" class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-lg focus:ring-2 focus:ring-[#570000]/20 focus:border-[#570000] p-3 transition-all" required autofocus>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Password</label>
                <input type="password" name="password" class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-lg focus:ring-2 focus:ring-[#570000]/20 focus:border-[#570000] p-3 transition-all" required>
            </div>
            <button type="submit" class="w-full bg-[#570000] text-white font-bold py-3 rounded-lg hover:bg-[#800000] transition-all shadow-lg shadow-[#570000]/20 mt-4">
                Masuk ke Sistem
            </button>
        </form>
        <div class="mt-8 text-center">
            <a href="{{ route('home') }}" class="text-sm text-gray-400 hover:text-gray-600 transition-colors">&larr; Kembali ke Website Depan</a>
        </div>
    </div>
</body>
</html>