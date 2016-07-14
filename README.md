# Cache Factory

[![Author](http://img.shields.io/badge/author-@dgreda-blue.svg?style=flat-square)](https://www.linkedin.com/in/damiangreda)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Build Status](https://travis-ci.org/dgreda/cache-factory.svg?branch=master)](https://travis-ci.org/dgreda/cache-factory)
[![codecov](https://codecov.io/gh/dgreda/cache-factory/branch/master/graph/badge.svg)](https://codecov.io/gh/dgreda/cache-factory)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/833af7a6-7eee-471b-a5a8-ee3861113d9f/mini.png)](https://insight.sensiolabs.com/projects/833af7a6-7eee-471b-a5a8-ee3861113d9f)

# Description

Simple Factory for php-cache/cache library (PSR-6 Cache implementation).

# Goal

To offer a simple way of creating different cache pool adapters based on simplified configurations.

# Usage

The factory creates and returns a PSR-6 compatible cache pool based on small and tidy configuration provided as either PHP array or via YAML configuration file.

## Installation

Library is available on Packagist, so you can simply run:

```
composer require "dgreda/cache-factory":"v0.1.*"
```

## Adapters

Currently factory supports the following adapters:

* Filesystem
* Memcached
* Predis

The goal is to implement all the adapters implemented by https://github.com/php-cache/cache

## Examples

There are some examples of usage under the "examples" directory.

Nevertheless, to give you a quick explanation and idea how it works, let's assume that you want to quickly obtain a Memcached cache pool implementation.
You prepare the following YAML file and save it as 'cache.yml'

```
Cache:
  adapter:
    memcached:
      type: Memcached
      servers:
        memcached1: { host: localhost, port: 11211 }
```

Then in your PHP code you instantiate the factory and provide the config file to it, to easily make an instance of desired cache pool defined in the YAML:

```
use Cache\Factory\Factory;

...

$cachePoolFactory = new Factory();

$cachePoolFactory->setConfigFile('cache.yml');

$cachePool = $cachePoolFactory->make('memcached');
```

Now you have the PSR-6 compatible instance of cache pool using the desired cache adapter!

Your YAML file can contain several adapters defined and you can easily get a new instance of desired/needed cache pool by simply passing the name of the adapter as a parameter to the 'make' method of the factory.

# Credits

Inspiration to create this library came from the flysystem-factory where I am also a contributor: https://github.com/Westwing-Home-and-Living/flysystem-factory

The flysystem-factory library development was initiated by https://github.com/titosemi
