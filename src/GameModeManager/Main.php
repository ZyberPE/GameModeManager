<?php

namespace GameModeManager;

use pocketmine\plugin\PluginBase;
use pocketmine\player\Player;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\world\GameMode;

class Main extends PluginBase{

    public function onEnable(): void{
        $this->saveDefaultConfig();
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool{

        switch($command->getName()){

            case "gmc":
                return $this->changeGamemode($sender,$args,GameMode::CREATIVE(),"creative","gmm.gmc");

            case "gms":
                return $this->changeGamemode($sender,$args,GameMode::SURVIVAL(),"survival","gmm.gms");

            case "gma":
                return $this->changeGamemode($sender,$args,GameMode::ADVENTURE(),"adventure","gmm.gma");

            case "gmsp":
                return $this->changeGamemode($sender,$args,GameMode::SPECTATOR(),"spectator","gmm.gmsp");
        }

        return false;
    }

    private function changeGamemode(CommandSender $sender, array $args, GameMode $mode, string $type, string $permission): bool{

        $msg = $this->getConfig()->get("messages");

        if(!$sender->hasPermission($permission)){
            $sender->sendMessage($msg["no-permission"]);
            return true;
        }

        // Target player
        if(isset($args[0])){

            $target = $this->getServer()->getPlayerByPrefix($args[0]);

            if(!$target instanceof Player){
                $sender->sendMessage($msg["player-not-found"]);
                return true;
            }

            $target->setGamemode($mode);

            $sender->sendMessage(str_replace("{player}", $target->getName(), $msg[$type . "-other"]));
            $target->sendMessage($msg[$type . "-target"]);

            return true;
        }

        // Self change
        if($sender instanceof Player){

            $sender->setGamemode($mode);
            $sender->sendMessage($msg[$type . "-self"]);

            return true;

        }else{
            $sender->sendMessage("Use: /" . $type . " <player>");
        }

        return true;
    }
}
