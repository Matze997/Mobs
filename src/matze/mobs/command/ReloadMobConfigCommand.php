<?php

namespace matze\mobs\command;

use matze\mobs\util\MobsConfig;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class ReloadMobConfigCommand extends Command{
    public function __construct(){
        parent::__construct("reloadmobconfig", "Reload mob config");
        $this->setPermission("command.reloadmobconfig.use");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): void{
        if(!$this->testPermission($sender)) {
            return;
        }
        new MobsConfig();
        $sender->sendMessage("Successfully reloaded config.");
    }
}