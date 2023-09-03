<?php

it('loads users', function () {
    $this->get(route('users.index'))
        ->assertOk();
});
