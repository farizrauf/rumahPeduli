@php
    $notifType = null;
    $notifTitle = '';
    $notifMessage = '';
    if (session('success')) {
        $notifType = 'success';
        $notifTitle = 'Berhasil!';
        $notifMessage = session('success');
    } elseif (session('error')) {
        $notifType = 'error';
        $notifTitle = 'Terjadi Kesalahan';
        $notifMessage = session('error');
    } elseif (session('warning')) {
        $notifType = 'warning';
        $notifTitle = 'Perhatian';
        $notifMessage = session('warning');
    } elseif (session('info')) {
        $notifType = 'info';
        $notifTitle = 'Informasi';
        $notifMessage = session('info');
    } elseif ($errors->any()) {
        $notifType = 'error';
        $notifTitle = 'Terjadi Kesalahan';
        $notifMessage = $errors->first();
    }
@endphp

@if ($notifType)
<div id="modal-notification-overlay" class="modal-notif-overlay" role="dialog" aria-modal="true" aria-label="Notifikasi">
    <div class="modal-notif-card modal-notif-{{$notifType}}">
        <div class="modal-notif-icon">
            @if ($notifType === 'success')
            <svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
              <circle cx="24" cy="24" r="24" fill="#e8f7ef"/>
              <path d="M14 25l7 7 13-14" stroke="#3cb879" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            @elseif ($notifType === 'error')
            <svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
              <circle cx="24" cy="24" r="24" fill="#fef2f2"/>
              <path d="M17 17l14 14M31 17L17 31" stroke="#ef4444" stroke-width="3" stroke-linecap="round"/>
            </svg>
            @elseif ($notifType === 'warning')
            <svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
              <circle cx="24" cy="24" r="24" fill="#fffbeb"/>
              <path d="M24 18v8M24 30v2" stroke="#f59e0b" stroke-width="3" stroke-linecap="round"/>
            </svg>
            @else
            <svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
              <circle cx="24" cy="24" r="24" fill="#eff6ff"/>
              <path d="M24 18v8M24 30v2" stroke="#3b82f6" stroke-width="3" stroke-linecap="round"/>
            </svg>
            @endif
        </div>
        <h3 class="modal-notif-title">{{$notifTitle}}</h3>
        <p class="modal-notif-message">{{$notifMessage}}</p>
        <button class="modal-notif-btn modal-notif-btn-{{$notifType}}" onclick="closeModalNotif()">OK</button>
    </div>
</div>
<script>
(function(){
    var overlay = document.getElementById('modal-notification-overlay');
    if (!overlay) return;
    document.body.style.overflow = 'hidden';
    @if ($notifType === 'success')
    setTimeout(function(){ closeModalNotif(); }, 4000);
    @endif
})();
function closeModalNotif() {
    var overlay = document.getElementById('modal-notification-overlay');
    if (overlay) {
        overlay.classList.add('modal-notif-closing');
        setTimeout(function(){ overlay.style.display = 'none'; document.body.style.overflow = ''; }, 300);
    }
}
</script>
@endif
