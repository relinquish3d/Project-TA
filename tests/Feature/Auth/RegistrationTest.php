<?php

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('new users can register through multi-step flow', function () {
    $responseStep1 = $this->post('/register/step-1', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);
    $responseStep1->assertRedirect(route('register.profile'));

    $responseStep2 = $this->post('/create-profile', [
        'display_name' => 'TestPlayer',
        'bio' => 'Hello world',
    ]);
    $responseStep2->assertRedirect(route('register.game'));

    $responseStep3 = $this->post('/register/complete', [
        'games' => ['Valorant'],
    ]);

    $this->assertAuthenticated();
    $responseStep3->assertRedirect(route('dashboard', absolute: false));
});
