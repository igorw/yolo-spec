# YOLO spec

Specification for how a microframework should behave.

This repository contains a suite of tests that describe the expectations
towards a simple web framework based on the HttpKernelInterface.

Currently they run against:

* [silex](github.com/fabpot/Silex)
* [yolo](https://github.com/igorw/yolo)

## Run the tests

    $ composer install --dev
    $ phpunit
