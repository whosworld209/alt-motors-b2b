<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alt Motors - Suivi de Flotte & Géolocalisation Live</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Leaflet CSS pour la carte interactive -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        /* Personnalisation de la scrollbar et des popups Leaflet */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #020617; }
        ::-webkit-scrollbar-thumb { background: #1e293b; border-radius: 3px; }
        .leaflet-popup-content-wrapper {
            background: #0f172a;
            color: #f8fafc;
            border: 1px solid #334155;
            border-radius: 0.75rem;
        }
        .leaflet-popup-tip { background: #0f172a; }
    </style>
</head>
<body class="bg-slate-950 text-slate-100 font-sans antialiased min-h-screen flex flex-col">

    <!-- Header / Navbar Alt Motors Style -->
    <header class="border-b border-slate-800/80 bg-slate-950/90 backdrop-blur sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <a href="{{ route('home') }}" class="text-2xl font-black tracking-wider text-white">
                    ALT <span class="text-rose-600">MOTORS</span>
                </a>
                <span class="hidden sm:inline-block text-[11px] font-semibold uppercase tracking-widest px-2.5 py-1 bg-slate-900 border border-slate-800 rounded-md text-slate-400">
                    Live Telemetry
                </span>
            </div>
            
            <nav class="hidden md:flex space-x-8 text-sm font-medium text-slate-300">
                <a href="{{ route('home') }}" class="hover:text-rose-500 transition">Accueil</a>
                <a href="{{ route('b2b.portal') }}" class="hover:text-rose-500 transition">Espace B2B</a>
                <a href="{{ route('fleet.index') }}" class="text-rose-500 font-semibold">Géolocalisation Live</a>
            </nav>

            <div class="flex items-center space-x-3">
                <a href="{{ route('b2b.portal') }}" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-semibold rounded-lg transition">
                    Devis Entreprise
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content Section -->
    <main class="max-w-7xl w-full mx-auto px-6 py-10 flex-1">
        
        <!-- En-tête de section & Statistiques rapides -->
        <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4 border-b border-slate-800/60 pb-6">
            <div>
                <h1 class="text-3xl font-bold tracking-tight text-white">Suivi Géolocalisé de la Flotte</h1>
                <p class="text-slate-400 text-sm mt-1">Supervisez en temps réel l'emplacement GPS et le statut opérationnel des véhicules au Togo.</p>
            </div>
            
            <div class="flex items-center space-x-4">
                <div class="text-right hidden sm:block">
                    <span class="text-xs text-slate-400 block">Véhicules enregistrés</span>
                    <span class="text-lg font-bold text-white">{{ $vehicles->count() }}</span>
                </div>
                <span class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-medium bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                    <span class="w-2 h-2 mr-2 bg-emerald-500 rounded-full animate-pulse"></span>
                    Radar GPS Actif
                </span>
            </div>
        </div>

        <!-- Grille Principale : Liste (Gauche) + Carte (Droite) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <!-- Colonne de Gauche : Liste des véhicules (7 colonnes sur 12) -->
            <div class="lg:col-span-7 space-y-4 max-h-[620px] overflow-y-auto pr-2">
                <div class="flex items-center justify-between mb-2">
                    <h2 class="text-sm font-semibold uppercase tracking-wider text-slate-400">Flotte en service</h2>
                    <button onclick="resetMap()" class="text-xs text-rose-500 hover:text-rose-400 transition">
                        Recentrer sur Lomé
                    </button>
                </div>
                
                @forelse($vehicles as $vehicle)
                <div class="bg-slate-900/60 border border-slate-800/80 rounded-xl p-5 hover:border-slate-700 transition flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                        <div class="flex items-center space-x-3">
                            <h3 class="text-lg font-bold text-white">{{ $vehicle->brand }} {{ $vehicle->model }}</h3>
                            <span class="text-xs font-mono px-2.5 py-0.5 rounded bg-slate-950 border border-slate-800 text-slate-300">
                                {{ $vehicle->plate_number }}
                            </span>
                        </div>
                        
                        <div class="flex flex-wrap items-center gap-4 mt-2.5 text-xs text-slate-400 font-mono">
                            <span>Lat: <strong class="text-slate-200">{{ $vehicle->latitude ?? 'N/A' }}</strong></span>
                            <span>Lng: <strong class="text-slate-200">{{ $vehicle->longitude ?? 'N/A' }}</strong></span>
                            @if($vehicle->last_seen_at)
                                <span class="text-slate-500">• Maj : {{ \Carbon\Carbon::parse($vehicle->last_seen_at)->diffForHumans() }}</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-3 w-full sm:w-auto justify-between sm:justify-end">
                        <!-- Badge Statut -->
                        @if($vehicle->status === 'available')
                            <span class="px-3 py-1 text-xs font-medium rounded-md bg-blue-500/10 text-blue-400 border border-blue-500/20">
                                Disponible
                            </span>
                        @elseif($vehicle->status === 'rented')
                            <span class="px-3 py-1 text-xs font-medium rounded-md bg-rose-500/10 text-rose-400 border border-rose-500/20">
                                En Location
                            </span>
                        @else
                            <span class="px-3 py-1 text-xs font-medium rounded-md bg-amber-500/10 text-amber-400 border border-amber-500/20">
                                Maintenance
                            </span>
                        @endif

                        <!-- Bouton Localiser -->
                        <button 
                            onclick="focusVehicle({{ $vehicle->id }}, {{ $vehicle->latitude ?? 'null' }}, {{ $vehicle->longitude ?? 'null' }})" 
                            class="px-4 py-2 bg-white text-slate-950 font-semibold text-xs rounded-lg hover:bg-rose-600 hover:text-white transition shadow-sm">
                            Localiser
                        </button>
                    </div>
                </div>
                @empty
                <div class="bg-slate-900/50 border border-slate-800 rounded-xl p-12 text-center">
                    <p class="text-slate-300 font-medium">Aucun véhicule enregistré dans la base de données.</p>
                    <p class="text-xs text-slate-500 mt-2">Exécutez <code class="text-rose-400">php artisan db:seed</code> pour ajouter des véhicules de test.</p>
                </div>
                @endforelse
            </div>

            <!-- Colonne de Droite : Carte Interactive GPS (5 colonnes sur 12) -->
            <div class="lg:col-span-5 bg-slate-900/60 border border-slate-800/80 rounded-xl p-5 flex flex-col h-[550px] lg:h-[620px]">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-sm font-semibold uppercase tracking-wider text-slate-400">Carte Interactive (Lomé)</h2>
                    <span class="text-[11px] text-slate-500">Cliquez sur un marqueur</span>
                </div>
                
                <!-- Conteneur de la carte Leaflet -->
                <div id="map" class="flex-1 rounded-lg border border-slate-800 z-10 w-full h-full"></div>
            </div>

        </div>
    </main>

    <!-- Footer sobre -->
    <footer class="border-t border-slate-900 py-6 text-center text-xs text-slate-500">
        &copy; {{ date('Y') }} Africa's Logistics and Transport (Alt Motors) — Système de Gestion de Flotte & Télématique.
    </footer>

    <!-- Scripts Leaflet.js -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        // Coordonnées par défaut : Lomé, Togo
        const LOME_COORDS = [6.1375, 1.2123];

        // 1. Initialisation de la carte
        const map = L.map('map').setView(LOME_COORDS, 12);

        // 2. Fond de carte sombre élégant (CartoDB Dark Matter)
        L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; OpenStreetMap &copy; CARTO',
            maxZoom: 19
        }).addTo(map);

        // 3. Injection des véhicules depuis Laravel Blade vers JavaScript
        const vehicles = @json($vehicles);
        const markers = {};

        // Création d'une icône personnalisée rouge Alt Motors
        const carIcon = L.divIcon({
            className: 'custom-div-icon',
            html: `<div style="background-color:#e11d48; width:14px; height:14px; border-radius:50%; border:3px solid #ffffff; box-shadow: 0 0 10px rgba(225,29,72,0.8);"></div>`,
            iconSize: [14, 14],
            iconAnchor: [7, 7]
        });

        // 4. Placement des marqueurs sur la carte
        vehicles.forEach(vehicle => {
            if (vehicle.latitude && vehicle.longitude) {
                const statusLabel = vehicle.status === 'available' ? 'Disponible' : (vehicle.status === 'rented' ? 'En Location' : 'Maintenance');
                
                const marker = L.marker([vehicle.latitude, vehicle.longitude], { icon: carIcon })
                    .addTo(map)
                    .bindPopup(`
                        <div style="padding: 4px;">
                            <div style="font-weight: 700; font-size: 14px; margin-bottom: 2px;">${vehicle.brand} ${vehicle.model}</div>
                            <div style="font-family: monospace; font-size: 11px; color: #94a3b8; margin-bottom: 6px;">Plaque : ${vehicle.plate_number}</div>
                            <span style="display: inline-block; padding: 2px 8px; border-radius: 4px; font-size: 11px; font-weight: 600; background: #1e293b; color: #f43f5e;">
                                ${statusLabel}
                            </span>
                        </div>
                    `);
                
                markers[vehicle.id] = marker;
            }
        });

        // 5. Fonction pour zoomer sur un véhicule précis au clic sur "Localiser"
        function focusVehicle(id, lat, lng) {
            if (!lat || !lng) {
                alert("Aucune coordonnée GPS enregistrée pour ce véhicule.");
                return;
            }
            map.flyTo([lat, lng], 15, { duration: 1.2 });
            if (markers[id]) {
                markers[id].openPopup();
            }
        }

        // 6. Fonction pour réinitialiser la vue sur tout Lomé
        function resetMap() {
            map.flyTo(LOME_COORDS, 12, { duration: 1.2 });
            map.closePopup();
        }
    </script>
</body>
</html>