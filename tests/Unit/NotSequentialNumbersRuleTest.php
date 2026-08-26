<?php

use App\Rules\NotSequentialNumbers;

test('it passes when password has no sequential numbers and is valid', function ($password) {
    $rule = new NotSequentialNumbers();
    $failed = false;

    $rule->validate('password', $password, function () use (&$failed) {
        $failed = true;
    });

    expect($failed)->toBeFalse();
})->with([
    'minhaSenha@2026',
    'OficinaForte8135',
    'p@ssw0rd!#$',
    'senhaComplexa9',
    'a1b2c3d4e5',
]);

test('it fails when password contains ascending sequential numbers', function ($password) {
    $rule = new NotSequentialNumbers();
    $failedMessage = null;

    $rule->validate('password', $password, function ($message) use (&$failedMessage) {
        $failedMessage = $message;
    });

    expect($failedMessage)->not->toBeNull();
    expect($failedMessage)->toContain('A senha não pode conter números sequenciais');
})->with([
    '12345678',
    'senha123456',
    'oficina123',
    '45678abc',
    'abc6789def',
]);

test('it fails when password contains descending sequential numbers', function ($password) {
    $rule = new NotSequentialNumbers();
    $failedMessage = null;

    $rule->validate('password', $password, function ($message) use (&$failedMessage) {
        $failedMessage = $message;
    });

    expect($failedMessage)->not->toBeNull();
    expect($failedMessage)->toContain('A senha não pode conter números sequenciais');
})->with([
    '87654321',
    'senha9876',
    'oficina321',
    '76543abc',
]);

test('it fails when password contains repeated identical numbers', function ($password) {
    $rule = new NotSequentialNumbers();
    $failedMessage = null;

    $rule->validate('password', $password, function ($message) use (&$failedMessage) {
        $failedMessage = $message;
    });

    expect($failedMessage)->not->toBeNull();
})->with([
    '11111111',
    '00000000',
    'senha9999',
]);

