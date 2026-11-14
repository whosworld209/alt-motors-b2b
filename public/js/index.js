/* =================================================================
   1. NAVIGATION & ONGLETS DU PORTAIL B2B
   =================================================================== */
function initNavigation() {
    const navButtons = document.querySelectorAll('.nav-btn');
    const tabContents = document.querySelectorAll('.tab-content');

    document.querySelectorAll('[data-tab]').forEach((button) => {
        button.addEventListener('click', () => {
            const targetId = button.dataset.tab;
            const target = document.getElementById(targetId);
            if (!target) return;

            navButtons.forEach((item) => item.classList.toggle('active', item.dataset.tab === targetId));
            tabContents.forEach((tab) => tab.classList.toggle('active', tab === target));

            // Redimensionnement de la carte GPS pour éviter les bugs d'affichage à l'ouverture de l'onglet
            if (targetId === 'dashboard' && window.mapInstance) {
                setTimeout(() => {
                    window.mapInstance.invalidateSize();
                }, 200);
            }

            if (!button.classList.contains('nav-btn')) {
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });
}

/* =================================================================
   2. CARROUSEL HERO SECTION
   =================================================================== */
function initHeroCarousel() {
    const carousel = document.getElementById('heroCarousel');
    if (!carousel) return;

    const slides = Array.from(carousel.querySelectorAll('.hero-slide'));
    const dots = Array.from(carousel.querySelectorAll('.hero-dot'));
    if (slides.length < 2 || dots.length !== slides.length) return;

    let activeSlide = 0;
    let autoplayId;
    let isPaused = false;
    const autoplayToggle = carousel.querySelector('[data-carousel-toggle]');

    function showSlide(index) {
        activeSlide = (index + slides.length) % slides.length;
        slides.forEach((slide, slideIndex) => {
            const isActive = slideIndex === activeSlide;
            slide.classList.toggle('active', isActive);
            slide.setAttribute('aria-hidden', String(!isActive));
        });
        dots.forEach((dot, dotIndex) => {
            const isActive = dotIndex === activeSlide;
            dot.classList.toggle('active', isActive);
            dot.setAttribute('aria-current', String(isActive));
        });
    }

    function stopAutoplay() {
        window.clearInterval(autoplayId);
        autoplayId = undefined;
    }

    function startAutoplay() {
        stopAutoplay();
        if (isPaused || window.matchMedia('(prefers-reduced-motion: reduce)').matches || document.hidden) return;
        autoplayId = window.setInterval(() => showSlide(activeSlide + 1), 6500);
    }

    autoplayToggle?.addEventListener('click', () => {
        isPaused = !isPaused;
        autoplayToggle.setAttribute('aria-pressed', String(isPaused));
        autoplayToggle.setAttribute(
            'aria-label',
            isPaused ? 'Reprendre le défilement automatique' : 'Mettre le défilement en pause'
        );
        const icon = autoplayToggle.querySelector('i');
        icon?.classList.toggle('fa-pause', !isPaused);
        icon?.classList.toggle('fa-play', isPaused);
        if (isPaused) stopAutoplay();
        else startAutoplay();
    });

    carousel.querySelectorAll('[data-direction]').forEach((button) => {
        button.addEventListener('click', () => {
            showSlide(activeSlide + Number(button.dataset.direction));
        });
    });

    dots.forEach((dot, index) => {
        dot.addEventListener('click', () => {
            showSlide(index);
        });
    });

    carousel.addEventListener('mouseenter', stopAutoplay);
    carousel.addEventListener('mouseleave', startAutoplay);
    carousel.addEventListener('focusin', stopAutoplay);
    carousel.addEventListener('focusout', (event) => {
        if (!carousel.contains(event.relatedTarget)) startAutoplay();
    });
    document.addEventListener('visibilitychange', startAutoplay);
    window.matchMedia('(prefers-reduced-motion: reduce)').addEventListener('change', startAutoplay);

    showSlide(0);
    startAutoplay();
}

/* =================================================================
   3. GÉOLOCALISATION & CARTE GPS (LEAFLET.JS)
   =================================================================== */
window.mapInstance = null;
let mapMarkers = {};

function initMap() {
    const mapBox = document.getElementById('mapBox');
    if (!mapBox || typeof L === 'undefined') return;

    const vehicles = window.LARAVEL_VEHICLES || [];
    if (vehicles.length === 0) return;

    // Nettoyage de l'état vide et initialisation de la carte centrée sur Lomé
    mapBox.innerHTML = '';
    mapBox.classList.remove('empty-map');
    mapBox.style.height = '350px';

    window.mapInstance = L.map('mapBox').setView([6.1375, 1.2123], 12);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap'
    }).addTo(window.mapInstance);

    // Icône personnalisée rouge Alt Motors (#E30613)
    const redIcon = L.divIcon({
        className: 'custom-pin',
        html: `<div style="background:#E30613; width:14px; height:14px; border-radius:50%; border:2px solid #fff; box-shadow:0 2px 6px rgba(0,0,0,0.4);"></div>`,
        iconSize: [14, 14]
    });

    vehicles.forEach(v => {
        if (v.latitude && v.longitude) {
            const marker = L.marker([v.latitude, v.longitude], { icon: redIcon })
                .addTo(window.mapInstance)
                .bindPopup(`<strong>${v.brand} ${v.model}</strong><br>Plaque : ${v.plate_number}<br>Statut : ${v.status}`);
            mapMarkers[v.id] = marker;
        }
    });
}

function focusVehicle(id, lat, lng) {
    if (window.mapInstance && lat && lng) {
        window.mapInstance.flyTo([lat, lng], 15, { duration: 1.2 });
        if (mapMarkers[id]) {
            mapMarkers[id].openPopup();
        }
    } else {
        alert("Aucune coordonnée GPS enregistrée pour ce véhicule.");
    }
}

/* =================================================================
   4. TABLEAU DE LA FLOTTE & RECHERCHE EN DIRECT (AVEC MINIATURES)
   =================================================================== */
function renderFleetTable() {
    const tableBody = document.getElementById('vehicleTableBody');
    const searchInput = document.getElementById('searchVehicle');
    if (!tableBody) return;

    const vehicles = window.LARAVEL_VEHICLES || [];
    const search = (searchInput?.value || '').toLowerCase().trim();

    const filtered = vehicles.filter(v => 
        (v.brand + ' ' + v.model).toLowerCase().includes(search) ||
        (v.plate_number || '').toLowerCase().includes(search)
    );

    if (filtered.length === 0) {
        tableBody.innerHTML = `<tr><td class="empty-state" colspan="7">Aucun véhicule ne correspond à votre recherche.</td></tr>`;
        return;
    }

    tableBody.innerHTML = filtered.map(v => {
        const statusBadge = v.status === 'available' 
            ? '<span class="badge" style="background:#e6f4ea; color:#287A58;">Disponible</span>'
            : (v.status === 'rented' 
                ? '<span class="badge" style="background:#fce8e6; color:#E30613;">En mission</span>'
                : '<span class="badge" style="background:#fef7e0; color:#A56B17;">Maintenance</span>');

        return `
            <tr>
                <td>
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                        <img src="${v.image || 'https://via.placeholder.com/60'}" alt="${v.model}" style="width: 50px; height: 35px; object-fit: cover; border-radius: 4px; border: 1px solid #ddd;">
                        <strong>${v.brand} ${v.model}</strong>
                    </div>
                </td>
                <td><code>${v.plate_number}</code></td>
                <td>Chauffeur ALT</td>
                <td>12 450 km</td>
                <td>95%</td>
                <td>${statusBadge}</td>
                <td>
                    <button type="button" class="btn btn-primary" style="padding: 0.4rem 0.75rem; font-size: 0.8rem;" 
                        onclick="focusVehicle(${v.id}, ${v.latitude}, ${v.longitude})">
                        <i class="fa-solid fa-location-dot"></i> GPS
                    </button>
                </td>
            </tr>
        `;
    }).join('');
}

/* =================================================================
   5. TABLEAU DES CONTRATS & FACTURES
   =================================================================== */
function renderContractsTable() {
    const contractsBody = document.getElementById('contractTableBody');
    if (!contractsBody) return;

    const contracts = window.LARAVEL_CONTRACTS || [];
    if (contracts.length === 0) {
        contractsBody.innerHTML = '<tr><td class="empty-state" colspan="7">Aucun contrat ou facture enregistré pour le moment.</td></tr>';
        return;
    }

    contractsBody.innerHTML = contracts.map(c => `
        <tr>
            <td><strong>${c.reference}</strong></td>
            <td>${c.start_date}</td>
            <td>${c.end_date}</td>
            <td>${c.fleet_volume} véhicule(s)</td>
            <td><strong>${Number(c.monthly_ht).toLocaleString('fr-FR')} FCFA</strong></td>
            <td><span class="badge" style="background:#e6f4ea; color:#287A58;">${c.payment_status}</span></td>
            <td>
                <button type="button" class="btn btn-primary" style="padding: 0.4rem 0.75rem; font-size: 0.8rem;" onclick="window.print()">
                    <i class="fa-solid fa-print"></i> PDF
                </button>
            </td>
        </tr>
    `).join('');
}

/* =================================================================
   6. SIMULATEUR DE DEVIS B2B (RÉSUMÉ EN DIRECT)
   =================================================================== */
function updateQuoteSummary() {
    const category = document.getElementById('vehicleCategory');
    const quantity = document.getElementById('vehicleQty');
    const duration = document.getElementById('durationRange');
    const mileage = document.getElementById('kmPackage');

    if (!category || !quantity || !duration || !mileage) return;

    const optionLabels = Array.from(
        document.querySelectorAll('.checkbox-item input:checked')
    ).map((input) => input.closest('.checkbox-item')?.querySelector('span')?.textContent.trim())
        .filter(Boolean);

    document.getElementById('summaryCategory').textContent = category.value || 'Non précisée';
    document.getElementById('summaryQty').textContent = quantity.value
        ? `${quantity.value} véhicule(s)`
        : 'Non précisé';
    document.getElementById('durationVal').textContent = duration.value
        ? `${duration.value} mois`
        : 'Non précisée';
    document.getElementById('summaryKm').textContent = mileage.value
        ? `${Number(mileage.value).toLocaleString('fr-FR')} km/an`
        : 'Non précisé';
    document.getElementById('summaryOptions').textContent = optionLabels.length
        ? optionLabels.join(', ')
        : 'Aucune option sélectionnée';
}

function initQuoteForm() {
    const form = document.getElementById('lldForm');
    if (!form) return;

    form.addEventListener('input', updateQuoteSummary);
    form.addEventListener('change', updateQuoteSummary);
    updateQuoteSummary();
}

function printQuote() {
    updateQuoteSummary();
    window.print();
}

/* =================================================================
   7. INITIALISATION GLOBALE AU CHARGEMENT DE LA PAGE
   =================================================================== */
function init() {
    initNavigation();
    initHeroCarousel();

    // Recherche dynamique de véhicules dans le tableau
    const searchInput = document.getElementById('searchVehicle');
    searchInput?.addEventListener('input', renderFleetTable);
    renderFleetTable();

    // Initialisation des données dynamiques issues de MySQL
    initMap();
    renderContractsTable();

    // Gestion du simulateur de devis
    initQuoteForm();
    window.printQuote = printQuote;
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
} else {
    init();
}