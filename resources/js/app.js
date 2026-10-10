import './bootstrap';
import Swal from 'sweetalert2';

if (document.querySelector('[data-pdf-flipbook]')) {
  import('./bulletin-flipbook').catch((error) => {
    console.error('Flipbook initialization failed.', error);
  });
}

window.Swal = Swal;

const warna = {
  confirmButtonColor: '#bf0e18',
  cancelButtonColor: '#6b7280',
};

// Toast kecil di pojok kanan atas (untuk success / error / info / warning)
const Toast = Swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 3500,
  timerProgressBar: true,
  didOpen: (toast) => {
    toast.addEventListener('mouseenter', Swal.stopTimer);
    toast.addEventListener('mouseleave', Swal.resumeTimer);
  },
});

window.notify = (icon, title) => Toast.fire({ icon, title });

// Daftar error validasi dalam satu modal
window.notifyErrors = (errors) => {
  const list = document.createElement('ul');
  list.style.cssText = 'text-align:left;margin:0;padding-left:1.2rem;font-size:14px';
  errors.forEach((msg) => {
    const li = document.createElement('li');
    li.textContent = msg; // textContent supaya aman dari XSS
    list.appendChild(li);
  });

  Swal.fire({
    icon: 'error',
    title: 'Periksa kembali isian kamu',
    html: list,
    ...warna,
  });
};

// Dialog konfirmasi generik
window.confirmAction = (options = {}) =>
  Swal.fire({
    icon: 'warning',
    title: 'Apakah kamu yakin?',
    showCancelButton: true,
    confirmButtonText: 'Ya, lanjutkan',
    cancelButtonText: 'Batal',
    reverseButtons: true,
    ...warna,
    ...options,
  });

document.addEventListener('DOMContentLoaded', () => {
  // ===== Menu hamburger (mobile) =====
  const hamburger = document.querySelector('[data-hamburger]');
  const menu = document.querySelector('[data-nav-menu]');
  hamburger?.addEventListener('click', () => {
    menu?.classList.toggle('hidden');
    menu?.classList.toggle('flex');
  });

  // ===== Cursor glow =====
  const glow = document.querySelector('[data-cursor-glow]');
  if (glow) {
    document.addEventListener('mousemove', (e) => {
      glow.style.left = e.clientX + 'px';
      glow.style.top = e.clientY + 'px';
    });
  }

  // ===== Konfirmasi form: <form data-confirm="Hapus artikel ini?"> =====
  document.addEventListener('submit', (e) => {
    const form = e.target.closest('form[data-confirm]');
    if (!form || form.dataset.confirmed) return;

    e.preventDefault();
    window.confirmAction({ text: form.dataset.confirm }).then((result) => {
      if (result.isConfirmed) {
        form.dataset.confirmed = '1';
        form.submit();
      }
    });
  });

  // ===== Reveal on scroll =====
  const items = document.querySelectorAll('.reveal');
  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add('show');
          observer.unobserve(entry.target);
        }
      });
    },
    { threshold: 0.12 }
  );
  items.forEach((el) => observer.observe(el));
});
