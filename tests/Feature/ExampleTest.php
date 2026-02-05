<?php

test('the application returns 200 for the root route', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});
