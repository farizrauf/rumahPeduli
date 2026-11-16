<?php

namespace Tests\Feature;

use App\Models\Donasi;
use App\Models\Kampanye;
use App\Models\Kebutuhan;
use App\Models\Panti;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertOk()
            ->assertSee('Bersama kita bisa')
            ->assertSee('Panti pilihan');
    }

    public function test_the_default_user_factory_creates_a_user(): void
    {
        $user = User::factory()->create();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => $user->name,
        ]);
    }

    public function test_donor_can_register_and_access_their_dashboard(): void
    {
        $this->post(route('register.store'), [
            'name' => 'Dina Peduli',
            'email' => 'dina@example.test',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'donatur',
            'terms' => '1',
        ])->assertRedirect(route('dashboard'));

        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', ['email' => 'dina@example.test', 'role' => 'donatur']);
        $this->get(route('dashboard'))->assertRedirect(route('donatur.dashboard'));
        $this->get(route('donatur.dashboard'))->assertOk()->assertSee('Dina Peduli');
    }

    public function test_panti_registration_creates_a_pending_application(): void
    {
        $this->post(route('register.store'), [
            'name' => 'Pengelola Panti',
            'email' => 'pengelola@example.test',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'panti',
            'nama_panti' => 'Panti Harapan',
            'alamat' => 'Jalan Kebaikan 10',
            'kota' => 'Bandung',
            'jumlah_anak' => 28,
            'terms' => '1',
        ])->assertRedirect(route('dashboard'));

        $this->assertDatabaseHas('panti', [
            'nama' => 'Panti Harapan',
            'status_verifikasi' => 'pending',
        ]);
        $this->get(route('panti.dashboard'))->assertOk()->assertSee('menunggu verifikasi admin');
    }

    public function test_donor_can_create_a_pending_donation_for_an_active_campaign(): void
    {
        $donor = User::factory()->create();
        $panti = $this->createVerifiedPanti();
        $campaign = $this->createActiveCampaign($panti);

        $this->actingAs($donor)->post(route('donatur.donate.store', $campaign), [
            'nominal' => 50000,
            'metode' => 'QRIS',
        ])->assertRedirect(route('donatur.donations'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('donasi', [
            'user_id' => $donor->id,
            'kampanye_id' => $campaign->id,
            'nominal' => 50000,
            'status' => 'pending',
        ]);
        $this->get(route('campaigns.show', $campaign))->assertOk()->assertSee('Kampanye Kebutuhan Panti');
    }

    public function test_non_donors_cannot_create_donations(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $panti = $this->createVerifiedPanti();
        $campaign = $this->createActiveCampaign($panti);

        $this->actingAs($admin)->get(route('donatur.donate', $campaign))->assertForbidden();
    }

    public function test_admin_can_verify_donations_and_publish_transparent_reports(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $donor = User::factory()->create();
        $panti = $this->createVerifiedPanti();
        $campaign = $this->createActiveCampaign($panti);
        $donation = Donasi::create([
            'user_id' => $donor->id,
            'kampanye_id' => $campaign->id,
            'kode_transaksi' => 'TRX-TEST-0001',
            'nominal' => 250000,
            'metode' => 'Transfer BCA',
        ]);

        $this->actingAs($admin)
            ->post(route('admin.donations.verify', $donation), ['status' => 'verified'])
            ->assertRedirect();

        $this->assertDatabaseHas('donasi', ['id' => $donation->id, 'status' => 'verified']);
        $this->assertDatabaseHas('audit_log', [
            'user_id' => $admin->id,
            'action' => 'donasi.verified',
            'subject_id' => $donation->id,
        ]);
        $this->get(route('admin.dashboard'))->assertOk()->assertSee('Donasi terverifikasi');
        $this->get(route('admin.reports'))->assertOk()->assertSee('250.000');
        $this->get(route('reports.index'))->assertOk()->assertSee('250.000');
        $this->get(route('admin.audit'))->assertOk()->assertSee('donasi');
    }

    public function test_admin_can_verify_a_panti_and_only_verified_panti_are_public(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $pending = $this->createPendingPanti('Panti Menunggu');
        $verified = $this->createVerifiedPanti('Panti Terverifikasi');

        $this->get(route('panti.index'))->assertOk()
            ->assertSee('Panti Terverifikasi')
            ->assertDontSee('Panti Menunggu');

        $this->actingAs($admin)
            ->post(route('admin.panti.verify', $pending), ['status_verifikasi' => 'verified'])
            ->assertRedirect();

        $this->assertDatabaseHas('panti', ['id' => $pending->id, 'status_verifikasi' => 'verified']);
    }

    public function test_admin_can_deactivate_a_donor_and_the_change_is_audited(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $donor = User::factory()->create(['email' => 'inactive@example.test']);

        $this->actingAs($admin)
            ->post(route('admin.users.toggle', $donor))
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('users', ['id' => $donor->id, 'status' => 'nonaktif']);
        $this->assertDatabaseHas('audit_log', ['action' => 'pengguna.nonaktif', 'subject_id' => $donor->id]);
    }

    public function test_admin_can_create_a_user_and_panti_application(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)->post(route('admin.users.store'), [
            'name' => 'Pengelola Baru',
            'email' => 'baru-panti@example.test',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'panti',
            'status' => 'aktif',
            'nama_panti' => 'Panti Baru',
            'alamat' => 'Jl. Baru No. 8',
            'kota' => 'Garut',
            'jumlah_anak' => 12,
        ])->assertRedirect()->assertSessionHas('success');

        $this->assertDatabaseHas('users', ['email' => 'baru-panti@example.test', 'role' => 'panti']);
        $this->assertDatabaseHas('panti', ['nama' => 'Panti Baru', 'status_verifikasi' => 'pending']);
        $this->assertDatabaseHas('audit_log', ['action' => 'pengguna.dibuat']);
    }

    public function test_panti_owner_can_manage_needs_and_active_campaigns(): void
    {
        $owner = User::factory()->create(['role' => 'panti']);
        $panti = Panti::create([
            'user_id' => $owner->id,
            'nama' => 'Panti Pengelola',
            'alamat' => 'Jl. Sejahtera',
            'kota' => 'Bandung',
            'status_verifikasi' => 'verified',
        ]);

        $this->actingAs($owner)->post(route('panti.items.store', 'kebutuhan'), [
            'nama_barang' => 'Buku bacaan',
            'kategori' => 'Pendidikan',
            'jumlah' => 25,
            'prioritas' => 'tinggi',
            'status' => 'dibutuhkan',
        ])->assertRedirect()->assertSessionHas('success');

        $this->actingAs($owner)->post(route('panti.items.store', 'kampanye'), [
            'judul' => 'Bantu Perpustakaan',
            'kategori' => 'Pendidikan',
            'target_dana' => 1000000,
            'tanggal_mulai' => now()->toDateString(),
            'tanggal_selesai' => now()->addMonth()->toDateString(),
            'status' => 'aktif',
        ])->assertRedirect()->assertSessionHas('success');

        $this->assertDatabaseHas('kebutuhan', ['panti_id' => $panti->id, 'nama_barang' => 'Buku bacaan']);
        $this->assertDatabaseHas('kampanye', ['panti_id' => $panti->id, 'judul' => 'Bantu Perpustakaan']);
        $campaign = Kampanye::where('judul', 'Bantu Perpustakaan')->firstOrFail();
        $this->actingAs($owner)->delete(route('panti.items.destroy', ['kampanye', $campaign->id]))
            ->assertSessionHas('success');

        $need = Kebutuhan::where('panti_id', $panti->id)->firstOrFail();
        $this->actingAs($owner)->put(route('panti.items.update', ['kebutuhan', $need->id]), [
            'nama_barang' => 'Buku cerita anak',
            'kategori' => 'Pendidikan',
            'jumlah' => 30,
            'prioritas' => 'sedang',
            'status' => 'dibutuhkan',
        ])->assertRedirect()->assertSessionHas('success');

        $this->assertDatabaseHas('kebutuhan', ['id' => $need->id, 'nama_barang' => 'Buku cerita anak']);
    }

    public function test_panti_cannot_delete_a_campaign_that_has_donations(): void
    {
        $owner = User::factory()->create(['role' => 'panti']);
        $panti = Panti::create([
            'user_id' => $owner->id,
            'nama' => 'Panti Mitra',
            'alamat' => 'Jl. Kebaikan',
            'kota' => 'Bandung',
            'status_verifikasi' => 'verified',
        ]);
        $campaign = $this->createActiveCampaign($panti);
        $donation = Donasi::create([
            'user_id' => User::factory()->create()->id,
            'kampanye_id' => $campaign->id,
            'kode_transaksi' => 'TRX-LOCKED-0001',
            'nominal' => 10000,
            'metode' => 'QRIS',
        ]);

        $this->actingAs($owner)
            ->delete(route('panti.items.destroy', ['kampanye', $campaign->id]))
            ->assertStatus(422);

        $this->assertDatabaseHas('donasi', ['id' => $donation->id]);
    }

    public function test_demo_seeder_populates_example_accounts_and_relationships(): void
    {
        $this->seed();

        $this->assertDatabaseHas('users', ['email' => 'admin@rumahpeduli.test', 'role' => 'admin']);
        $this->assertDatabaseHas('panti', ['nama' => 'Rumah Harapan Ibu', 'status_verifikasi' => 'verified']);
        $this->assertDatabaseHas('panti', ['nama' => 'Panti Cahaya Kasih', 'status_verifikasi' => 'pending']);
        $this->assertDatabaseHas('kampanye', ['judul' => 'Paket Gizi untuk 120 Anak Panti', 'status' => 'aktif']);
        $this->get(route('panti.index'))->assertDontSee('Panti Cahaya Kasih');
    }

    private function createVerifiedPanti(string $name = 'Panti Terverifikasi'): Panti
    {
        $owner = User::factory()->create(['role' => 'panti']);

        return Panti::create([
            'user_id' => $owner->id,
            'nama' => $name,
            'alamat' => 'Jl. Peduli No. 1',
            'kota' => 'Bandung',
            'status_verifikasi' => 'verified',
        ]);
    }

    private function createPendingPanti(string $name): Panti
    {
        $owner = User::factory()->create(['role' => 'panti']);

        return Panti::create([
            'user_id' => $owner->id,
            'nama' => $name,
            'alamat' => 'Jl. Peduli No. 2',
            'kota' => 'Bandung',
            'status_verifikasi' => 'pending',
        ]);
    }

    private function createActiveCampaign(Panti $panti): Kampanye
    {
        return Kampanye::create([
            'panti_id' => $panti->id,
            'judul' => 'Kampanye Kebutuhan Panti',
            'target_dana' => 1000000,
            'tanggal_mulai' => now()->subDay()->toDateString(),
            'tanggal_selesai' => now()->addMonth()->toDateString(),
            'status' => 'aktif',
        ]);
    }
}
