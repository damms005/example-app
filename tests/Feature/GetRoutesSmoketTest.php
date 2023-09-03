<?php

use Illuminate\Routing\Route;

it('loads main basic "GET" routes', function () {
    $getRoutes = collect(\Route::getRoutes()->getRoutesByMethod()['GET']);

    $getRoutes->each(function (Route $getRoute) {
        $namedRoutesThatDontNeedSmokeTest = getNamedRoutesThatDontNeedSmokeTest();

        if (in_array($getRoute->getName(), $namedRoutesThatDontNeedSmokeTest)) {
            return;
        }

        $urisThatDontNeedSmokeTest = [
            'admin',
            'admin/generate-senate-result',
            'admin/upload-scoresheet',
            'sanctum/csrf-cookie',
            'api/user',
            'api/user',
            'api/user',
            'api/user',
            'livewire/livewire.js',
            'livewire/livewire.js.map',
        ];

        if (in_array($getRoute->uri(), $urisThatDontNeedSmokeTest)) {
            return;
        }

        // if uri matches regex: {<any-alphanumeric>} (i.e has route binding), skip
        if (preg_match('/{[a-zA-Z0-9]+}/', $getRoute->uri())) {
            return;
        }

        $this->get($getRoute->uri())
            ->assertOk();
    });
});

function getNamedRoutesThatDontNeedSmokeTest()
{
    return [
        'cypress.csrf-token',
        'ignition.healthCheck',
        'filament.app.auth.login',
        'admissions.credentials.upload',
        'admissions.credentials.delete',
        'final-submission',
        'print-acknowledgement-slip',
        'admission-letter',
        'login',
        'register',
        'staff.login',
        'password.request',
        'verification.notice',
        'password.confirm',
        'logout',
    ];
}
