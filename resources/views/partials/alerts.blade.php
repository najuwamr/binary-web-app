{{--
  Menampilkan notifikasi SweetAlert dari session flash dan error validasi.

  Cara pakai di controller:
    return back()->with('success', 'Pesan berhasil dikirim!');
    return back()->with('error', 'Terjadi kesalahan.');
    return back()->with('warning', '...');   // juga: info
  Error validasi ($errors) otomatis ditampilkan.
--}}
<script>
  document.addEventListener('DOMContentLoaded', function () {
    @if (session('success')) window.notify('success', @json(session('success'))); @endif
    @if (session('error'))   window.notify('error',   @json(session('error')));   @endif
    @if (session('warning')) window.notify('warning', @json(session('warning'))); @endif
    @if (session('info'))    window.notify('info',    @json(session('info')));    @endif

    @if ($errors->any())
      window.notifyErrors(@json($errors->all()));
    @endif
  });
</script>
