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
namespace tueenaLib\loader;

class Loader implements ILoader
{
	/**
	 * @var \Closure[]
	 */
	private $loaders = [];

	public function __construct()
	{
		$loaders = &$this->loaders;
		spl_autoload_register(function ($className) use (&$loaders) {
			foreach ($loaders as $loader) {
				if ($loader($className))
					return;
			}
		});
	}

	/**
	 * Adds an autoload function.
	 *
	 * If you set the namespace to "Foo\Bar" and the path to "/my/path", then
	 * a class "\Foo\Bar\Baz\Qux" would be searched in "/my/path/Bar/Qux.php".
	 *
	 * @param string $namespace Namespace without leading and trailing backslashes.
	 * @param string $path Path without trailing slash.
	 * @return self
	 */
	public function defineNamespaceDirectory(string $namespace, string $path): Loader
	{
		return $this->addLoader(function ($className) use ($namespace, $path) {
			if (strpos($className, $namespace) !== 0)
				return false;

			$localClassNamePart = substr($className, strlen($namespace) + 1);
			$parts = explode('\\', $localClassNamePart);
			$path = $path . '/' . join('/', $parts) . '.php';
			if (!file_exists($path))
				return false;
			require_once $path;
			return true;
		});
	}

	/**
	 * Adds a custom autoload function.
	 *
	 * The closure must expect one parameter: The name of the class, that is
	 * searched. It must return true on success or false if the class file could
	 * not be found by this loader.
	 *
	 * @param \Closure $Loader
	 * @return self
	 */
	public function addLoader(\Closure $loader): Loader
	{
		$this->loaders[] = $loader;
		return $this;
	}
}
