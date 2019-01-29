<?php declare(strict_types=1);

use FastRoute\RouteCollector;

return function (RouteCollector $r) {
    $r->addGroup('/app/v1', function (RouteCollector $r) {
        $r->post('/users', 'AuthController@register');
        $r->post('/users/login', 'AuthController@login');
        $r->get('/users/me', 'AuthController@me');

        $r->post('/todos', 'TodoController@create');
    });
};