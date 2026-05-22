<?php

use App\Models\User;
use App\Models\CustomerAddress;
use App\Models\CustomerBankAccount;

test('guest cannot access customer settings page', function () {
    $response = $this->get('/settings');
    $response->assertRedirect('/login');
});

test('customer can access settings page', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->get('/settings');

    $response->assertOk();
    $response->assertViewIs('customer.settings');
});

test('customer can add shipping address', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->post('/settings/address', [
            'label' => 'Rumah',
            'recipient_name' => 'Budi Santoso',
            'phone' => '081234567890',
            'full_address' => 'Jl. Jenderal Sudirman No. 123, Jakarta',
            'is_default' => 1,
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/settings');

    $this->assertDatabaseHas('customer_addresses', [
        'user_id' => $user->id,
        'label' => 'Rumah',
        'recipient_name' => 'Budi Santoso',
        'phone' => '081234567890',
        'full_address' => 'Jl. Jenderal Sudirman No. 123, Jakarta',
        'is_default' => true,
    ]);
});

test('customer can update shipping address', function () {
    $user = User::factory()->create();
    $address = CustomerAddress::create([
        'user_id' => $user->id,
        'label' => 'Rumah',
        'recipient_name' => 'Budi Old',
        'phone' => '0811111111',
        'full_address' => 'Alamat lama',
        'is_default' => true,
    ]);

    $response = $this
        ->actingAs($user)
        ->put("/settings/address/{$address->id}", [
            'label' => 'Kantor',
            'recipient_name' => 'Budi New',
            'phone' => '0822222222',
            'full_address' => 'Alamat baru',
            'is_default' => 1,
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/settings');

    $this->assertDatabaseHas('customer_addresses', [
        'id' => $address->id,
        'label' => 'Kantor',
        'recipient_name' => 'Budi New',
        'phone' => '0822222222',
        'full_address' => 'Alamat baru',
        'is_default' => true,
    ]);
});

test('customer cannot update another customer address', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $address = CustomerAddress::create([
        'user_id' => $user1->id,
        'label' => 'Rumah',
        'recipient_name' => 'Budi',
        'phone' => '0811111111',
        'full_address' => 'Alamat Budi',
        'is_default' => true,
    ]);

    $response = $this
        ->actingAs($user2)
        ->put("/settings/address/{$address->id}", [
            'label' => 'Kantor',
            'recipient_name' => 'Budi New',
            'phone' => '0822222222',
            'full_address' => 'Alamat baru',
            'is_default' => 1,
        ]);

    $response->assertStatus(403);
});

test('customer can delete shipping address', function () {
    $user = User::factory()->create();
    $address = CustomerAddress::create([
        'user_id' => $user->id,
        'label' => 'Rumah',
        'recipient_name' => 'Budi',
        'phone' => '0811111111',
        'full_address' => 'Alamat Budi',
        'is_default' => true,
    ]);

    $response = $this
        ->actingAs($user)
        ->delete("/settings/address/{$address->id}");

    $response->assertRedirect('/settings');
    $this->assertDatabaseMissing('customer_addresses', ['id' => $address->id]);
});

test('customer can add refund bank account', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->post('/settings/bank-account', [
            'bank_name' => 'BCA',
            'account_number' => '1234567890',
            'account_holder' => 'Budi Santoso',
            'is_default' => 1,
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/settings');

    $this->assertDatabaseHas('customer_bank_accounts', [
        'user_id' => $user->id,
        'bank_name' => 'BCA',
        'account_number' => '1234567890',
        'account_holder' => 'Budi Santoso',
        'is_default' => true,
    ]);
});

test('customer can update refund bank account', function () {
    $user = User::factory()->create();
    $bank = CustomerBankAccount::create([
        'user_id' => $user->id,
        'bank_name' => 'BCA',
        'account_number' => '1234567890',
        'account_holder' => 'Budi Old',
        'is_default' => true,
    ]);

    $response = $this
        ->actingAs($user)
        ->put("/settings/bank-account/{$bank->id}", [
            'bank_name' => 'Mandiri',
            'account_number' => '0987654321',
            'account_holder' => 'Budi New',
            'is_default' => 1,
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/settings');

    $this->assertDatabaseHas('customer_bank_accounts', [
        'id' => $bank->id,
        'bank_name' => 'Mandiri',
        'account_number' => '0987654321',
        'account_holder' => 'Budi New',
        'is_default' => true,
    ]);
});

test('customer cannot update another customer bank account', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $bank = CustomerBankAccount::create([
        'user_id' => $user1->id,
        'bank_name' => 'BCA',
        'account_number' => '1234567890',
        'account_holder' => 'Budi Old',
        'is_default' => true,
    ]);

    $response = $this
        ->actingAs($user2)
        ->put("/settings/bank-account/{$bank->id}", [
            'bank_name' => 'Mandiri',
            'account_number' => '0987654321',
            'account_holder' => 'Budi New',
            'is_default' => 1,
        ]);

    $response->assertStatus(403);
});

test('customer can delete refund bank account', function () {
    $user = User::factory()->create();
    $bank = CustomerBankAccount::create([
        'user_id' => $user->id,
        'bank_name' => 'BCA',
        'account_number' => '1234567890',
        'account_holder' => 'Budi',
        'is_default' => true,
    ]);

    $response = $this
        ->actingAs($user)
        ->delete("/settings/bank-account/{$bank->id}");

    $response->assertRedirect('/settings');
    $this->assertDatabaseMissing('customer_bank_accounts', ['id' => $bank->id]);
});

test('guest cannot update customer profile', function () {
    $response = $this->put('/settings/profile', [
        'name' => 'John Doe',
        'phone' => '081234567890',
    ]);
    $response->assertRedirect('/login');
});

test('customer can update profile without password', function () {
    $user = User::factory()->create([
        'name' => 'Old Name',
        'phone' => '0811111111',
    ]);

    $response = $this
        ->actingAs($user)
        ->put('/settings/profile', [
            'name' => 'New Name',
            'phone' => '0822222222',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/settings');

    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'name' => 'New Name',
        'phone' => '0822222222',
    ]);
});

test('customer can update profile with password', function () {
    $user = User::factory()->create([
        'name' => 'John',
        'password' => \Illuminate\Support\Facades\Hash::make('old_password'),
    ]);

    $response = $this
        ->actingAs($user)
        ->put('/settings/profile', [
            'name' => 'John New',
            'phone' => '081234567890',
            'password' => 'new_password',
            'password_confirmation' => 'new_password',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/settings');

    $user->refresh();
    $this->assertTrue(\Illuminate\Support\Facades\Hash::check('new_password', $user->password));
    $this->assertEquals('John New', $user->name);
    $this->assertEquals('081234567890', $user->phone);
});

test('customer cannot update profile with invalid data', function () {
    $user = User::factory()->create([
        'name' => 'John',
    ]);

    $response = $this
        ->actingAs($user)
        ->put('/settings/profile', [
            'name' => '', // Required
            'phone' => '123',
            'password' => '123', // Min 8
            'password_confirmation' => '1234', // Doesn't match
        ]);

    $response->assertSessionHasErrors(['name', 'password']);
});

