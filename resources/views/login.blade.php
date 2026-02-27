<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | PANMUD HUKUM CONNECTION</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700;800&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: radial-gradient(circle at top right, #1e3a8a, #0f172a);
            overflow: hidden;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        .gold-gradient-text {
            background: linear-gradient(to right, #bf953f, #fcf6ba, #b38728, #fbf5b7, #aa771c);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .btn-gold {
            background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%);
            transition: all 0.3s ease;
        }

        .btn-gold:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -5px rgba(30, 58, 138, 0.5);
            filter: brightness(1.1);
        }

        /* Dekorasi Background */
        .circle-bg {
            position: absolute;
            border-radius: 50%;
            background: linear-gradient(to bottom right, rgba(255, 215, 0, 0.1), rgba(255, 255, 255, 0.05));
            z-index: -1;
        }
    </style>
</head>

<body class="flex items-center justify-center min-h-screen relative px-4">

    <div class="circle-bg w-96 h-96 -top-20 -left-20 animate__animated animate__pulse animate__infinite"></div>
    <div class="circle-bg w-80 h-80 -bottom-20 -right-20 animate__animated animate__pulse animate__infinite" style="animation-delay: 1s;"></div>

    <div class="w-full max-w-lg glass-card rounded-[40px] overflow-hidden animate__animated animate__zoomIn">
        
        <div class="relative pt-12 pb-8 text-center bg-[#0f172a]">
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-right from-yellow-600 via-yellow-200 to-yellow-600"></div>
            <div class="inline-flex items-center justify-center w-24 h-24 bg-white rounded-3xl mb-4 shadow-2xl transform -rotate-6">
                <i class="fa-solid fa-scale-balanced text-[#1e3a8a] text-5xl"></i>
            </div>
            <h2 class="text-3xl font-extrabold tracking-tighter gold-gradient-text px-4">
                PANMUD HUKUM CONNECTION
            </h2>
            <div class="flex items-center justify-center mt-2 space-x-2">
                <span class="h-[1px] w-8 bg-yellow-600"></span>
                <p class="text-gray-400 text-xs uppercase tracking-[0.3em] font-semibold">PTA BANDUNG</p>
                <span class="h-[1px] w-8 bg-yellow-600"></span>
            </div>
        </div>

        <div class="p-10">
            @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 border-r-4 border-red-500 rounded-xl animate__animated animate__shakeX">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle text-red-500 mr-3"></i>
                    <p class="text-red-700 text-sm font-bold">{{ $errors->first() }}</p>
                </div>
            </div>
            @endif

            <form action="{{ url('login') }}" method="POST" class="space-y-6">
                @csrf
                <div>
                    <label class="block text-slate-500 text-xs font-bold mb-2 uppercase tracking-widest ml-1">Username</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fa-solid fa-circle-user text-slate-300 group-focus-within:text-blue-600 transition-colors"></i>
                        </div>
                        <input type="text" name="username" required autofocus
                            class="w-full pl-12 pr-4 py-4 bg-gray-50 border border-transparent rounded-2xl focus:bg-white focus:ring-4 focus:ring-blue-100 focus:border-blue-600 outline-none transition-all duration-300 font-semibold text-slate-700 placeholder:text-slate-300"
                            placeholder="Ketik Username Anda">
                    </div>
                </div>

                <div>
                    <label class="block text-slate-500 text-xs font-bold mb-2 uppercase tracking-widest ml-1">Password</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fa-solid fa-shield-halved text-slate-300 group-focus-within:text-blue-600 transition-colors"></i>
                        </div>
                        <input type="password" name="password" required
                            class="w-full pl-12 pr-4 py-4 bg-gray-50 border border-transparent rounded-2xl focus:bg-white focus:ring-4 focus:ring-blue-100 focus:border-blue-600 outline-none transition-all duration-300 font-semibold text-slate-700 placeholder:text-slate-300"
                            placeholder="••••••••••••">
                    </div>
                </div>

                <button type="submit"
                    class="w-full btn-gold text-white font-extrabold py-5 rounded-2xl shadow-xl uppercase tracking-widest flex items-center justify-center space-x-3">
                    <span>Login</span>
                    <i class="fa-solid fa-arrow-right-long animate-bounce-x"></i>
                </button>
            </form>
        </div>

        <div class="pb-8 text-center">
            <p class="text-[10px] text-slate-400 font-medium tracking-tighter uppercase opacity-50">
                Integritas • Transparansi • Akuntabilitas
            </p>
            <p class="text-[10px] text-slate-300 mt-1">
                &copy; {{ date('Y') }} Information Technology Department PTA Bandung
            </p>
        </div>
    </div>

    <script>
        // Animasi bounces sederhana untuk icon panah
        const style = document.createElement('style');
        style.innerHTML = `
            @keyframes bounce-x {
                0%, 100% { transform: translateX(0); }
                50% { transform: translateX(5px); }
            }
            .animate-bounce-x {
                animation: bounce-x 1s infinite;
            }
        `;
        document.head.appendChild(style);
    </script>
</body>

</html>