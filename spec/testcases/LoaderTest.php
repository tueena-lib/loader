<?php

/**
 * Part of the tueena lib
 *
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 * @link http://tueena.org/
 * @author Bastian Fenske <bastian.fenske@tueena.org>
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
		$target = new Loader;
		$this->assertFalse(class_exists('tueenaLib\\loader\\spec\\something\\foo\\Bar'));

		// when
		$target->defineNamespaceDirectory('tueenaLib\\loader\\spec\\something', __DIR__ . '/../stubs/loader-test');

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
		$target = new Loader;

		// when
		$target->addLoader(function (string $className) use (&$closureCallLog): bool {
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
		$target = new Loader;

		// when
		$target
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
		$target = new Loader;

		// when
		$returnValue1 = $target->defineNamespaceDirectory('x', 'y');
		$returnValue2 = $target->addLoader(function () {});

		// then
		$this->assertSame($target, $returnValue1);
		$this->assertSame($target, $returnValue2);
	}
}
