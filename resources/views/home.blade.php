@extends('layouts.app')

@section('title', 'CareWell - Accueil')

@section('content')

<!-- Hero Section -->
<section class="relative pt-24 pb-20 lg:pt-40 lg:pb-32 text-white overflow-hidden bg-hero-gradient">
    <!-- Subtle overlay for texture -->
    <div class="absolute inset-0 opacity-[0.03] pointer-events-none bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')]"></div>
    
    <div class="container mx-auto px-4 relative z-10 max-w-[1320px]">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-16 items-center">
            <div class="lg:col-span-12 xl:col-span-7">
                <div class="inline-flex items-center gap-3 px-5 py-2.5 rounded-2xl glass-effect text-sm font-bold mb-8 animate-in fade-in slide-in-from-bottom-4 duration-700">
                    <span class="flex h-2 w-2 rounded-full bg-accent-600 animate-pulse"></span>
                    <span class="uppercase tracking-widest opacity-90">Plateforme de soins connectés</span>
                </div>
                
                <h1 class="text-5xl lg:text-7xl font-extrabold leading-[1.1] mb-8 tracking-tight animate-in fade-in slide-in-from-bottom-6 duration-700 delay-100">
                    Une équipe médicale <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-accent-600 to-emerald-300">engagée</span> pour vous.
                </h1>
                
                <p class="text-xl text-emerald-50/70 mb-12 max-w-2xl leading-relaxed animate-in fade-in slide-in-from-bottom-8 duration-700 delay-200">
                    CareWell réunit médecins, spécialistes et patients autour d'un parcours de soins personnalisés, sécurisé et disponible en permanence pour votre bien-être.
                </p>
                
                <div class="flex flex-wrap gap-5 mb-16 animate-in fade-in slide-in-from-bottom-10 duration-700 delay-300">
                    <a href="{{ route('register') }}" class="group relative px-8 py-4 bg-white text-emerald-900 font-bold rounded-2xl transition-all hover:scale-105 hover:shadow-premium overflow-hidden">
                        <span class="relative z-10 flex items-center gap-2">
                             <i class="fas fa-user-plus"></i> Créer mon espace
                        </span>
                        <div class="absolute inset-0 bg-emerald-50 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    </a>
                    
                    <a href="{{ route('services') }}" class="px-8 py-4 glass-effect text-white font-bold rounded-2xl transition-all hover:bg-white/20 hover:scale-105 flex items-center gap-2">
                        <i class="fas fa-stethoscope"></i> Nos services
                    </a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 animate-in fade-in slide-in-from-bottom-12 duration-700 delay-500">
                    @php
                        $stats = [
                            ['count' => \App\Models\Appointment::count(), 'label' => 'Rendez-vous'],
                            ['count' => \App\Models\User::whereHas('roles', function($q) { $q->where('name', 'doctor'); })->count(), 'label' => 'Spécialistes'],
                            ['count' => \App\Models\User::count(), 'label' => 'Patients'],
                        ];
                    @endphp
                    @foreach($stats as $stat)
                    <div class="p-6 rounded-3xl glass-effect group hover:bg-white/10 transition-colors">
                        <span class="text-3xl font-extrabold block mb-1 group-hover:scale-110 transition-transform origin-left">{{ $stat['count'] }}+</span>
                        <span class="text-sm font-medium opacity-60 uppercase tracking-wider">{{ $stat['label'] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            
            <div class="lg:col-span-12 xl:col-span-5 h-full">
                <div class="relative p-10 bg-white shadow-premium rounded-[2.5rem] text-slate-800 overflow-hidden group">
                    <!-- Subtle bg element -->
                    <div class="absolute -right-20 -top-20 w-64 h-64 bg-emerald-50 rounded-full blur-3xl opacity-60"></div>
                    
                    <div class="flex items-center justify-between mb-10">
                        <span class="bg-emerald-100 text-emerald-700 rounded-2xl px-5 py-2 text-xs font-bold uppercase tracking-widest flex items-center gap-2">
                            <i class="fas fa-bolt"></i> Disponibilité immédiate
                        </span>
                        <span class="text-slate-400 text-xs font-bold uppercase">Temps réel</span>
                    </div>

                    <h3 class="text-3xl font-bold mb-4 tracking-tight">Prendre rendez-vous</h3>
                    <p class="text-slate-500 mb-10 leading-relaxed">Simplifiez votre parcours de santé en quelques étapes rapides et sécurisées.</p>
                    
                    <div class="space-y-10 relative before:absolute before:left-[11px] before:top-2 before:bottom-2 before:w-[2px] before:bg-slate-100">
                        <div class="relative pl-10">
                            <div class="absolute left-0 top-1 w-6 h-6 rounded-full bg-white border-4 border-emerald-500 z-10"></div>
                            <h4 class="text-base font-bold mb-1">Choisissez votre besoin</h4>
                            <p class="text-sm text-slate-500">Cardiologie, pédiatrie ou simple consultation de routine.</p>
                        </div>
                        <div class="relative pl-10">
                            <div class="absolute left-0 top-1 w-6 h-6 rounded-full bg-white border-4 border-emerald-500 z-10"></div>
                            <h4 class="text-base font-bold mb-1">Validez votre créneau</h4>
                            <p class="text-sm text-slate-500">Sélectionnez le moment qui vous convient le mieux.</p>
                        </div>
                        <div class="relative pl-10">
                            <div class="absolute left-0 top-1 w-6 h-6 rounded-full bg-white border-4 border-emerald-500 z-10"></div>
                            <h4 class="text-base font-bold mb-1">Suivez votre dossier</h4>
                            <p class="text-sm text-slate-500">Retrouvez vos comptes-rendus et ordonnances en ligne.</p>
                        </div>
                    </div>
                    
                    <div class="mt-12">
                        <a href="{{ route('appointments.create') }}" class="block w-full text-center py-5 bg-gradient-to-br from-emerald-500 to-primary-700 text-white font-bold rounded-2xl shadow-xl hover:shadow-emerald-200 transition-all hover:-translate-y-1">
                            Prendre rendez-vous
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-24 bg-white relative">
    <div class="container mx-auto px-4 max-w-[1320px]">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 -mt-32 relative z-20">
            @php
                $features = [
                    ['icon' => 'hands-helping', 'title' => 'Parcours coordonné', 'desc' => 'Une communication fluide entre vos différents praticiens.'],
                    ['icon' => 'mobile-alt', 'title' => 'Application mobile', 'desc' => 'Gérez votre santé du bout des doigts, où que vous soyez.'],
                    ['icon' => 'shield-virus', 'title' => 'Données sécurisées', 'desc' => 'Vos données médicales sont cryptées et protégées.'],
                    ['icon' => 'heartbeat', 'title' => 'Suivi préventif', 'desc' => 'Des rappels et conseils personnalisés pour votre bien-être.'],
                ];
            @endphp
            @foreach($features as $feature)
            <div class="p-10 rounded-[2.5rem] bg-white border border-slate-100 shadow-premium transition-all hover:-translate-y-3 group hover:border-emerald-200">
                <div class="w-16 h-16 rounded-2xl bg-emerald-50 text-emerald-600 text-2xl flex items-center justify-center mb-8 group-hover:bg-emerald-500 group-hover:text-white transition-all duration-500">
                    <i class="fas fa-{{ $feature['icon'] }}"></i>
                </div>
                <h3 class="text-xl font-bold mb-4 tracking-tight group-hover:text-emerald-700 transition-colors">{{ $feature['title'] }}</h3>
                <p class="text-slate-500 text-sm leading-relaxed">{{ $feature['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Services Preview -->
<section class="py-24 bg-slate-50/50">
    <div class="container mx-auto px-4 max-w-[1320px]">
        <div class="flex flex-col lg:flex-row lg:items-end justify-between mb-16 gap-8">
            <div class="max-w-2xl">
                <span class="text-xs font-bold text-emerald-600 uppercase tracking-[0.2em] mb-4 block">Découvrez nos pôles</span>
                <h2 class="text-4xl lg:text-5xl font-black text-slate-900 tracking-tight leading-none mb-6">Des services médicaux pensés pour chaque étape</h2>
                <p class="text-lg text-slate-500 leading-relaxed">Consultez les spécialités disponibles et profitez d'un accompagnement sur-mesure par nos experts de santé.</p>
            </div>
            <a href="{{ route('services') }}" class="px-8 py-4 bg-white border border-slate-200 text-slate-900 font-bold rounded-2xl hover:bg-slate-50 transition-all flex items-center justify-center gap-2 group">
                Tous nos services <i class="fas fa-arrow-right text-xs group-hover:translate-x-1 transition-transform"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach(\App\Models\Service::take(6)->get() as $service)
            <div class="bg-white rounded-[2rem] border border-slate-100 overflow-hidden shadow-soft hover:shadow-premium transition-all duration-500 flex flex-col group">
                <div class="h-64 relative overflow-hidden">
                    @if($service->photo)
                        <img src="{{ asset('storage/' . $service->photo) }}" alt="{{ $service->name }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                    @else
                        <div class="w-full h-full bg-slate-100 flex items-center justify-center transition-colors group-hover:bg-emerald-50">
                            <i class="fas fa-stethoscope text-5xl text-slate-300 group-hover:text-emerald-300 transition-colors"></i>
                        </div>
                    @endif
                    <div class="absolute top-6 right-6">
                        <span class="px-5 py-2 bg-white/90 backdrop-blur-md rounded-2xl text-slate-900 font-black text-sm shadow-xl">
                            {{ number_format($service->price, 0, ',', ' ') }} FCFA
                        </span>
                    </div>
                </div>
                <div class="p-10 flex flex-col flex-grow">
                    <h5 class="text-2xl font-bold mb-4 tracking-tight text-slate-900">{{ $service->name }}</h5>
                    <p class="text-slate-500 text-sm leading-relaxed mb-8 grow">{{ Str::limit($service->description, 130) }}</p>
                    <a href="{{ route('services.show', $service->id) }}" class="flex items-center justify-center gap-2 py-4 px-6 rounded-2xl bg-emerald-50 text-emerald-700 font-bold hover:bg-emerald-500 hover:text-white transition-all">
                        Détails du service <i class="fas fa-plus text-[10px]"></i>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Stats Impact -->
<section class="py-32 bg-slate-900 overflow-hidden relative">
    <!-- Background accents -->
    <div class="absolute top-0 right-0 w-1/2 h-full bg-emerald-500/5 blur-[120px]"></div>
    <div class="absolute bottom-0 left-0 w-1/4 h-1/2 bg-blue-500/5 blur-[100px]"></div>
    
    <div class="container mx-auto px-4 relative z-10 max-w-[1320px]">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-16 items-center">
            <div class="lg:col-span-12 xl:col-span-5">
                <span class="text-xs font-bold text-emerald-400 uppercase tracking-[0.2em] mb-6 block">Notre Impact</span>
                <h2 class="text-4xl lg:text-5xl font-black text-white tracking-tight leading-tight mb-8">Une communauté médicale en pleine expansion</h2>
                <p class="text-lg text-slate-400 leading-relaxed mb-0">Nous accompagnons chaque jour des milliers de patients et de praticiens pour une santé plus humaine, plus connectée et plus efficace.</p>
            </div>
            <div class="lg:col-span-12 xl:col-span-7">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                    @php
                        $impact = [
                            ['icon' => 'users', 'count' => \App\Models\User::count(), 'label' => 'Patients'],
                            ['icon' => 'user-md', 'count' => \App\Models\User::whereHas('roles', function($q) { $q->where('name', 'doctor'); })->count(), 'label' => 'Spécialistes'],
                            ['icon' => 'calendar-check', 'count' => \App\Models\Appointment::count(), 'label' => 'Consultations'],
                        ];
                    @endphp
                    @foreach($impact as $item)
                    <div class="p-10 rounded-[2.5rem] bg-white/5 border border-white/10 text-center group hover:bg-white/10 transition-all">
                        <div class="w-14 h-14 rounded-2xl bg-white/10 flex items-center justify-center mx-auto mb-6 text-emerald-400 group-hover:scale-110 transition-transform">
                            <i class="fas fa-{{ $item['icon'] }} text-xl"></i>
                        </div>
                        <h3 class="text-4xl font-black text-white mb-2">{{ $item['count'] }}+</h3>
                        <p class="text-slate-400 text-xs font-bold uppercase tracking-widest">{{ $item['label'] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-24 bg-white">
    <div class="container mx-auto px-4 max-w-[1320px]">
        <div class="bg-gradient-to-br from-slate-50 to-emerald-50 rounded-[3rem] p-12 lg:p-24 text-center border border-emerald-100 relative overflow-hidden">
            <!-- Shape decoration -->
            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[800px] h-[800px] bg-white opacity-40 rounded-full blur-3xl -translate-y-1/2"></div>
            
            <div class="relative z-10 max-w-3xl mx-auto">
                <span class="text-xs font-bold text-emerald-600 uppercase tracking-[0.2em] mb-6 block">Rejoignez-nous</span>
                <h2 class="text-4xl lg:text-6xl font-black text-slate-900 tracking-tight leading-none mb-10 italic">Prêt à transformer votre suivi de santé ?</h2>
                <p class="text-lg text-slate-500 mb-12 leading-relaxed">Inscrivez-vous dès aujourd'hui et profitez d'une plateforme conçue pour faciliter la collaboration entre vous et votre équipe médicale.</p>
                
                <div class="flex flex-wrap justify-center gap-6">
                    <a href="{{ route('register') }}" class="px-10 py-5 bg-slate-900 text-white font-bold rounded-2xl hover:bg-slate-800 transition-all hover:scale-105 hover:shadow-2xl shadow-slate-200">
                        Créer mon compte
                    </a>
                    <a href="{{ route('contact') }}" class="px-10 py-5 bg-white border border-slate-200 text-slate-900 font-bold rounded-2xl hover:bg-slate-50 transition-all hover:scale-105">
                        Nous contacter
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
