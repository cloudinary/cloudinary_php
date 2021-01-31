# Developing Cloudinary PHP

## Code style

### Recommended Standards
All code should follow the following standards:
- [PSR-1](http://www.php-fig.org/psr/psr-1/)
- [PSR-2](http://www.php-fig.org/psr/psr-2/)
- [PSR-3 _Recommended_](http://www.php-fig.org/psr/psr-3/)
- [PSR-5 _Recommended_](https://github.com/php-fig/fig-standards/blob/master/proposed/phpdoc.md)
- [PSR-12](https://www.php-fig.org/psr/psr-12/)

### PHP Code Size Control
All code should meet default configuration of [PHPMD](https://phpmd.org/rules/codesize.html)

## Tests Coverage
All code must be covered by tests using [PHPUnit](https://phpunit.de/manual/current/en/writing-tests-for-phpunit.html)
For functional tests unique IDs should be used and after test is done all data from remote server should be removed.
