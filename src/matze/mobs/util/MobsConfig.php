<?php

declare(strict_types=1);

namespace matze\mobs\util;

use matze\mobs\Mobs;
use pocketmine\plugin\Plugin;
use pocketmine\utils\VersionString;
use ReflectionClass;
use ReflectionProperty;

class MobsConfig {
    public static bool $DEBUG = false;

    public function __construct(){
        $plugin = Mobs::getInstance();

        $reflection = new ReflectionClass($this);
        $properties = $reflection->getProperties(ReflectionProperty::IS_PUBLIC | ReflectionProperty::IS_STATIC);
        $config = $plugin->getConfig();
        $path = $config->getPath();
        $logger = $plugin->getLogger();


        //Auto Updater
        $version = $plugin->getDescription()->getVersion();
        $pluginVersion = new VersionString($version);
        if(!$config->exists("version") || $pluginVersion->compare(new VersionString($config->get("version")), true) > 0) {
            $logger->warning("Plugin config is not up-to-date. Config will be updated to V".$version);

            rename($path, $path."_old");

            $config->setAll([]);
            foreach($properties as $property) {
                if(!$property->isPublic() || !$property->isStatic()) {
                    continue;
                }
                $config->set($property->getName(), $property->getValue());
            }
            $config->save();
        }

        //Load data from config
        foreach($properties as $property) {
            if(!$property->isPublic() || !$property->isStatic()) {
                continue;
            }
            $propertyName = $property->getName();
            $this::${$propertyName} = $config->get($propertyName);
        }

        $logger->debug("Successfully loaded config!");
    }

    public static int $maxPathfinderIterations = 24;
    public static int $maxRandomPositionGeneratorIterations = 8;

    public static bool $registerPigs = true;
    public static int $pigDespawnDistance = 128;
    public static int $pigNoDespawnDistance = 32;

    public static bool $registerCows = true;
    public static int $cowDespawnDistance = 128;
    public static int $cowNoDespawnDistance = 32;

    public static bool $registerChickens = true;
    public static int $chickenDespawnDistance = 128;
    public static int $chickenNoDespawnDistance = 32;

    public static bool $registerSheep = true;
    public static int $sheepDespawnDistance = 128;
    public static int $sheepNoDespawnDistance = 32;
    public static bool $sheepCanDestroyGrass = true;
}