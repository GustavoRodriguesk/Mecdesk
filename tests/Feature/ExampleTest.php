<?php

it('returns a successful response for public landing page', function () {
    $response = $this->get('/planos');

    $response->assertStatus(200);
});
