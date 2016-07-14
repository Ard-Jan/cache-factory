<?php

namespace Cache\Factory\Config\Loader;

use Symfony\Component\Config\Loader\FileLoader;
use Symfony\Component\Yaml\Yaml as YamlParser;

class Yaml extends FileLoader
{
    /**
     * Loads the resource.
     *
     * @param mixed       $resource The resource
     * @param string|null $type     The resource type or null if unknown
     *
     * @return array
     *
     * @throws \Exception If something went wrong
     */
    public function load($resource, $type = null)
    {
        $config = $this->parseYaml($resource);

        return $config;
    }

    /**
     * Parses YAML content from resource
     *
     * @param string $resource Resource filename
     *
     * @return array
     */
    protected function parseYaml($resource)
    {
        return YamlParser::parse($this->loadResourceData($resource));
    }

    /**
     * Loads resource data
     *
     * @param string $resource Resource filename
     *
     * @return string
     */
    protected function loadResourceData($resource)
    {
        return file_get_contents($resource);
    }

    /**
     * States whether this class does support the given resource.
     *
     * @param mixed       $resource A resource
     * @param string|null $type     The resource type or null if unknown
     *
     * @return bool True if this class supports the given resource, false otherwise
     */
    public function supports($resource, $type = null)
    {
        return preg_match('/\.ya?ml$/i', $resource);
    }
}
