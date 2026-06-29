/**
 * Obsidian Studio - Main JS
 * Animasi scroll, navigasi, booking, favorit
 */
(function () {
    'use strict';

    const NAVBAR_HEIGHT = 80;

    /* --- Scroll reveal animasi --- */
    function initScrollAnimations() {
        const loadEls = document.querySelectorAll('.animate-on-load');
        loadEls.forEach(function (el, i) {
            setTimeout(function () {
                el.classList.add('is-visible');
            }, 200 + i * 100);
        });

        const scrollEls = document.querySelectorAll('.animate-on-scroll');
        if (!scrollEls.length) return;

        const observer = new IntersectionObserver(
            function (entries) {
                entries.forEach(function (entry) {
                    if (!entry.isIntersecting) return;
                    const delay = parseInt(entry.target.dataset.delay || '0', 10);
                    setTimeout(function () {
                        entry.target.classList.add('is-visible');
                    }, delay);
                    observer.unobserve(entry.target);
                });
            },
            { threshold: 0.12, rootMargin: '0px 0px -40px 0px' }
        );

        scrollEls.forEach(function (el) {
            observer.observe(el);
        });
    }

    /* --- Smooth scroll + active nav --- */
    function applyStaticNavActive() {
        const activeMenu = document.body.dataset.activeMenu || '';
        document.querySelectorAll('.navbar-menu .nav-link').forEach(function (link) {
            link.classList.toggle('active', link.dataset.menu === activeMenu);
        });
    }

    function initNavigation() {
        const navMode = document.body.dataset.navMode || 'static';
        const navLinks = document.querySelectorAll('.nav-link[data-section]');
        const sections = document.querySelectorAll('#home, #booking, #contact');
        const navToggle = document.getElementById('navToggle');
        const navMenu = document.getElementById('navMenu');

        function scrollToSection(target) {
            const el = document.querySelector(target);
            if (!el) return;
            const top = el.getBoundingClientRect().top + window.scrollY - NAVBAR_HEIGHT;
            window.scrollTo({ top: top, behavior: 'smooth' });
        }

        navLinks.forEach(function (link) {
            link.addEventListener('click', function (e) {
                const href = link.getAttribute('href');
                if (!href || !href.includes('#')) return;
                const hash = href.substring(href.indexOf('#'));
                if (hash.length <= 1) return;

                if (navMode === 'scroll' && document.getElementById('home')) {
                    e.preventDefault();
                    scrollToSection(hash);
                    if (navMenu) navMenu.classList.remove('is-open');
                    if (navToggle) navToggle.classList.remove('is-open');
                }
            });
        });

        if (navToggle && navMenu) {
            navToggle.addEventListener('click', function () {
                const open = navMenu.classList.toggle('is-open');
                navToggle.classList.toggle('is-open', open);
                navToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
            });
        }

        if (navMode !== 'scroll') {
            applyStaticNavActive();
            return;
        }

        function updateActiveNav() {
            let current = 'home';
            const scrollPos = window.scrollY + NAVBAR_HEIGHT + 100;

            sections.forEach(function (section) {
                if (section.offsetTop <= scrollPos) {
                    current = section.id;
                }
            });

            document.querySelectorAll('.navbar-menu .nav-link').forEach(function (link) {
                if (link.dataset.menu === 'catalog') return;
                link.classList.toggle('active', link.dataset.menu === current);
            });
        }

        window.addEventListener('scroll', updateActiveNav, { passive: true });
        updateActiveNav();

        if (window.location.hash) {
            setTimeout(function () {
                scrollToSection(window.location.hash);
            }, 300);
        }
    }

    /* --- Favorit studio --- */
    function initFavorites() {
        const STORAGE_KEY = 'obsidian_favorites';

        function getFavorites() {
            try {
                return JSON.parse(localStorage.getItem(STORAGE_KEY) || '[]');
            } catch (e) {
                return [];
            }
        }

        function saveFavorites(list) {
            localStorage.setItem(STORAGE_KEY, JSON.stringify(list));
        }

        document.querySelectorAll('[data-favorite]').forEach(function (btn) {
            const card = btn.closest('.studio-card');
            const name = card ? card.dataset.studio : '';
            const favorites = getFavorites();

            if (favorites.includes(name)) {
                btn.classList.add('is-active');
            }

            btn.addEventListener('click', function () {
                let list = getFavorites();
                const idx = list.indexOf(name);

                if (idx > -1) {
                    list.splice(idx, 1);
                    btn.classList.remove('is-active');
                } else {
                    list.push(name);
                    btn.classList.add('is-active');
                }

                saveFavorites(list);
            });
        });
    }

    /* --- Pilih studio dari kartu ke booking --- */
    function initStudioSelect() {
        const studioSelect = document.getElementById('booking-studio');
        if (!studioSelect) return;

        document.querySelectorAll('[data-studio-select]').forEach(function (btn) {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                const name = btn.dataset.studioSelect;
                studioSelect.value = name;

                const booking = document.getElementById('booking');
                if (booking) {
                    const top = booking.getBoundingClientRect().top + window.scrollY - NAVBAR_HEIGHT;
                    window.scrollTo({ top: top, behavior: 'smooth' });
                }
            });
        });
    }

    /* --- Form booking: redirect ke halaman detail studio --- */
    function initBookingForm() {
        const form = document.getElementById('bookingForm');
        const dateInput = document.getElementById('booking-date');
        if (!form) return;

        if (dateInput) {
            const today = new Date();
            const iso = today.getFullYear() + '-'
                + String(today.getMonth() + 1).padStart(2, '0') + '-'
                + String(today.getDate()).padStart(2, '0');
            dateInput.value = iso;
            dateInput.min = iso;
        }

        form.addEventListener('submit', function (e) {
            e.preventDefault();

            const studioId = form.querySelector('#booking-studio').value;
            const tanggal = form.querySelector('#booking-date').value;
            const waktu = form.querySelector('#booking-time').value;

            if (!studioId) return;

            const params = new URLSearchParams({
                id: studioId,
                tanggal: tanggal,
                waktu: waktu,
            });

            window.location.href = 'views/studio-detail.php?' + params.toString();
        });
    }

    /* --- Alert contact auto-hide --- */
    function initContactAlert() {
        const alert = document.querySelector('.animate-alert');
        if (!alert) return;

        setTimeout(function () {
            alert.style.transition = 'opacity 0.5s ease';
            alert.style.opacity = '0';
            setTimeout(function () {
                alert.remove();
            }, 500);
        }, 6000);
    }

    document.addEventListener('DOMContentLoaded', function () {
        initScrollAnimations();
        initNavigation();
        initFavorites();
        initStudioSelect();
        initBookingForm();
        initContactAlert();
    });
})();
