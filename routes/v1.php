<?php declare(strict_types=1);

use FastRoute\RouteCollector;

return function (RouteCollector $r) {
    $r->addGroup('/app/v1', function (RouteCollector $r) {
        $r->post('/users', 'App\\V1\\Controller\\AuthController@register');
        $r->post('/users/login', 'App\\V1\\Controller\\AuthController@login');
        $r->delete('/users/login', 'App\\V1\\Controller\\AuthController@logout');
        $r->get('/users/me', 'App\\V1\\Controller\\AuthController@me');
    });
};