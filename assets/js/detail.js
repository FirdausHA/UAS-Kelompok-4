/**
 * Obsidian Studio - Halaman Detail Katalog
 * Kalender, slot waktu, add-on, kalkulasi harga, redirect checkout
 */
(function () {
    'use strict';

    const detailEl = document.getElementById('studioDetail');
    if (!detailEl) return;

    const basePrice = parseInt(detailEl.dataset.basePrice, 10) || 0;
    const studioId = detailEl.dataset.studioId || '';
    const studioName = detailEl.dataset.studioName || 'Studio';
    const isPelanggan = detailEl.dataset.isPelanggan === '1';
    const totalEl = document.getElementById('totalPrice');
    const calMonthLabel = document.getElementById('calMonthLabel');
    const calDays = document.getElementById('calDays');
    const calPrev = document.getElementById('calPrev');
    const calNext = document.getElementById('calNext');
    const btnPayment = document.getElementById('btnPayment');

    const urlParams = new URLSearchParams(window.location.search);
    const prefillDate = urlParams.get('tanggal');
    const prefillWaktu = urlParams.get('waktu');

    let currentDate = new Date();
    let selectedDate = new Date();
    selectedDate.setHours(0, 0, 0, 0);
    currentDate = new Date(selectedDate.getFullYear(), selectedDate.getMonth(), 1);
    let selectedHour = null;
    let selectedAddon = 0;

    const monthNames = [
        'January', 'February', 'March', 'April', 'May', 'June',
        'July', 'August', 'September', 'October', 'November', 'December'
    ];

    function parsePrefill() {
        if (prefillDate && /^\d{4}-\d{2}-\d{2}$/.test(prefillDate)) {
            selectedDate = new Date(prefillDate + 'T00:00:00');
            currentDate = new Date(selectedDate.getFullYear(), selectedDate.getMonth(), 1);
        } else {
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            selectedDate = today;
            currentDate = new Date(today.getFullYear(), today.getMonth(), 1);
        }

        if (prefillWaktu) {
            const startHour = prefillWaktu.split('-')[0].trim();
            if (/^\d{2}:\d{2}$/.test(startHour)) {
                selectedHour = startHour;
            }
        }
    }

    function formatDateISO(date) {
        const y = date.getFullYear();
        const m = String(date.getMonth() + 1).padStart(2, '0');
        const d = String(date.getDate()).padStart(2, '0');
        return y + '-' + m + '-' + d;
    }

    function getEndHour(startHour) {
        const parts = startHour.split(':');
<<<<<<< HEAD
        let h = parseInt(parts[0], 10) + 4;
=======
        let h = parseInt(parts[0], 10) + 1;
>>>>>>> cac3d16ec6ccf1868c6d3ae6a9ea2567a7f69b0a
        if (h > 22) h = 22;
        return String(h).padStart(2, '0') + ':' + (parts[1] || '00');
    }

    function formatIDR(amount) {
        return 'IDR ' + amount.toLocaleString('id-ID');
    }

    function updateTotal() {
        const total = basePrice + selectedAddon;
        if (totalEl) {
            totalEl.textContent = formatIDR(total);
            totalEl.classList.remove('is-updated');
            void totalEl.offsetWidth;
            totalEl.classList.add('is-updated');
        }
    }

    function applyTimeSlotSelection() {
        document.querySelectorAll('.time-slot').forEach(function (btn) {
            btn.classList.toggle('is-selected', selectedHour !== null && btn.dataset.hour === selectedHour);
        });
        const hint = document.getElementById('timeSlotsHint');
        if (hint) {
            hint.hidden = selectedHour !== null;
        }
    }

    function renderCalendar() {
        const year = currentDate.getFullYear();
        const month = currentDate.getMonth();

        calMonthLabel.textContent = monthNames[month] + ' ' + year;
        calDays.innerHTML = '';

        const firstDay = new Date(year, month, 1).getDay();
        const daysInMonth = new Date(year, month + 1, 0).getDate();
        const today = new Date();
        today.setHours(0, 0, 0, 0);

        for (let i = 0; i < firstDay; i++) {
            const empty = document.createElement('span');
            empty.className = 'cal-day cal-day-empty';
            calDays.appendChild(empty);
        }

        for (let day = 1; day <= daysInMonth; day++) {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'cal-day';
            btn.textContent = day;

            const cellDate = new Date(year, month, day);
            cellDate.setHours(0, 0, 0, 0);

            if (cellDate < today) {
                btn.classList.add('is-disabled');
                btn.disabled = true;
            }

            if (
                cellDate.getTime() === selectedDate.getTime() &&
                selectedDate.getMonth() === month &&
                selectedDate.getFullYear() === year
            ) {
                btn.classList.add('is-selected');
            }

            btn.addEventListener('click', function () {
                selectedDate = new Date(year, month, day);
                renderCalendar();
            });

            calDays.appendChild(btn);
        }
    }

    function initCalendar() {
        if (!calDays) return;

        calPrev.addEventListener('click', function () {
            currentDate.setMonth(currentDate.getMonth() - 1);
            renderCalendar();
        });

        calNext.addEventListener('click', function () {
            currentDate.setMonth(currentDate.getMonth() + 1);
            renderCalendar();
        });

        renderCalendar();
    }

    function initTimeSlots() {
        document.querySelectorAll('.time-slot').forEach(function (btn) {
            btn.addEventListener('click', function () {
                document.querySelectorAll('.time-slot').forEach(function (b) {
                    b.classList.remove('is-selected');
                });
                btn.classList.add('is-selected');
                selectedHour = btn.dataset.hour;
            });
        });
        applyTimeSlotSelection();
    }

    function initAddons() {
        document.querySelectorAll('.addon-item input[type="radio"]').forEach(function (radio) {
            radio.addEventListener('change', function () {
                document.querySelectorAll('.addon-item').forEach(function (item) {
                    item.classList.remove('is-selected');
                });
                radio.closest('.addon-item').classList.add('is-selected');
                selectedAddon = parseInt(radio.value, 10) || 0;
                updateTotal();
            });
        });
    }

    function initPayment() {
        if (!btnPayment) return;

        btnPayment.addEventListener('click', function () {
            if (!selectedHour) {
                alert('Silakan pilih waktu sesi terlebih dahulu.');
                document.getElementById('timeSlots')?.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                return;
            }

            const addonLabel = document.querySelector('.addon-item.is-selected .addon-name');
            const addon = addonLabel ? addonLabel.textContent.trim() : 'Studio Only';
            const total = basePrice + selectedAddon;
            const waktuRange = selectedHour + '-' + getEndHour(selectedHour);

            const params = new URLSearchParams({
                studio_id: studioId,
                tanggal: formatDateISO(selectedDate),
                waktu: waktuRange,
                addon_label: addon,
                total: String(total),
            });

            const checkoutPath = 'checkout.php?' + params.toString();

            if (!isPelanggan) {
                window.location.href = 'auth/register.php?redirect=' + encodeURIComponent(checkoutPath);
                return;
            }

            window.location.href = checkoutPath;
        });
    }

    function initScrollAnimations() {
        document.querySelectorAll('.animate-on-load').forEach(function (el, i) {
            setTimeout(function () {
                el.classList.add('is-visible');
            }, 200 + i * 120);
        });
    }

    function initMobileNav() {
        const navToggle = document.getElementById('navToggle');
        const navMenu = document.getElementById('navMenu');
        if (!navToggle || !navMenu) return;

        navToggle.addEventListener('click', function () {
            const open = navMenu.classList.toggle('is-open');
            navToggle.classList.toggle('is-open', open);
            navToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        });
    }

    parsePrefill();

    document.addEventListener('DOMContentLoaded', function () {
        initScrollAnimations();
        initMobileNav();
        initCalendar();
        initTimeSlots();
        initAddons();
        initPayment();
        updateTotal();
    });
})();
