<?php

namespace bonanoo\Lootbags\commands\sub;

use bonanoo\Lootbags\Main;
use CortexPE\Commando\args\IntegerArgument;
use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\args\TargetPlayerArgument;
use CortexPE\Commando\BaseSubCommand;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;

class GiveSubCommand extends BaseSubCommand {
    public function __construct(){
        parent::__construct(Main::getInstance(), "give" , "Give player a lootbag" , ["add"]);
    }

    public function prepare(): void{
        $this->setPermissions(["lootbags.command.give"]);
        $this->registerArgument(0, new TargetPlayerArgument(false, "player"));
        $this->registerArgument(1, new RawStringArgument("lootbag", false));
        $this->registerArgument(2, new IntegerArgument("count", false));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void{
        $player = Main::getInstance()->getServer()->getPlayerExact($args["player"]);
        if(!$player instanceof  Player){
            $sender->sendMessage(Main::$PREFIX . TextFormat::RED . " This player is not ingame");
            return;
        }

        $inventory = $player->getInventory();

        $lootbag = Main::getInstance()->getLootbagHandler()->getLootBag($args["lootbag"], $args["count"]);

        if(is_string($lootbag)){
            $sender->sendMessage(Main::$PREFIX.$lootbag);
            return;
        }

        $sender->sendMessage("You have given {$player->getName()} a x".$args["count"].$lootbag->getName()."lootbag");
        $player->sendMessage(Main::$PREFIX.TextFormat::GRAY." You have been gived  x" . $args["count"] . $lootbag->getName(). " lootbag(s).");
        if($inventory->canAddItem($lootbag)){
            $inventory->addItem($lootbag);
        } else {
            $player->getWorld()->dropItem($player->getPosition()->asVector3(), $lootbag);
        }

    }
}