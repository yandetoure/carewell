<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'CareWell - Plateforme de Santé')</title>

    <!-- Tailwind CSS (via Vite) -->
    @vite(['resources/css/app.css'])
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @yield('styles')
</head>
<body class="font-['Inter',sans-serif] leading-relaxed text-slate-700 bg-slate-50 flex flex-col min-h-screen antialiased">
    <!-- Navigation -->
    <nav id="navbar" class="sticky top-0 z-50 py-4 transition-all duration-500 bg-nav-gradient shadow-xl backdrop-blur-md border-b border-white/10">
        <div class="container mx-auto px-4 max-w-[1320px] flex flex-wrap items-center justify-between">
            <a href="{{ route('home') }}" class="font-bold text-2xl text-white flex items-center transition-all duration-300 hover:scale-[1.02] no-underline group">
                <img src="{{ asset('images/logo.png') }}" alt="CareWell Logo" id="brand-img" class="h-10 w-auto mr-3 transition-all duration-300 group-hover:rotate-3 object-contain">
                <span class="tracking-tight">CareWell</span>
            </a>

            <button type="button" id="mobile-menu-btn" class="lg:hidden border border-white/20 rounded-xl p-2.5 hover:bg-white/10 transition-colors focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6 text-white">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                </svg>
            </button>

            <div id="mobile-menu" class="hidden w-full lg:flex lg:items-center lg:w-auto mt-4 lg:mt-0">
                <ul class="flex flex-col lg:flex-row lg:items-center gap-1 lg:gap-2 lg:ml-auto list-none p-0 m-0">
                    @php
                        $navItems = [
                            ['route' => 'home', 'label' => 'Accueil'],
                            ['route' => 'services', 'label' => 'Services'],
                            ['route' => 'articles', 'label' => 'Articles'],
                            ['route' => 'about', 'label' => 'À propos'],
                            ['route' => 'contact', 'label' => 'Contact'],
                        ];
                    @endphp

                    @foreach($navItems as $item)
                    <li>
                        <a href="{{ route($item['route']) }}" 
                           class="block px-4 py-2 rounded-xl text-white/80 font-medium transition-all duration-300 hover:text-white hover:bg-white/10 relative group {{ request()->routeIs($item['route']) ? 'text-white bg-white/10 font-bold' : '' }}">
                            {{ $item['label'] }}
                            <span class="absolute bottom-1.5 left-4 right-4 h-0.5 bg-emerald-400 scale-x-0 transition-transform duration-300 group-hover:scale-x-100 {{ request()->routeIs($item['route']) ? 'scale-x-100' : '' }}"></span>
                        </a>
                    </li>
                    @endforeach

                    @guest
                        <li class="lg:ml-4 flex flex-col lg:flex-row gap-2">
                            <a href="{{ route('login') }}" class="px-5 py-2 text-white font-medium hover:text-white/80 transition-colors text-center">
                                Connexion
                            </a>
                            <a href="{{ route('register') }}" class="px-6 py-2 bg-emerald-500 text-white font-bold rounded-xl shadow-lg hover:bg-emerald-600 hover:-translate-y-0.5 transition-all text-center">
                                Inscription
                            </a>
                        </li>
                    @else
                        <li class="relative lg:ml-4">
                            <button type="button" id="user-dropdown-btn" class="flex items-center gap-3 px-3 py-1.5 rounded-xl hover:bg-white/10 transition-all text-white outline-none w-full lg:w-auto">
                                <div class="w-8 h-8 rounded-full overflow-hidden border-2 border-white/30 flex-shrink-0">
                                    @if(Auth::user()->photo)
                                        <img src="{{ asset('storage/' . Auth::user()->photo) }}" alt="Avatar" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full bg-blue-500 flex items-center justify-center font-bold text-sm text-white">
                                            {{ strtoupper(substr(Auth::user()->first_name, 0, 1)) }}
                                        </div>
                                    @endif
                                </div>
                                <span class="font-medium truncate max-w-[120px]">{{ Auth::user()->first_name }}</span>
                                <i class="fas fa-chevron-down text-[10px] opacity-60"></i>
                            </button>
                            <div id="user-dropdown-menu" class="hidden absolute lg:right-0 mt-3 w-64 bg-white rounded-2xl shadow-2xl p-2 z-[100] border border-gray-100 transform origin-top lg:origin-top-right transition-all">
                                <div class="px-4 py-3 mb-2 bg-gray-50 rounded-xl">
                                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-0.5">Connecté</p>
                                    <p class="text-xs font-bold text-slate-800 truncate mb-0">{{ Auth::user()->email }}</p>
                                </div>
                                <a class="flex items-center gap-3 rounded-xl px-4 py-2.5 text-slate-600 hover:bg-blue-50 hover:text-blue-700 transition-all group" href="{{ route('dashboard') }}">
                                    <div class="w-9 h-9 rounded-lg bg-blue-50 flex items-center justify-center group-hover:bg-blue-100 transition-colors text-blue-600"><i class="fas fa-tachometer-alt"></i></div>
                                    <span class="font-semibold text-sm">Dashboard</span>
                                </a>
                                <a class="flex items-center gap-3 rounded-xl px-4 py-2.5 text-slate-600 hover:bg-blue-50 hover:text-blue-700 transition-all group" href="{{ route('profile') }}">
                                    <div class="w-9 h-9 rounded-lg bg-blue-50 flex items-center justify-center group-hover:bg-blue-100 transition-colors text-blue-600"><i class="fas fa-user-edit"></i></div>
                                    <span class="font-semibold text-sm">Mon Profil</span>
                                </a>
                                <hr class="my-2 border-gray-100">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center gap-3 rounded-xl px-4 py-2.5 text-red-600 hover:bg-red-50 transition-all group">
                                        <div class="w-9 h-9 rounded-lg bg-red-50 flex items-center justify-center group-hover:bg-red-100 transition-colors text-red-600"><i class="fas fa-sign-out-alt"></i></div>
                                        <span class="font-semibold text-sm text-left">Déconnexion</span>
                                    </button>
                                </form>
                            </div>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <!-- Messages Flash -->
    <div class="fixed top-24 right-4 z-[100] flex flex-col gap-3 max-w-sm w-full pointer-events-none">
    @foreach(['success' => 'bg-emerald-50 border-emerald-200 text-emerald-800 icon-check-circle', 
              'error' => 'bg-red-50 border-red-200 text-red-800 icon-exclamation-triangle', 
              'warning' => 'bg-amber-50 border-amber-200 text-amber-800 icon-exclamation-circle', 
              'info' => 'bg-blue-50 border-blue-200 text-blue-800 icon-info-circle'] as $type => $style)
        @if(session($type))
        <div class="pointer-events-auto p-4 rounded-2xl border {{ explode(' icon-', $style)[0] }} shadow-xl flex items-start gap-3 animate-in fade-in slide-in-from-right-8 duration-500">
            <div class="flex-shrink-0 mt-0.5"><i class="fas {{ explode(' icon-', $style)[1] }} text-lg"></i></div>
            <div class="flex-grow text-sm font-medium">{{ session($type) }}</div>
            <button type="button" onclick="this.parentElement.remove()" class="flex-shrink-0 opacity-40 hover:opacity-100 transition-opacity"><i class="fas fa-times"></i></button>
        </div>
        @endif
    @endforeach
    </div>

    <!-- Main Content -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-slate-900 text-white pt-20 pb-10 border-t border-white/5">
        <div class="container mx-auto px-4 max-w-[1320px]">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-12 mb-16">
                <div class="lg:col-span-4">
                    <div class="mb-8">
                        <img src="{{ asset('images/logo.png') }}" alt="CareWell Logo" class="h-12 w-auto brightness-0 invert">
                    </div>
                    <p class="text-slate-400 mb-10 leading-relaxed max-w-sm">Votre plateforme de santé de confiance, connectant patients et professionnels de santé pour des soins optimaux, coordonnés et sécurisés.</p>
                    <div class="flex gap-4">
                        @foreach(['facebook-f', 'twitter', 'linkedin-in', 'instagram'] as $social)
                        <a href="#" class="w-12 h-12 rounded-2xl bg-white/5 flex items-center justify-center transition-all hover:bg-white hover:text-slate-900 hover:-translate-y-2 group shadow-lg">
                            <i class="fab fa-{{ $social }} transition-transform group-hover:scale-110"></i>
                        </a>
                        @endforeach
                    </div>
                </div>
                <div class="lg:col-span-2">
                    <h5 class="text-white font-bold mb-8 text-xs uppercase tracking-[0.2em]">Services</h5>
                    <ul class="space-y-4 text-slate-400">
                        <li><a href="#" class="hover:text-blue-400 transition-colors">Consultations</a></li>
                        <li><a href="#" class="hover:text-blue-400 transition-colors">Examens</a></li>
                        <li><a href="#" class="hover:text-blue-400 transition-colors">Prescriptions</a></li>
                        <li><a href="#" class="hover:text-blue-400 transition-colors">Suivi médical</a></li>
                    </ul>
                </div>
                <div class="lg:col-span-2">
                    <h5 class="text-white font-bold mb-8 text-xs uppercase tracking-[0.2em]">Ressources</h5>
                    <ul class="space-y-4 text-slate-400">
                        <li><a href="#" class="hover:text-blue-400 transition-colors">Articles santé</a></li>
                        <li><a href="#" class="hover:text-blue-400 transition-colors">Conseils</a></li>
                        <li><a href="#" class="hover:text-blue-400 transition-colors">FAQ</a></li>
                        <li><a href="#" class="hover:text-blue-400 transition-colors">Support</a></li>
                    </ul>
                </div>
                <div class="lg:col-span-4">
                    <h5 class="text-white font-bold mb-8 text-xs uppercase tracking-[0.2em]">Contact</h5>
                    <div class="space-y-6 text-slate-400">
                        <p class="flex items-start gap-4"><i class="fas fa-map-marker-alt mt-1.5 text-blue-500"></i> <span>123 Rue de la Santé,<br>75001 Paris, France</span></p>
                        <p class="flex items-center gap-4"><i class="fas fa-phone text-blue-500"></i> +33 1 23 45 67 89</p>
                        <p class="flex items-center gap-4"><i class="fas fa-envelope text-blue-500"></i> contact@carewell.fr</p>
                    </div>
                </div>
            </div>
            <div class="pt-10 border-t border-white/5 flex flex-col md:flex-row justify-between items-center gap-6 text-slate-500 text-sm">
                <p>&copy; {{ date('Y') }} CareWell. Excellence en soins connectés.</p>
                <div class="flex flex-wrap gap-8 justify-center">
                    <a href="#" class="hover:text-white transition-colors">Mentions légales</a>
                    <a href="#" class="hover:text-white transition-colors">Confidentialité</a>
                    <a href="#" class="hover:text-white transition-colors">Conditions</a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Navbar scroll effect
        window.addEventListener('scroll', () => {
            const navbar = document.getElementById('navbar');
            const brandImg = document.getElementById('brand-img');
            if (window.scrollY > 40) {
                navbar.classList.add('py-2', 'bg-slate-900/95', 'shadow-2xl');
                navbar.classList.remove('py-4', 'bg-nav-gradient');
                brandImg.classList.add('h-8');
                brandImg.classList.remove('h-10');
            } else {
                navbar.classList.add('py-4', 'bg-nav-gradient');
                navbar.classList.remove('py-2', 'bg-slate-900/95', 'shadow-2xl');
                brandImg.classList.add('h-10');
                brandImg.classList.remove('h-8');
            }
        });

        // Mobile menu
        const mobileBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        if(mobileBtn) mobileBtn.onclick = () => mobileMenu.classList.toggle('hidden');

        // User dropdown
        const userBtn = document.getElementById('user-dropdown-btn');
        const userMenu = document.getElementById('user-dropdown-menu');
        if(userBtn) {
            userBtn.onclick = (e) => {
                e.stopPropagation();
                userMenu.classList.toggle('hidden');
            }
            document.onclick = () => userMenu.classList.add('hidden');
        }
    </script>
    @yield('scripts')
</body>
</html>
