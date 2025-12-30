<?php

namespace matze\mobs\command;

use matze\mobs\entity\Mob;
use matze\mobs\util\MobsConfig;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Server;

class ToggleMobDebugCommand extends Command{
    public function __construct(){
        parent::__construct("togglemobdebug", "Toggle mob debug");
        $this->setPermission("command.togglemobdebug.use");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): void{
        if(!$this->testPermission($sender)) {
            return;
        }
        MobsConfig::$DEBUG = !MobsConfig::$DEBUG;
        if(MobsConfig::$DEBUG) {
            $sender->sendMessage("Mob debug is now enabled.");
        } else {
            $sender->sendMessage("Mob debug is now disabled.");
        }

        foreach(Server::getInstance()->getWorldManager()->getWorlds() as $world) {
            foreach($world->getEntities() as $entity) {
                if($entity instanceof Mob) {
                    $entity->setNameTag("");
                    $entity->setNameTagVisible(MobsConfig::$DEBUG);
                    $entity->setNameTagAlwaysVisible(MobsConfig::$DEBUG);
                }
            }
        }
    }
}