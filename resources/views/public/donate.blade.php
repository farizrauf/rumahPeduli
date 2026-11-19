@extends('layouts.app')

@section('title', 'Donasi · '.$kampanye->judul)

@section('content')
<section class="donation-page">
    <div class="container donate-layout">
        <div class="donate-summary">
            <a class="back-link" href="{{ route('campaigns.show', $kampanye) }}">← Kembali ke kampanye</a>
            <span class="eyebrow">SATU KEBAIKAN HARI INI</span>
            <h1>{{ $kampanye->judul }}</h1>
            <p class="donate-location">{{ $kampanye->panti->nama }} · {{ $kampanye->panti->kota }}</p>

            <div class="donate-impact">
                <span class="eyebrow">TARGET KAMPANYE</span>
                <strong>Rp {{ number_format($kampanye->target_dana, 0, ',', '.') }}</strong>
                <p>{{ \Illuminate\Support\Str::limit($kampanye->deskripsi, 150) }}</p>
            </div>

            <p class="trust-note">Donasi Anda akan dicatat sebagai menunggu verifikasi sampai bukti pembayaran ditinjau admin.</p>
        </div>

        <form class="donation-form" method="POST" action="{{ route('donatur.donate.store', $kampanye) }}" enctype="multipart/form-data">
            @csrf
            <span class="eyebrow">FORM DONASI</span>
            <h2>Berapa yang ingin Anda berikan?</h2>
            <p class="form-intro">Pilih nominal atau masukkan jumlah yang paling nyaman untuk Anda.</p>

            <label for="nominal">Nominal donasi <span>(Rp)</span></label>
            <input id="nominal" type="number" name="nominal" value="{{ old('nominal', 50000) }}" min="1000" required>

            <div class="amount-chips" aria-label="Pilihan nominal donasi">
                <button class="amount-chip" type="button" data-amount="25000">Rp25rb</button>
                <button class="amount-chip" type="button" data-amount="50000">Rp50rb</button>
                <button class="amount-chip" type="button" data-amount="100000">Rp100rb</button>
                <button class="amount-chip" type="button" data-amount="250000">Rp250rb</button>
            </div>

            <label for="metode">Metode pembayaran</label>
            <select id="metode" name="metode" required>
                <option value="">Pilih metode pembayaran</option>
                <option value="Transfer BCA" @selected(old('metode') === 'Transfer BCA')>Transfer BCA</option>
                <option value="Transfer Mandiri" @selected(old('metode') === 'Transfer Mandiri')>Transfer Mandiri</option>
                <option value="QRIS" @selected(old('metode') === 'QRIS')>QRIS</option>
                <option value="GoPay" @selected(old('metode') === 'GoPay')>GoPay</option>
            </select>

            <div id="qris-card" class="qris-card" style="display:none;">
                <p class="qris-card-title">QRIS</p>
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=00020101021226570011ID.DANA.WWW011893600914031000000002150000000000000000303UMI51440014ID.CO.QRIS.WWW0215ID10254639108080303UMI5204000053033605802ID5920RUMAH+PEDULI+CHARITY6013Jakarta+Pusat61051234562070703A016304ABCD" alt="QRIS Code" width="200" height="200">
                <p class="qris-card-subtitle">Scan dengan aplikasi e-wallet apapun</p>
                <p class="qris-card-info" id="qris-panti-name">{{ $kampanye->panti->nama }} — {{ $kampanye->judul }}</p>
                <p class="qris-card-info" id="qris-amount-display"></p>
            </div>

            <label for="bukti">Bukti pembayaran <span class="optional">Opsional · JPG/PNG maks. 5 MB</span></label>
            <input id="bukti" type="file" name="bukti" accept="image/*">

            <button class="button button-primary button-wide donation-submit" type="submit">Kirim donasi</button>
            <small class="trust-note">Donasi akan dicatat atas nama <strong>{{ auth()->user()->name }}</strong>.</small>
        </form>
    </div>
</section>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('[data-amount]').forEach((button) => {
        button.addEventListener('click', () => {
            document.querySelector('#nominal').value = button.dataset.amount;
            document.querySelectorAll('.amount-chip').forEach((chip) => chip.classList.remove('selected'));
            button.classList.add('selected');
        });
    });

    (function() {
        var metode = document.getElementById('metode');
        var qrisCard = document.getElementById('qris-card');
        var nominalInput = document.getElementById('nominal');
        var qrisAmount = document.getElementById('qris-amount-display');

        function updateQris() {
            if (metode.value.toLowerCase() === 'qris') {
                qrisCard.style.display = 'block';
                if (qrisAmount && nominalInput) {
                    var val = parseInt(nominalInput.value, 10);
                    if (!isNaN(val) && val > 0) {
                        qrisAmount.textContent = 'Nominal: Rp ' + val.toLocaleString('id-ID');
                    } else {
                        qrisAmount.textContent = '';
                    }
                }
            } else {
                qrisCard.style.display = 'none';
            }
        }

        if (metode) {
            metode.addEventListener('change', updateQris);
            updateQris();
        }
        if (nominalInput) {
            nominalInput.addEventListener('input', function() {
                if (metode.value.toLowerCase() === 'qris' && qrisAmount) {
                    var val = parseInt(nominalInput.value, 10);
                    qrisAmount.textContent = (!isNaN(val) && val > 0)
                        ? 'Nominal: Rp ' + val.toLocaleString('id-ID') : '';
                }
            });
        }
    })();
</script>
@endpush
