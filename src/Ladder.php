<?php

/*
 * This file is part of Climb.
 *
 * (c) Vincent Klaiber <hello@vinkla.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vinkla\Climb;

/**
 * This is the ladder class.
 *
 * @author Vincent Klaiber <hello@vinkla.com>
 * @author Jens Segers <hello@jenssegers.com>
 */
class Ladder
{
    /**
     * The composer instance.
     *
     * @var string
     */
    protected $composer;

    /**
     * Create a new ladder instance.
     *
     * @param string|null $directory
     *
     * @return void
     */
    public function __construct($directory = null)
    {
        $this->composer = new Composer($directory ?: getcwd());
    }

    /**
     * Get outdated packages with their current and latest version.
     *
     * @throws \Vinkla\Climb\ClimbException
     *
     * @return array
     */
    public function getOutdatedPackages()
    {
        // Get all installed and required packages.
        $installed = $this->composer->getInstalledPackages();
        $required = $this->composer->getRequiredPackages();

        $outdated = [];

        // Get the installed version number of the required packages.
        $packages = array_intersect_key($installed, $required);

        foreach ($packages as $name => $version) {
            $package = new Package($name, Version::normalize($version), $version);

            if ($package->isOutdated()) {
                $package->setConstraint($required[$name]);

                $outdated[] = $package;
            }
        }

        return $outdated;
    }
}
