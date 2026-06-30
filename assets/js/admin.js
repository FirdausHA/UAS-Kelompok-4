(function () {
    'use strict';

    const modal = document.getElementById('studioModal');
    const form = document.getElementById('studioForm');
    const btnAdd = document.getElementById('btnAddStudio');
    const modalTitle = document.getElementById('modalTitle');

    function openModal() {
        if (modal) modal.hidden = false;
    }

    function closeModal() {
        if (modal) modal.hidden = true;
    }

    function resetForm() {
        if (!form) return;
        form.reset();
        document.getElementById('studioId').value = '';
        form.action = '../../controllers/StudioController.php?action=simpan';
        if (modalTitle) modalTitle.textContent = 'Tambah Studio Baru';
    }

    function fillForm(studio) {
        if (!form) return;
        document.getElementById('studioId').value = studio.id;
        document.getElementById('studioNama').value = studio.nama || '';
        document.getElementById('studioGambar').value = studio.gambar || '';
        document.getElementById('studioHarga').value = studio.harga || '';
        document.getElementById('studioLuas').value = studio.luas_area || '';
        document.getElementById('studioRating').value = studio.rating || '5.0';
        document.getElementById('studioStatus').value = studio.status || 'available';
        document.getElementById('studioDeskripsi').value = studio.deskripsi || '';
        document.getElementById('studioPopuler').checked = studio.is_populer == 1;
        form.action = '../../controllers/StudioController.php?action=update';
        if (modalTitle) modalTitle.textContent = 'Edit Studio';
    }

    if (btnAdd) {
        btnAdd.addEventListener('click', function () {
            resetForm();
            openModal();
        });
    }

    document.querySelectorAll('[data-edit-studio]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            try {
                const data = JSON.parse(btn.getAttribute('data-edit-studio'));
                resetForm();
                fillForm(data);
                openModal();
            } catch (e) {
                console.error(e);
            }
        });
    });

    document.querySelectorAll('[data-close-modal]').forEach(function (el) {
        el.addEventListener('click', closeModal);
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeModal();
    });
})();
