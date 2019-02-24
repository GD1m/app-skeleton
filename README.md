# Non-framework PSR-compatible starter kit for PHP applications

Project is not production ready.

Created as a skeleton for performing test tasks without frameworks.

Use it at own risk :)

Backward compatibility may not be supported. Just run ```composer create-project gd1m/non-framework-php-starter-kit``` and make something amazing :)

# Dependencies

- php-di/php-di - [PSR-11](https://www.php-fig.org/psr/psr-11) DI container
- relay/relay - [PSR-15](https://www.php-fig.org/psr/psr-15) request handler
- zendframework/zend-diactoros - [PSR-7](https://www.php-fig.org/psr/psr-7) & [PSR-17](https://www.php-fig.org/psr/psr-17) implementations
- zendframework/zend-httphandlerrunner - [PSR-7](https://www.php-fig.org/psr/psr-7) responses emitter
- monolog/monolog - [PSR-3](https://www.php-fig.org/psr/psr-3) logger
- league/booboo - Error handler
- nikic/fast-route - Regular expression based router
- doctrine/orm - Doctrine 2 ORM
- ramsey/uuid-doctrine - Doctrine adapter for uuid generator
- moontoast/math - Long arithmetic library for uuid generator (for 32bit system)
- doctrine/migrations - Migration system
- rakit/validation - Laravel-friendly data validation
- league/fractal - Presentation and transformation layer for complex data output
- vlucas/phpdotenv - Loads .env variables

You can feel free to remove or replace everything you need.

# Contribution

Pull request are always welcome.