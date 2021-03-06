<?php

namespace BetterEmbed\WordPress;

use BetterEmbed\WordPress\Exception\UnmetRequirement;
use BetterEmbed\WordPress\Service\Service;

class Plugin
{

    protected $pluginFile;
    protected $pluginPath;
    protected $namespace;

    public function __construct( string $pluginFile, string $namespace) {
        $this->pluginFile = $pluginFile;
        $this->pluginPath = plugin_dir_path($pluginFile);
        $this->namespace  = $namespace;
    }

    /**
     * @param Service[] $services
     */
    public function init( array $services) {

        try {
            $this->checkRequirements();
        } catch (UnmetRequirement $exception) {
            if ($this->betterEmbedDebugEnabled()) {
                throw $exception;
            }
            return;
        }

        foreach ($services as $service) {
            if ($service instanceof Service) {
                $service->init($this);
            }
        }
    }

    /**
     * A string in the format `{plugin_namespace}_{name}` for use with e.g. asset handles.
     *
     * @param string $name
     *
     * @return string
     */
    public function prefix( string $name = ''): string {
        return empty($name) ? $this->namespace : $this->namespace . '_' . esc_attr(strtolower($name));
    }

    /**
     * A string in the format `{plugin_namespace}/{name}` for use with e.g. block namespaces.
     *
     * @param string $name
     *
     * @return string
     */
    public function namespace( string $name = ''): string {
        return empty($name) ? $this->namespace : $this->namespace . '/' . esc_attr(strtolower($name));
    }

    /**
     * Path to main plugin file.
     *
     * @return string
     */
    public function pluginFile(): string {
        return $this->pluginFile;
    }

    /**
     * Path to plugin folder.
     *
     * @return string
     */
    public function pluginPath(): string {
        return $this->pluginPath;
    }

    /**
     * Debug flag that enables some development-only things like enabling the UI for Custom Post Types.
     *
     * @return bool
     */
    public function betterEmbedDebugEnabled(): bool {
        return ( defined('BETTEREMBED_DEBUG') && BETTEREMBED_DEBUG );
    }

    protected function checkRequirements() {
        // Abort if PHP < 7.2.0
        $requiredVersionPHP = '7.2.0';
        if (version_compare(phpversion(), $requiredVersionPHP, '<')) {
            throw UnmetRequirement::fromApiException('PHP', $requiredVersionPHP, phpversion());
        }

        // Abort if WordPress Version < 5.4
        $requiredVersionWordPress = '5.4.0';
        if (version_compare($GLOBALS['wp_version'], $requiredVersionWordPress, '<')) {
            throw UnmetRequirement::fromApiException('WordPress', $requiredVersionWordPress, $GLOBALS['wp_version']);
        }
    }
}
