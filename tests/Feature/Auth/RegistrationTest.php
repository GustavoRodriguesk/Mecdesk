<?php

use Database\Seeders\PlanoSeeder;

test('registration screen can be rendered', function () {
    $this->seed(PlanoSeeder::class);

    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('new users can register', function () {
    $this->seed(PlanoSeeder::class);

    $response = $this->post('/register', [
        'empresa'  => 'Test Oficina',
        'name'     => 'Test User',
        'email'    => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard', absolute: false));
});
