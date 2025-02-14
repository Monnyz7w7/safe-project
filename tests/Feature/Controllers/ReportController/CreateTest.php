<?php

use function Pest\Laravel\get;

it('requires authentication', function () {
    
    get(route('reports.create'))
        ->assertRedirect(route('login'));
});
