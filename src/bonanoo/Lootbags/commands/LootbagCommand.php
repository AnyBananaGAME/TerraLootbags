<?php

namespace bonanoo\Lootbags\commands;

use bonanoo\Lootbags\commands\sub\GiveAllSubCommand;
use bonanoo\Lootbags\commands\sub\GiveSubCommand;
use bonanoo\Lootbags\Main;
use CortexPE\Commando\BaseCommand;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\lang\Translatable;
use pocketmine\player\Player;
use pocketmine\plugin\Plugin;
use pocketmine\utils\TextFormat;

class LootbagCommand extends BaseCommand {
    public function __construct(){
        parent::__construct(Main::getInstance(), "lootbags", "Lootbag Command", ["lootbag", 'lb']);
    }

    public function prepare(): void{
        $this->setPermissions(["lootbags.command.lootbags"]);
        $this->setDescription("Lootbags command");
        $this->registerSubCommand(new GiveSubCommand());
        $this->registerSubCommand(new GiveAllSubCommand());

    }

   public function onRun(CommandSender $sender, string $aliasUsed, array $args): void{
       if(!$sender instanceof Player){
           $sender->sendMessage(Main::$PREFIX.TextFormat::RED." You may not execute this command outside IN-GAME");
           return;
       }
       $sender->sendMessage(Main::$PREFIX.TextFormat::RED." Available SubCommands:\n   - view\n   - give\n   - giveall");
   }


}