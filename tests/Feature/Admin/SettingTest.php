<?php

namespace Tests\Feature\Admin;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SettingTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $customer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
            'phone' => '081234567890',
        ]);

        $this->customer = User::factory()->create([
            'role' => 'customer',
            'phone' => '081234567891',
        ]);
    }

    public function test_setting_model_get_returns_default_when_key_missing(): void
    {
        Setting::clearCache();
        $value = Setting::get('nonexistent_key', 'default_value');
        $this->assertEquals('default_value', $value);
    }

    public function test_setting_model_set_creates_and_retrieves_value(): void
    {
        Setting::clearCache();
        Setting::set('test_key', 'test_value');

        $this->assertDatabaseHas('settings', [
            'key' => 'test_key',
            'value' => 'test_value',
        ]);

        $this->assertEquals('test_value', Setting::get('test_key'));
    }

    public function test_setting_model_set_updates_existing_value(): void
    {
        Setting::clearCache();
        Setting::set('update_key', 'initial');
        Setting::set('update_key', 'updated');

        $this->assertDatabaseCount('settings', 1);
        $this->assertEquals('updated', Setting::get('update_key'));
    }

    public function test_admin_can_access_settings_page(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.settings.edit'));
        $response->assertStatus(200);
        $response->assertSee('Pengaturan Toko');
    }

    public function test_customer_cannot_access_settings_page(): void
    {
        $response = $this->actingAs($this->customer)->get(route('admin.settings.edit'));
        $response->assertStatus(403);
    }

    public function test_admin_can_update_store_name(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.settings.update'), [
            'store_name' => 'Toko Baru',
        ]);

        $response->assertRedirect(route('admin.settings.edit'));
        $response->assertSessionHas('success');

        Setting::clearCache();
        $this->assertEquals('Toko Baru', Setting::get('store_name'));
    }

    public function test_admin_can_upload_store_logo(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('logo.png', 200, 200);

        $response = $this->actingAs($this->admin)->post(route('admin.settings.update'), [
            'store_name' => 'BN Boutique',
            'store_logo' => $file,
        ]);

        $response->assertRedirect(route('admin.settings.edit'));
        $response->assertSessionHas('success');

        Setting::clearCache();
        $logoPath = Setting::get('store_logo');
        $this->assertNotNull($logoPath);
        $this->assertStringContainsString('storage/logos/', $logoPath);
    }

    public function test_store_name_is_required(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.settings.update'), [
            'store_name' => '',
        ]);

        $response->assertSessionHasErrors('store_name');
    }

    public function test_store_logo_must_be_image(): void
    {
        $file = UploadedFile::fake()->create('document.pdf', 500);

        $response = $this->actingAs($this->admin)->post(route('admin.settings.update'), [
            'store_name' => 'BN Boutique',
            'store_logo' => $file,
        ]);

        $response->assertSessionHasErrors('store_logo');
    }

    public function test_admin_can_update_store_whatsapp(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.settings.update'), [
            'store_name' => 'BN Boutique',
            'store_whatsapp' => '6289999999999',
        ]);

        $response->assertRedirect(route('admin.settings.edit'));
        $response->assertSessionHas('success');

        Setting::clearCache();
        $this->assertEquals('6289999999999', Setting::get('store_whatsapp'));
    }

    public function test_store_whatsapp_must_be_digits(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.settings.update'), [
            'store_name' => 'BN Boutique',
            'store_whatsapp' => '+62-899-999-999',
        ]);

        $response->assertSessionHasErrors('store_whatsapp');
    }
}
