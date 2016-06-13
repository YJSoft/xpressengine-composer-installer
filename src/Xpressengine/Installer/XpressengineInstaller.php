<?php
/**
 * This file is XpressEngine 3rd party plugin installer.
 *
 * PHP version 5
 *
 * @category    Installer
 * @package     Xpressengine\Installer
 * @author      XE Team (jhyeon1010) <cjh1010@xpressengine.com>
 * @copyright   2014 Copyright (C) NAVER <http://www.navercorp.com>
 * @license     http://www.gnu.org/licenses/lgpl-3.0-standalone.html LGPL
 * @link        http://www.xpressengine.com
 */
namespace Xpressengine\Installer;

use Composer\Composer;
use Composer\Installer\BinaryInstaller;
use Composer\Installer\LibraryInstaller;
use Composer\IO\IOInterface;
use Composer\Package\PackageInterface;
use Composer\Repository\InstalledRepositoryInterface;
use Composer\Util\Filesystem;

/**
 * This class is extend composer installer for XpressEngine plugins.
 *
 * @category    Installer
 * @package     Xpressengine\Installer
 * @author      XE Team (jhyeon1010) <cjh1010@xpressengine.com>
 * @copyright   2014 Copyright (C) NAVER <http://www.navercorp.com>
 * @license     http://www.gnu.org/licenses/lgpl-3.0-standalone.html LGPL
 * @link        http://www.xpressengine.com
 */
class XpressengineInstaller extends LibraryInstaller
{

    public static $changed = [];

    /**
     * Initializes library installer.
     *
     * @param IOInterface $io
     * @param Composer $composer
     * @param string $type
     * @param Filesystem $filesystem
     * @param BinaryInstaller $binaryInstaller
     */
    public function __construct(IOInterface $io, Composer $composer, $type = 'library', Filesystem $filesystem = null, BinaryInstaller $binaryInstaller = null)
    {
        static::$changed = [
            'installed' => [],
            'updated' => [],
            'uninstalled' => [],
        ];

        parent::__construct($io, $composer, $type, $filesystem, $binaryInstaller);
    }

    /**
     * Directory in which the plugin is installed
     *
     * @param PackageInterface $package 3rd party plugin package instance
     * @return string
     */
    public function getInstallPath(PackageInterface $package)
    {

        list(, $packageName) = explode('/', $package->getPrettyName());

        return 'plugins/' . $packageName;
    }

    /**
     * Decides if the installer supports the given type
     *
     * Check XpressEngine plugin type
     *
     * @param string $packageType type of package
     * @return bool
     */
    public function supports($packageType)
    {
        return 'xpressengine-plugin' === $packageType;
    }

    public function install(InstalledRepositoryInterface $repo, PackageInterface $package)
    {
        parent::install($repo, $package);
        static::$changed['installed'][$package->getName()] = $package->getPrettyVersion();
    }

    public function update(InstalledRepositoryInterface $repo, PackageInterface $initial, PackageInterface $target)
    {
        parent::update($repo, $initial, $target);
        static::$changed['updated'][$target->getName()] = $target->getPrettyVersion();
    }

    public function uninstall(InstalledRepositoryInterface $repo, PackageInterface $package)
    {
        parent::uninstall($repo, $package);
        static::$changed['uninstalled'][$package->getName()] = $package->getPrettyVersion();
    }


}
