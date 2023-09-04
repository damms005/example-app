<?php

use Livewire\Livewire;
use App\Filament\Resources\UserResource\Pages\ManageUsers;

it('rejects bad names', function () {
    Livewire::test(ManageUsers::class)
        ->fillForm([
            'name' => 'bad',
            'email' => 'test@mail.com',
            'password' => 'password',
        ])
        ->callAction(CreateAction::class)
        ->assertHasFormErrors(['name']);
});
