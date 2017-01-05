<?php

/**
 * tueena lib
 *
 * Copyright (c) Bastian Fenske <bastian.fenske@tueena.org>
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @package loader
 * @author Bastian Fenske <bastian.fenske@tueena.org>
 * @copyright Copyright (c) Bastian Fenske <bastian.fenske@tueena.org>
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 * @link http://tueena.org/
 * @file
 */

spl_autoload_register(function ($className) {

	$namespaceParts = explode('\\', $className);
	$firstNamespacePart = array_shift($namespaceParts);
	$secondNamespacePart = array_shift($namespaceParts);
	if ($firstNamespacePart !== 'tueenaLib' || $secondNamespacePart !== 'loader')
		return false;

	$basePath = $namespaceParts[0] === 'spec' ? __DIR__ : __DIR__ . '/../source';

	$fileName = $basePath . '/' . implode('/', $namespaceParts) . '.php';
	if (!is_readable($fileName))
		return false;
	include $fileName;
	return true;
});
