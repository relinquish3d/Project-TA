<?php

use App\Models\User;

test('guest is redirected to login when accessing dashboard', function () {
    $response = $this->get('/dashboard');
    $response->assertRedirect('/login');
});

test('authenticated user can access dashboard and see dashboard components', function () {
    $user = User::factory()->create([
        'name' => 'Raka Test',
        'email' => 'rakatest_' . uniqid() . '@example.com',
    ]);

    $response = $this->actingAs($user)->get('/dashboard');

    $response->assertStatus(200);
    $response->assertSee('Selamat datang kembali, Raka Test!');
    $response->assertSee('Ringkasan aktivitas');
    $response->assertSee('Game favorit');
    $response->assertSee('Chat terbaru');
    $response->assertSee('Undangan terbaru');
});

test('authenticated user can access friends page', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/friends');

    $response->assertStatus(200);
    $response->assertSee('Temukan Teman Mabar');
});

test('admin user login redirects to admin dashboard', function () {
    $admin = User::factory()->create([
        'email' => 'admin_test@admin.com',
        'password' => \Illuminate\Support\Facades\Hash::make('admin123'),
        'role' => 'admin',
    ]);

    $response = $this->post('/login', [
        'email' => 'admin_test@admin.com',
        'password' => 'admin123',
    ]);

    $response->assertRedirect(route('admin.dashboard'));
});
