tueenaLib/loader
================
A tiny php service to configure an autoloader.

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

Contact
-------
Bastian Fenske <bastian.fenske@tueena.org>
