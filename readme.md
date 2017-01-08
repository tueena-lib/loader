tueena-lib/loader
=================
A tiny service to configure an autoloader for php 7 applications.

Features
--------
* Test driven developed. Code coverage 100%.
* Provides a method to assign a directory to a namespace.
* Provides a method to register a closure, that will be called to load a class.

Usage
-----
```php
<?php

// Create an instance of the Loader class.
$loader = new Loader;

// Define a directory, where classes of a namespace are found.
// Namespaces are defined without leading and trailing backslashes.
// Directories without trailing slashes.
$loader->addNamespaceDirectory('my\\namespace', '/path/to/my/class/files');

// Or define a closure as loader. This closure will be called with the
// class name as parameter and must return true if the class could be
// loaded and false otherwise.
$loader->addLoader(function ($className) {
	if (!$className === 'my\\special\\class')
		return false;
	include '/path/to/my/special/class.php';
	return true;
});

// Method calls can be chained.
$loader
  ->addNamespaceDirectory(/* ... */)
  ->addNamespaceDirectory(/* ... */)
  ->addLoader(/* ... */)
  ->addNamespaceDirectory(/* ... */);

// Loaders are called in the order of definition.
// The Loader class implements the ILoader interface.
```

License
-------
MIT

Requirements
------------
php >= 7.0.0

Installation
------------
If you use `Composer`:
```
composer require tueena-lib/loader
```
Otherwise just download the class file and use it.

Contact
-------
Bastian Fenske <bastian.fenske@tueena.org>
