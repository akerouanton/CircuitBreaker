# `nir/circuit-breaker`

[![Build Status](https://travis-ci.org/NiR-/CircuitBreaker.svg?branch=master)](https://travis-ci.org/NiR-/CircuitBreaker)
[![StyleCI](https://styleci.io/repos/108280770/shield?branch=master)](https://styleci.io/repos/108280770)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/NiR-/CircuitBreaker/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/NiR-/CircuitBreaker/?branch=master)

This package is inspired by [`ejsmont-artur/php-circuit-breaker`](https://github.com/ejsmont-artur/php-circuit-breaker).

## Install

`composer require nir/circuit-breaker`

## How to use?

You need to write (or generate) decorator classes to wrap objects on which you want to apply the circuit breaker.

## How it works?

You can check [this proposal](http://artur.ejsmont.org/blog/circuit-breaker) for Zend framework if you want to know how 
it works.

## Contribute

Here are some useful composer scripts you might want to use to ease development:

```bash
composer run docker:build   # It's automatically run by other scripts
composer run docker:install # Run composer install in a container
composer run docker:tests   # Run phpspec and phpunit in a container
composer run tests          # Run phpspec and phpunit
$(composer run docker:run)  # Run an interactive shell in a new container (composer run docker:build should be called first)
```
