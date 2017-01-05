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

declare(strict_types=1);
namespace tueenaLib\loader\spec;

use tueenaLib\loader\ILoader;
use tueenaLib\loader\Loader;

class LoaderTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @test
	 */
	public function The_Loader_class_implements_the_ILoader_interface()
	{
		$this->assertContains(ILoader::class, class_implements(Loader::class));
	}

	/**
	 * @test
	 */
	public function A_directory_can_be_defined_where_all_classes_of_a_namespace_are_found()
	{
		// given
		$loader = new Loader;
		$this->assertFalse(class_exists('tueenaLib\\loader\\spec\\something\\foo\\Bar'));

		// when
		$loader->defineNamespaceDirectory('tueenaLib\\loader\\spec\\something', __DIR__ . '/../stubs/loader-test');

		// then
		$this->assertTrue(class_exists('tueenaLib\\loader\\spec\\something\\foo\\Bar'));
	}

	/**
	 * @test
	 */
	public function A_closure_can_be_defined_as_loader()
	{
		// given
		$closureCallLog = [];
		$loader = new Loader;

		// when
		$loader->addLoader(function (string $className) use (&$closureCallLog): bool {
			$closureCallLog[] = $className;
			return true;
		});
		class_exists('tueenaLib\\loader\\spec\\NotExistingClass');

		// then
		$this->assertEquals(['tueenaLib\\loader\\spec\\NotExistingClass'], $closureCallLog);
	}

	/**
	 * @test
	 */
	public function Loaders_are_called_in_the_order_of_definition_until_a_class_has_been_loaded()
	{
		// given
		$log = [];
		$loader = new Loader;

		// when
		$loader
			->addLoader(function (string $className) use (&$log): bool {
				$log[] = 'First';
				return false;
			})
			->addLoader(function (string $className) use (&$log): bool {
				$log[] = 'Second';
				return false;
			})
			->addLoader(function (string $className) use (&$log): bool {
				$log[] = 'Third';
				return true; // <- this should stop any further loader calls.
			})
			->addLoader(function (string $className) use (&$log): bool {
				$log[] = 'Fourth';
				return false;
			});
		class_exists('tueenaLib\\loader\\spec\\NotExistingClass');

		// then
		$this->assertEquals(['First', 'Second', 'Third'], $log);
	}

	/**
	 * @test
	 */
	public function The_two_public_methods_return_an_instance_of_the_loader()
	{
		// given
		$loader = new Loader;

		// when
		$returnValue1 = $loader->defineNamespaceDirectory('x', 'y');
		$returnValue2 = $loader->addLoader(function () {});

		// then
		$this->assertSame($loader, $returnValue1);
		$this->assertSame($loader, $returnValue2);
	}
}
