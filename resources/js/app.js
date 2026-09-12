import Swal from 'sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
import {
    Chart,
    CategoryScale,
    LinearScale,
    BarElement,
    BarController,
    LineElement,
    LineController,
    PointElement,
    ArcElement,
    DoughnutController,
    Title,
    Tooltip,
    Legend
} from 'chart.js';

Chart.register(
    CategoryScale,
    LinearScale,
    BarElement,
    BarController,
    LineElement,
    LineController,
    PointElement,
    ArcElement,
    DoughnutController,
    Title,
    Tooltip,
    Legend
);

// Expose Chart and Swal globally
window.Chart = Chart;
window.Swal = Swal;

/**
 * Institutional SweetAlert2 Bridge
 */
const UniversitySwal = Swal.mixin({
    customClass: {
        popup: 'rounded-2xl shadow-xl font-sans text-slate-800',
        title: 'text-lg font-bold text-slate-900',
        htmlContainer: 'text-xs text-slate-600',
        confirmButton: 'px-4 py-2 rounded-xl bg-[#0d7a78] hover:bg-[#0a5c5a] text-white text-xs font-semibold shadow-sm focus:outline-none focus:ring-2 focus:ring-[#0d7a78] transition cursor-pointer mx-1',
        cancelButton: 'px-4 py-2 rounded-xl bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-slate-400 transition cursor-pointer mx-1'
    },
    buttonsStyling: false
});

/**
 * Toast Notification (top-end)
 */
window.showToast = function(title, icon = 'success') {
    UniversitySwal.fire({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3500,
        timerProgressBar: true,
        icon: icon,
        title: title
    });
};

/**
 * Confirm Logout Dialog
 */
window.confirmLogout = function(event) {
    if (event) event.preventDefault();
    const form = event ? event.target.closest('form') : document.getElementById('logout-form');

    UniversitySwal.fire({
        title: 'Konfirmasi Keluar',
        text: 'Apakah Anda yakin ingin mengakhiri sesi kedinasan ini?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya, Keluar',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            if (form) {
                form.submit();
            } else {
                document.getElementById('logout-form')?.submit();
            }
        }
    });
};

/**
 * Confirm Action / Delete Dialog
 */
window.confirmDelete = function(target, message = 'Naskah yang dihapus tidak dapat dipulihkan kembali.', title = 'Konfirmasi Hapus?') {
    let confirmText = message;
    if (typeof target === 'string' && message.includes('Naskah yang dihapus') && arguments[1] && arguments[1] !== message) {
        confirmText = `Apakah Anda yakin ingin menghapus naskah surat No. "${arguments[1]}"? Tindakan ini tidak dapat dibatalkan.`;
    } else if (typeof target === 'string' && typeof message === 'string') {
        confirmText = `Apakah Anda yakin ingin menghapus naskah surat No. "${message}"? Tindakan ini tidak dapat dibatalkan.`;
    }

    UniversitySwal.fire({
        title: title,
        text: confirmText,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            if (typeof target === 'function') {
                target();
            } else if (typeof target === 'string' && window.Livewire) {
                window.Livewire.dispatch('hapus-surat-masuk', { id: target });
            }
        }
    });
};

/**
 * Confirm Delete Surat Keluar Dialog
 */
window.confirmDeleteSuratKeluar = function(id, nomor = '') {
    const confirmText = nomor 
        ? `Apakah Anda yakin ingin menghapus naskah surat keluar No. "${nomor}"? Tindakan ini tidak dapat dibatalkan.` 
        : 'Apakah Anda yakin ingin menghapus naskah surat keluar ini? Tindakan ini tidak dapat dibatalkan.';

    UniversitySwal.fire({
        title: 'Konfirmasi Hapus?',
        text: confirmText,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed && window.Livewire) {
            window.Livewire.dispatch('hapus-surat-keluar', { id: id });
        }
    });
};

// Event Listeners for Livewire Dispatched SweetAlerts
window.addEventListener('swal:toast', (e) => {
    const data = e.detail?.[0] || e.detail || {};
    window.showToast(data.message || data.title || 'Operasi berhasil', data.icon || 'success');
});

window.addEventListener('show-toast', (e) => {
    const data = e.detail?.[0] || e.detail || {};
    window.showToast(data.message || 'Operasi berhasil', data.type || data.icon || 'success');
});

window.addEventListener('swal:alert', (e) => {
    const data = e.detail?.[0] || e.detail || {};
    UniversitySwal.fire({
        title: data.title || 'Pemberitahuan',
        text: data.text || data.message || '',
        icon: data.icon || 'info',
        confirmButtonText: 'Mengerti'
    });
});
