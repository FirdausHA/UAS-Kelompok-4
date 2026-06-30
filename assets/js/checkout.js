/**
 * Obsidian Studio - Halaman Checkout
 */
(function () {
    'use strict';

    function initScrollAnimations() {
        document.querySelectorAll('.animate-on-load').forEach(function (el, i) {
            const delay = parseInt(el.dataset.delay || '0', 10);
            setTimeout(function () {
                el.classList.add('is-visible');
            }, 200 + i * 80 + delay);
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

    function initPaymentMethods() {
        const methods = document.querySelectorAll('.payment-method input[type="radio"]');
        const bankDetails = document.getElementById('bankDetails');
        const ewalletDetails = document.getElementById('ewalletDetails');
        const paymentInput = document.getElementById('paymentMethodInput');

        methods.forEach(function (radio) {
            radio.addEventListener('change', function () {
                document.querySelectorAll('.payment-method').forEach(function (item) {
                    item.classList.remove('is-selected');
                });
                radio.closest('.payment-method').classList.add('is-selected');

                if (paymentInput) paymentInput.value = radio.value;

                if (bankDetails) bankDetails.hidden = radio.value !== 'bank';
                if (ewalletDetails) ewalletDetails.hidden = radio.value !== 'ewallet';
            });
        });
    }

    function initCopyAccount() {
        const btn = document.getElementById('btnCopyAccount');
        const accountEl = document.getElementById('accountNumber');
        if (!btn || !accountEl) return;

        btn.addEventListener('click', function () {
            const number = accountEl.textContent.replace(/\s/g, '');
            navigator.clipboard.writeText(number).then(function () {
                const original = btn.textContent;
                btn.textContent = 'COPIED';
                setTimeout(function () {
                    btn.textContent = original;
                }, 2000);
            });
        });
    }

    function initUploadZone() {
        const zone = document.getElementById('uploadZone');
        const input = document.getElementById('buktiInput');
        const filenameEl = document.getElementById('uploadFilename');
        if (!zone || !input) return;

        zone.addEventListener('click', function () {
            input.click();
        });

        input.addEventListener('change', function () {
            if (input.files.length > 0) {
                zone.classList.add('has-file');
                if (filenameEl) {
                    filenameEl.textContent = input.files[0].name;
                    filenameEl.hidden = false;
                }
            }
        });

        ['dragenter', 'dragover'].forEach(function (evt) {
            zone.addEventListener(evt, function (e) {
                e.preventDefault();
                zone.classList.add('is-dragover');
            });
        });

        ['dragleave', 'drop'].forEach(function (evt) {
            zone.addEventListener(evt, function (e) {
                e.preventDefault();
                zone.classList.remove('is-dragover');
            });
        });

        zone.addEventListener('drop', function (e) {
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                input.files = files;
                zone.classList.add('has-file');
                if (filenameEl) {
                    filenameEl.textContent = files[0].name;
                    filenameEl.hidden = false;
                }
            }
        });
    }

    function initAlertAutoHide() {
        const alert = document.querySelector('.checkout-alert');
        if (!alert) return;

        setTimeout(function () {
            alert.style.transition = 'opacity 0.5s ease';
            alert.style.opacity = '0';
            setTimeout(function () {
                alert.remove();
            }, 500);
        }, 8000);
    }

    document.addEventListener('DOMContentLoaded', function () {
        initScrollAnimations();
        initMobileNav();
        initPaymentMethods();
        initCopyAccount();
        initUploadZone();
        initAlertAutoHide();
    });
})();
