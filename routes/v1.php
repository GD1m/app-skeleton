<?php declare(strict_types=1);

use App\Kernel\Utils\String\Regex;
use FastRoute\RouteCollector;

return function (RouteCollector $r) {
    $r->addGroup('/app/v1', function (RouteCollector $r) {
        $r->post('/users', 'AuthController@register');
        $r->post('/users/login', 'AuthController@login');
        $r->delete('/users/login', 'AuthController@logout');
        $r->get('/users/me', 'AuthController@me');

        $r->post('/todos', 'TodoController@create');
        $r->get('/todos', 'TodoController@getTodoLists');
        $r->get(sprintf('/todos/{uuid:%s}', Regex::uuid4()), 'TodoController@getTodoList');
        $r->patch(sprintf('/todos/{uuid:%s}', Regex::uuid4()), 'TodoController@update');
        $r->delete(sprintf('/todos/{uuid:%s}', Regex::uuid4()), 'TodoController@delete');
        $r->delete(
            sprintf('/todos/{uuid:%s}/actions/completed', Regex::uuid4()),
            'TodoController@deleteCompletedActions'
        );

        $r->post(sprintf('/todos/{uuid:%s}/actions', Regex::uuid4()), 'ActionController@create');
        $r->patch(sprintf('/todos/actions/{uuid:%s}', Regex::uuid4()), 'ActionController@update');
        $r->delete(sprintf('/todos/actions/{uuid:%s}', Regex::uuid4()), 'ActionController@delete');
    });
};