<?php

declare(strict_types=1);

namespace matze\mobs;

use matze\mobs\command\ReloadMobConfigCommand;
use matze\mobs\command\TestCommand;
use matze\mobs\command\ToggleMobDebugCommand;
use matze\mobs\entity\animal\Chicken;
use matze\mobs\entity\animal\Cow;
use matze\mobs\entity\animal\Pig;
use matze\mobs\entity\animal\Sheep;
use matze\mobs\entity\Mob;
use matze\mobs\entity\spawning\rule\RequiredBlockTypesSpawnRule;
use matze\mobs\entity\spawning\rule\RequiredLightLevelSpawnRule;
use matze\mobs\entity\spawning\rule\RequiredSpaceSpawnRule;
use matze\mobs\entity\spawning\SpawnPlacements;
use matze\mobs\util\MobsConfig;
use pocketmine\block\VanillaBlocks;
use pocketmine\data\bedrock\item\ItemTypeNames;
use pocketmine\data\bedrock\item\SavedItemData;
use pocketmine\data\bedrock\item\upgrade\LegacyItemIdToStringIdMap;
use pocketmine\entity\Entity;
use pocketmine\entity\EntityDataHelper;
use pocketmine\entity\EntityFactory;
use pocketmine\entity\Location;
use pocketmine\inventory\CreativeCategory;
use pocketmine\inventory\CreativeInventory;
use pocketmine\item\ItemIdentifier as IID;
use pocketmine\item\ItemTypeIds;
use pocketmine\item\SpawnEgg;
use pocketmine\item\StringToItemParser;
use pocketmine\item\VanillaItems;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\world\format\io\GlobalItemDataHandlers;
use pocketmine\world\World;

class Mobs extends PluginBase {
    private static self $instance;

    public static function getInstance(): Mobs{
        return self::$instance;
    }

    protected function onEnable(): void{
        self::$instance = $this;

        new MobsConfig();

        Server::getInstance()->getCommandMap()->register($this->getName(), new ToggleMobDebugCommand());
        Server::getInstance()->getCommandMap()->register($this->getName(), new ReloadMobConfigCommand());

        Server::getInstance()->getPluginManager()->registerEvents(new EventListener(), $this);

        $this->registerMobs();
    }

    protected function registerMobs(): void {
        $factory = EntityFactory::getInstance();

        if(MobsConfig::$registerPigs) {
            $factory->register(Pig::class, function(World $world, CompoundTag $tag): Mob{
                return new Pig(EntityDataHelper::parseLocation($tag, $world), $tag);
            }, ["minecraft:pig", "Pig"]);

            $this->registerSpawnEgg("pig", new class(new IID(ItemTypeIds::newId())) extends SpawnEgg{
                public function createEntity(World $world, Vector3 $pos, float $yaw, float $pitch): Entity{
                    return new Pig(Location::fromObject($pos, $world, $yaw, $pitch));
                }
            });

            SpawnPlacements::register(Pig::class, new RequiredLightLevelSpawnRule(7), new RequiredBlockTypesSpawnRule(VanillaBlocks::GRASS()), new RequiredSpaceSpawnRule());
        }

        if(MobsConfig::$registerCows) {
            $factory->register(Cow::class, function(World $world, CompoundTag $tag): Mob{
                return new Cow(EntityDataHelper::parseLocation($tag, $world), $tag);
            }, ["minecraft:cow", "Cow"]);

            $this->registerSpawnEgg("cow", new class(new IID(ItemTypeIds::newId())) extends SpawnEgg{
                public function createEntity(World $world, Vector3 $pos, float $yaw, float $pitch): Entity{
                    return new Cow(Location::fromObject($pos, $world, $yaw, $pitch));
                }
            });

            SpawnPlacements::register(Cow::class, new RequiredLightLevelSpawnRule(9), new RequiredBlockTypesSpawnRule(VanillaBlocks::GRASS()), new RequiredSpaceSpawnRule());
        }

        if(MobsConfig::$registerChickens) {
            $factory->register(Chicken::class, function(World $world, CompoundTag $tag): Chicken{
                return new Chicken(EntityDataHelper::parseLocation($tag, $world), $tag);
            }, ["minecraft:chicken", "Chicken"]);

            $this->registerSpawnEgg("chicken", new class(new IID(ItemTypeIds::newId())) extends SpawnEgg{
                public function createEntity(World $world, Vector3 $pos, float $yaw, float $pitch): Entity{
                    return new Chicken(Location::fromObject($pos, $world, $yaw, $pitch));
                }
            });

            SpawnPlacements::register(Chicken::class, new RequiredLightLevelSpawnRule(9), new RequiredBlockTypesSpawnRule(VanillaBlocks::GRASS()), new RequiredSpaceSpawnRule());
        }

        if(MobsConfig::$registerSheep) {
            $factory->register(Sheep::class, function(World $world, CompoundTag $tag): Mob{
                return new Sheep(EntityDataHelper::parseLocation($tag, $world), $tag);
            }, ["minecraft:sheep", "Sheep"]);

            $this->registerSpawnEgg("sheep", new class(new IID(ItemTypeIds::newId())) extends SpawnEgg{
                public function createEntity(World $world, Vector3 $pos, float $yaw, float $pitch): Entity{
                    return new Sheep(Location::fromObject($pos, $world, $yaw, $pitch));
                }
            });

            SpawnPlacements::register(Sheep::class, new RequiredLightLevelSpawnRule(7), new RequiredBlockTypesSpawnRule(VanillaBlocks::GRASS()), new RequiredSpaceSpawnRule());
        }
    }

    private function registerSpawnEgg(string $name, SpawnEgg $item): void {
        $id = "minecraft:".$name."_spawn_egg";
        GlobalItemDataHandlers::getDeserializer()->map($id, fn() => $item);
        GlobalItemDataHandlers::getSerializer()->map($item, fn() => new SavedItemData($id));

        StringToItemParser::getInstance()->register($name."_spawn_egg", fn() => clone $item);

        CreativeInventory::getInstance()->add($item, CreativeCategory::NATURE);
    }
}