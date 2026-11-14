<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ALT MOTORS | Espace entreprise</title>
    <link rel="icon" href="https://alt-motors.com/templates/admin/assets/AT-L.png" type="image/x-icon">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Leaflet CSS pour la carte GPS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <!-- Custom CSS relié à Laravel -->
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
</head>
<body>

    <!-- Header Navigation -->
    <header class="navbar">
        <div class="nav-container">
            <div class="brand">
                <img class="brand-logo" src="https://alt-motors.com/templates/admin/assets/AT-L.png" alt="Logo ALT MOTORS">
                <div class="brand-text">
                    <span class="brand-name">ALT MOTORS</span>
                    <span class="brand-sub">ESPACE ENTREPRISE</span>
                </div>
            </div>
            
            <nav class="nav-menu">
                <button class="nav-btn active" data-tab="dashboard">
                    <i class="fa-solid fa-gauge" aria-hidden="true"></i> Tableau de bord
                </button>
                <button class="nav-btn" data-tab="simulator">
                    <i class="fa-solid fa-calculator" aria-hidden="true"></i> Simuler un devis LLD
                </button>
                <button class="nav-btn" data-tab="contracts">
                    <i class="fa-solid fa-file-lines" aria-hidden="true"></i> Contrats & Factures
                </button>
            </nav>

            <div class="user-profile">
                <div class="avatar">
                    <img class="avatar-logo" src="https://alt-motors.com/templates/admin/assets/AT-L.png" alt="">
                </div>
                <div class="user-info">
                    <span class="company-name">Espace entreprise</span>
                    <span class="user-role">{{ $vehicles->count() > 0 ? 'Flotte connectée' : 'Compte non connecté' }}</span>
                </div>
            </div>
            <a class="business-phone" href="tel:+22897759232" aria-label="Appeler l’assistance ALT MOTORS au +228 97 75 92 32">
                <i class="fa-solid fa-phone" aria-hidden="true"></i>
                <span>Assistance<strong>+228 97 75 92 32</strong></span>
            </a>
        </div>
    </header>

    <!-- Message de confirmation après soumission d'un devis -->
    @if(session('success'))
        <div style="max-width: 1400px; margin: 1.25rem auto 0; padding: 0 1.5rem;">
            <div style="background: #e6f4ea; color: #287A58; border: 1px solid #287A58; padding: 1rem 1.25rem; border-radius: 4px; font-weight: 700; display: flex; align-items: center; gap: 0.6rem;">
                <i class="fa-solid fa-circle-check"></i>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    <!-- Affichage des erreurs de validation éventuelles -->
    @if($errors->any())
        <div style="max-width: 1400px; margin: 1.25rem auto 0; padding: 0 1.5rem;">
            <div style="background: #fce8e6; color: #E30613; border: 1px solid #E30613; padding: 1rem 1.25rem; border-radius: 4px; font-size: 0.9rem;">
                <strong>Veuillez corriger les erreurs suivantes :</strong>
                <ul style="margin-top: 0.4rem; padding-left: 1.2rem;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <section class="portal-hero" aria-label="Présentation de l’espace entreprise">
        <div class="hero-carousel" id="heroCarousel" aria-roledescription="carrousel">
            <article class="hero-slide active" aria-hidden="false">
                <img src="https://images.unsplash.com/photo-1503376780353-7e6692767b70?auto=format&amp;fit=crop&amp;w=1400&amp;q=75" alt="Véhicule photographié en extérieur" fetchpriority="high">
                <div class="hero-shade"></div>
                <div class="hero-copy">
                    <span class="hero-kicker">ALT MOTORS · ESPACE ENTREPRISE</span>
                    <h1>La mobilité qui accompagne votre activité.</h1>
                    <p>Décrivez votre besoin de location et échangez avec notre équipe pour construire une proposition adaptée à votre entreprise.</p>
                    <button class="hero-action" type="button" data-tab="simulator">Préparer une demande de devis <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></button>
                </div>
            </article>
            <article class="hero-slide" aria-hidden="true">
                <img src="https://images.unsplash.com/photo-1492144534655-ae79c964c9d7?auto=format&amp;fit=crop&amp;w=1400&amp;q=75" alt="Voiture photographiée en extérieur" loading="lazy">
                <div class="hero-shade"></div>
                <div class="hero-copy">
                    <span class="hero-kicker">SOLUTIONS POUR LES PROFESSIONNELS</span>
                    <h2>Un véhicule adapté à vos missions.</h2>
                    <p>Indiquez le volume, la durée et les services souhaités. Les conditions seront confirmées après étude de votre demande.</p>
                    <button class="hero-action" type="button" data-tab="simulator">Décrire mon besoin <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></button>
                </div>
            </article>
            <article class="hero-slide" aria-hidden="true">
                <img src="https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?auto=format&amp;fit=crop&amp;w=1400&amp;q=75" alt="Véhicule photographié en ville" loading="lazy">
                <div class="hero-shade"></div>
                <div class="hero-copy">
                    <span class="hero-kicker">UN ACCOMPAGNEMENT PERSONNALISÉ</span>
                    <h2>De la demande à la solution de location.</h2>
                    <p>Notre équipe vous accompagne pour préciser les caractéristiques et services nécessaires à votre parc automobile.</p>
                    <a class="hero-action" href="tel:+22897759232">Contacter l’équipe <i class="fa-solid fa-phone" aria-hidden="true"></i></a>
                </div>
            </article>
            <div class="hero-controls">
                <button class="hero-arrow" type="button" data-direction="-1" aria-label="Image précédente"><i class="fa-solid fa-arrow-left" aria-hidden="true"></i></button>
                <div class="hero-dots" aria-label="Choisir une image">
                    <button class="hero-dot active" type="button" data-slide-to="0" aria-label="Image 1" aria-current="true"></button>
                    <button class="hero-dot" type="button" data-slide-to="1" aria-label="Image 2" aria-current="false"></button>
                    <button class="hero-dot" type="button" data-slide-to="2" aria-label="Image 3" aria-current="false"></button>
                </div>
                <button class="hero-arrow hero-autoplay-toggle" type="button" aria-label="Mettre le défilement en pause" aria-pressed="false" data-carousel-toggle><i class="fa-solid fa-pause" aria-hidden="true"></i></button>
                <button class="hero-arrow" type="button" data-direction="1" aria-label="Image suivante"><i class="fa-solid fa-arrow-right" aria-hidden="true"></i></button>
            </div>
        </div>
        <div class="business-info-bar">
            <div class="business-info-item">
                <i class="fa-solid fa-car-side" aria-hidden="true"></i>
                <span><strong>Location entreprise</strong><small>Une demande selon vos besoins</small></span>
            </div>
            <div class="business-info-item">
                <i class="fa-solid fa-file-lines" aria-hidden="true"></i>
                <span><strong>Offre sur mesure</strong><small>Tarifs confirmés par notre équipe</small></span>
            </div>
            <div class="business-info-item">
                <i class="fa-solid fa-list-check" aria-hidden="true"></i>
                <span><strong>Votre configuration</strong><small>Durée, volume et services souhaités</small></span>
            </div>
            <a class="business-info-item" href="tel:+22897759232">
                <i class="fa-solid fa-phone" aria-hidden="true"></i>
                <span><strong>Assistance ALT MOTORS</strong><small>+228 97 75 92 32</small></span>
            </a>
        </div>
    </section>

    <!-- Main Container -->
    <main class="main-content">

        <!-- TAB 1: DASHBOARD -->
        <section id="dashboard" class="tab-content active">
            <div class="section-header">
                <div>
                    <h2>Tableau de bord Flotte B2B</h2>
                    <p>Retrouvez vos véhicules, demandes de location et contrats depuis votre espace entreprise.</p>
                </div>
                <button class="btn btn-primary" type="button" data-tab="simulator">
                    <i class="fa-solid fa-plus" aria-hidden="true"></i> Nouvelle demande LLD
                </button>
            </div>

            <!-- KPI Cards reliés à MySQL -->
            <div class="kpi-grid">
                <div class="kpi-card">
                    <div class="kpi-icon"><i class="fa-solid fa-car-side" aria-hidden="true"></i></div>
                    <div class="kpi-data">
                        <span class="kpi-title">Véhicules enregistrés</span>
                        <span class="kpi-value">{{ $vehicles->count() }}</span>
                        <span class="kpi-sub">Synchronisé avec la base XAMPP</span>
                    </div>
                </div>
                <div class="kpi-card">
                    <div class="kpi-icon"><i class="fa-solid fa-gauge-high" aria-hidden="true"></i></div>
                    <div class="kpi-data">
                        <span class="kpi-title">Véhicules disponibles</span>
                        <span class="kpi-value">{{ $vehicles->where('status', 'available')->count() }}</span>
                        <span class="kpi-sub">Prêts à être déployés</span>
                    </div>
                </div>
                <div class="kpi-card">
                    <div class="kpi-icon"><i class="fa-solid fa-wrench" aria-hidden="true"></i></div>
                    <div class="kpi-data">
                        <span class="kpi-title">En maintenance</span>
                        <span class="kpi-value">{{ $vehicles->where('status', 'maintenance')->count() }}</span>
                        <span class="kpi-sub">Suivi atelier Mekano</span>
                    </div>
                </div>
                <div class="kpi-card">
                    <div class="kpi-icon"><i class="fa-solid fa-file-invoice-dollar" aria-hidden="true"></i></div>
                    <div class="kpi-data">
                        <span class="kpi-title">Mensualité Globale LLD</span>
                        <span class="kpi-value">{{ number_format($contracts->sum('monthly_ht'), 0, ',', ' ') }} FCFA</span>
                        <span class="kpi-sub">{{ $contracts->count() }} contrat(s) actif(s)</span>
                    </div>
                </div>
            </div>

            <!-- Vehicles Table & Telemetry -->
            <div class="dashboard-grid">
                <div class="card table-card">
                    <div class="card-title">
                        <h3><i class="fa-solid fa-list" aria-hidden="true"></i> Votre flotte</h3>
                        <input type="text" id="searchVehicle" class="form-control" placeholder="Rechercher par immatriculation ou modèle...">
                    </div>
                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Véhicule</th>
                                    <th>Immatriculation</th>
                                    <th>Conducteur attribué</th>
                                    <th>Kilométrage</th>
                                    <th>Niveau Carburant</th>
                                    <th>Statut Télématique</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="vehicleTableBody">
                                <!-- Généré dynamiquement via index.js avec les données MySQL -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Carte GPS Interactive -->
                <div class="card map-card">
                    <div class="card-title">
                        <h3><i class="fa-solid fa-location-dot" aria-hidden="true"></i> Géolocalisation Flotte (Lomé)</h3>
                    </div>
                    <div class="map-container empty-map" id="mapBox">
                        <i class="fa-solid fa-location-dot map-bg-icon" aria-hidden="true"></i>
                        <p>Aucune position GPS disponible</p>
                        <span>Les données s’afficheront lorsque votre flotte sera connectée.</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- TAB 2: SIMULATEUR DE DEVIS LLD -->
        <section id="simulator" class="tab-content">
            <div class="section-header">
                <div>
                    <h2>Simulateur de Devis LLD B2B</h2>
                    <p>Décrivez votre besoin : les tarifs et disponibilités seront confirmés par ALT MOTORS.</p>
                </div>
            </div>

            <div class="simulator-grid">
                <div class="card form-card">
                    <form id="lldForm" action="{{ route('b2b.quote.store') }}" method="POST">
                        @csrf
                        
                        <div class="form-group">
                            <label>Informations de votre entreprise *</label>
                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 0.75rem;">
                                <input type="text" name="company_name" class="form-control" placeholder="Nom de l'entreprise *" required>
                                <input type="email" name="contact_email" class="form-control" placeholder="Email professionnel *" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Catégorie de Véhicules *</label>
                            <select id="vehicleCategory" name="vehicle_category" class="form-control" required>
                                <option value="" selected disabled>Choisir une catégorie</option>
                                <option value="SUV Premium">SUV premium</option>
                                <option value="SUV">SUV</option>
                                <option value="Berline">Berline</option>
                                <option value="Pick-up">Pick-up / utilitaire</option>
                                <option value="Autre">Autre type de véhicule</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Nombre de véhicules dans le lot *</label>
                            <input type="number" id="vehicleQty" name="quantity" class="form-control" min="1" step="1" placeholder="Saisir une quantité" required>
                        </div>

                        <div class="form-group">
                            <label>Durée du contrat (Mois) *</label>
                            <input type="number" id="durationRange" name="duration_months" class="form-control" min="1" step="1" placeholder="Durée souhaitée en mois" required>
                        </div>

                        <div class="form-group">
                            <label>Forfait Kilométrique Annuel par véhicule</label>
                            <input type="number" id="kmPackage" name="annual_km" class="form-control" min="1" step="1" placeholder="Kilométrage annuel souhaité par véhicule">
                        </div>

                        <div class="form-group">
                            <label>Services & Options d'accompagnement</label>
                            <div class="checkbox-group">
                                <label class="checkbox-item">
                                    <input type="checkbox" name="options[]" value="Entretien et pièces d'usure" id="optMaintenance">
                                    <span>Entretien et pièces d’usure</span>
                                </label>
                                <label class="checkbox-item">
                                    <input type="checkbox" name="options[]" value="Télématique & Geofencing GPS 24/7" id="optGps">
                                    <span>Télématique & Geofencing GPS 24/7</span>
                                </label>
                                <label class="checkbox-item">
                                    <input type="checkbox" name="options[]" value="Chauffeur professionnel" id="optDriver">
                                    <span>Chauffeur professionnel</span>
                                </label>
                                <label class="checkbox-item">
                                    <input type="checkbox" name="options[]" value="Assurance Tout Risque Corporate" id="optInsurance">
                                    <span>Assurance Tout Risque Corporate & Véhicule de remplacement</span>
                                </label>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Live Quote Preview -->
                <div class="card quote-card">
                    <div class="quote-header">
                        <h3>Résumé de la demande</h3>
                        <span class="badge badge-info">Tarif sur devis</span>
                    </div>

                    <div class="quote-body">
                        <div class="quote-row">
                            <span>Catégorie :</span>
                            <strong id="summaryCategory">—</strong>
                        </div>
                        <div class="quote-row">
                            <span>Durée souhaitée :</span>
                            <strong id="durationVal">—</strong>
                        </div>
                        <div class="quote-row">
                            <span>Kilométrage annuel :</span>
                            <strong id="summaryKm">—</strong>
                        </div>
                        <div class="quote-row">
                            <span>Services demandés :</span>
                            <strong id="summaryOptions">—</strong>
                        </div>
                        <div class="quote-divider"></div>
                        <div class="quote-row total-row">
                            <span>Volume :</span>
                            <span class="price-highlight" id="summaryQty">—</span>
                        </div>
                        <div class="quote-row total-row-grand">
                            <span>Estimation :</span>
                            <span class="price-grand">Sur devis</span>
                        </div>
                        <p class="quote-note">Le tarif et les disponibilités seront confirmés par notre équipe après étude de votre demande.</p>

                        <div class="quote-actions">
                            <button class="btn btn-primary btn-block" type="submit" form="lldForm">
                                <i class="fa-solid fa-paper-plane" aria-hidden="true"></i> Envoyer ma demande à ALT MOTORS
                            </button>
                            <button class="btn btn-success btn-block" type="button" onclick="printQuote()" style="background: #252525;">
                                <i class="fa-solid fa-print" aria-hidden="true"></i> Imprimer la demande de devis
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- TAB 3: CONTRATS & FACTURES -->
        <section id="contracts" class="tab-content">
            <div class="section-header">
                <div>
                    <h2>Gestion des Contrats & Facturation</h2>
                    <p>Consultez ici vos contrats et factures enregistrés dans votre espace entreprise.</p>
                </div>
            </div>

            <div class="card">
                <div class="card-title">
                    <h3><i class="fa-solid fa-file-lines" aria-hidden="true"></i> Liste des Contrats LLD Actifs</h3>
                </div>
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Réf. Contrat</th>
                                <th>Date Début</th>
                                <th>Date Échéance</th>
                                <th>Volume Flotte</th>
                                <th>Mensualité HT</th>
                                <th>Statut Règlement</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="contractTableBody"></tbody>
                    </table>
                </div>
            </div>
        </section>

    </main>

    <footer class="portal-footer">
        <div class="footer-main">
            <a class="footer-brand" href="#dashboard">
                <img src="https://alt-motors.com/templates/admin/assets/AT-L.png" alt="Logo ALT MOTORS">
                <span><strong>ALT MOTORS</strong><small>Espace entreprise</small></span>
            </a>
            <p>Un point de contact pour vos demandes de location professionnelle et le suivi de votre espace entreprise.</p>
            <a class="footer-phone" href="tel:+22897759232"><i class="fa-solid fa-phone" aria-hidden="true"></i> +228 97 75 92 32</a>
        </div>
        <div class="footer-bottom">
            <span>ALT MOTORS · Espace entreprise</span>
            <a href="#dashboard">Retour au tableau de bord <i class="fa-solid fa-arrow-up" aria-hidden="true"></i></a>
        </div>
    </footer>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    
    <!-- Passage des données MySQL vers JavaScript -->
    <script>
        window.LARAVEL_VEHICLES = @json($vehicles);
        window.LARAVEL_CONTRACTS = @json($contracts);
    </script>

    <!-- Custom JS relié à Laravel -->
    <script src="{{ asset('js/index.js') }}"></script>
</body>
</html>