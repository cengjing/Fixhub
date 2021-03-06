<?php

/*
 * This file is part of Fixhub.
 *
 * Copyright (C) 2016 Fixhub.org
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fixhub\Services\Settings;

use Exception;
use Illuminate\Filesystem\Filesystem;

/**
 * This is the settings cache class.
 *
 */
class Cache
{
    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * Is path to the setting cache.
     *
     * @var string
     */
    protected $path;

    /**
     * Create a new settings cache instance.
     *
     * @param \Illuminate\Filesystem\Filesystem $files
     * @param string                            $path
     *
     * @return void
     */
    public function __construct(Filesystem $files, $path)
    {
        $this->files = $files;
        $this->path = $path;
    }

    /**
     * Store the settings in the cache.
     *
     * @param string $env
     * @param array  $data
     *
     * @return void
     */
    public function store($env, array $data)
    {
        $this->files->put($this->path($env), '<?php return '.var_export($data, true).';'.PHP_EOL);
    }

    /**
     * Load the settings from the cache.
     *
     * @param string $env
     *
     * @return array|false
     */
    public function load($env)
    {
        try {
            return $this->files->getRequire($this->path($env));
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Clear the settings cache.
     *
     * Note that we're careful not to remove the .gitignore file.
     *
     * @return void
     */
    public function clear()
    {
        $this->files->delete($this->files->allFiles($this->path));
    }

    /**
     * Returns the settings cache path.
     *
     * @return string
     */
    protected function path($env)
    {
        return "{$this->path}/{$env}.php";
    }
}
