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
    $response->assertRedirect(route('checkout.show', absolute: false));
});

test('registration rejects password with less than 8 characters', function () {
    $this->seed(PlanoSeeder::class);

    $response = $this->post('/register', [
        'empresa'               => 'Test Oficina',
        'name'                  => 'Test User',
        'email'                 => 'test@example.com',
        'password'              => '1234567',
        'password_confirmation' => '1234567',
    ]);

    $response->assertSessionHasErrors('password');
    $this->assertGuest();
});

test('registration rejects sequential numbers password', function ($password) {
    $this->seed(PlanoSeeder::class);

    $response = $this->post('/register', [
        'empresa'               => 'Test Oficina',
        'name'                  => 'Test User',
        'email'                 => 'test@example.com',
        'password'              => $password,
        'password_confirmation' => $password,
    ]);

    $response->assertSessionHasErrors('password');
    $this->assertGuest();
})->with([
    '12345678',
    '87654321',
    'senha123456',
    'oficina123',
]);
