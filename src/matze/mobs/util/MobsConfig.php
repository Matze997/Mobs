<?php

declare(strict_types=1);

namespace matze\mobs\util;

use matze\mobs\Mobs;
use pocketmine\math\Vector3;
use pocketmine\plugin\Plugin;
use pocketmine\utils\Config;
use pocketmine\utils\VersionString;
use ReflectionClass;
use ReflectionProperty;

class MobsConfig {
    public static bool $debug = false;

    public function __construct(){
        $plugin = Mobs::getInstance();
        $logger = $plugin->getLogger();
        $version = $plugin->getDescription()->getVersion();
        $pluginVersion = new VersionString($version);
        $reflection = new ReflectionClass($this);
        $properties = $reflection->getProperties(ReflectionProperty::IS_PUBLIC | ReflectionProperty::IS_STATIC);
        $filePath = $plugin->getDataFolder()."mobs.properties";
        $update = false;
        if(is_file($filePath)) {
            $config = new Config($filePath);
            if(!$config->exists("version") || $pluginVersion->compare(new VersionString($config->get("version")), true) > 0) {
                $update = true;
            }
        } else {
            $update = true;
        }

        //Auto Updater
        if($update) {
            $logger->warning("Plugin config is not up-to-date. Config will be updated to V".$version);

            if(is_file($filePath)) {
                rename($filePath, $filePath."_old");
            }

            $content = "#Mob Config file\r\n";
            $this->writeLine($content, "version", $version);
            foreach($properties as $property) {
                if(!$property->isPublic() || !$property->isStatic()) {
                    continue;
                }
                $comment = $property->getDocComment();
                if($comment !== false) {
                    foreach(explode("\n", $comment) as $str) {
                        $str = trim(str_replace(["/**", "*/", "* "], "", $str));
                        if(strlen($str) <= 0) {
                            continue;
                        }
                        $this->writeLine($content, $str);
                    }
                }
                $this->writeLine($content, $property->getName(), $property->getValue());
            }
            file_put_contents($filePath, $content);
            $config = new Config($filePath);
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

    private function writeLine(string &$content, string $keyOrComment, mixed $value = null): void {
        if($value === null) {
            if(str_starts_with($keyOrComment, "#")) {
                $content .= "\r\n";
            }
            $keyOrComment = str_replace("#", "", $keyOrComment);
            if(!empty($keyOrComment)) {
                $content .= "# " . str_replace("#", "", $keyOrComment) . "\r\n";
            }
        } else {
            if(is_bool($value)){
                $value = $value ? "yes" : "no";
            }
            $content .= $keyOrComment . "=" . $value . "\r\n";
        }
    }



    /**
     * #Pathfinder settings
     * Choose how many iterations the pathfinder makes until it stops (Higher value => More CPU usage)
     */
    public static int $maxPathfinderIterations = 24;
    /**
     * Choose how many random positions are generated and checked until the system stops (Higher value => More "real" positions, but higher CPU usage)
     */
    public static int $maxRandomPositionGeneratorIterations = 8;


    /**
     * #Animal settings
     */
    public static bool $animalsDropXpWhenBred = true;
    /**
     * Choose how animals shall despawn:
     * 0: Never
     * 1: Only despawn if the mob has been interacted with (Damage, Tamed, Tempt,...)
     * 2: Always
     */
    public static int $animalDespawnBehavior = 0;

    /**
     * #Pig settings
     */
    public static bool $registerPigs = true;
    /**
     * Animals beyond that distance will despawn immediately
     */
    public static int $pigDespawnDistance = 128;
    /**
     * Animals beyond that distance can despawn randomly
     */
    public static int $pigNoDespawnDistance = 32;

    /**
     * #Cow settings
     */
    public static bool $registerCows = true;
    public static int $cowDespawnDistance = 128;
    public static int $cowNoDespawnDistance = 32;

    /**
     * #Chicken settings
     */
    public static bool $registerChickens = true;
    public static int $chickenDespawnDistance = 128;
    public static int $chickenNoDespawnDistance = 32;

    /**
     * #Sheep settings
     */
    public static bool $registerSheep = true;
    public static int $sheepDespawnDistance = 128;
    public static int $sheepNoDespawnDistance = 32;
    public static bool $sheepCanDestroyGrass = true;
}