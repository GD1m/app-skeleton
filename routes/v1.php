<?php declare(strict_types=1);

use FastRoute\RouteCollector;

return function (RouteCollector $r) {
    $r->addRoute('GET', '/test', 'TestController@test');
};