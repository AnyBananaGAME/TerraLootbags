<?php

namespace bonanoo\Lootbags\commands\sub;

use bonanoo\Lootbags\Lootbag;
use bonanoo\Lootbags\Main;
use bonanoo\Lootbags\menu\LootbagMenu;
use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseSubCommand;
use pocketmine\command\CommandSender;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\enchantment\StringToEnchantmentParser;
use pocketmine\item\StringToItemParser;
use pocketmine\item\VanillaItems;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

class ViewSubCommand extends BaseSubCommand{

    public function __construct(){
        parent::__construct(Main::getInstance(),"view", "View  lootbags rewards", ["see"]);
    }

    protected function prepare(): void{
        $this->registerArgument(0, new RawStringArgument("lootbag"));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void{
        if(!$sender instanceof Player) {
            $sender->sendMessage(Main::$PREFIX . TextFormat::RED . " This command may only be executed IN GAME!");
            return;
        }
        $arg = $args["lootbag"];
        if(!Main::getInstance()->getLootbagHandler()->lootbagExists($arg)){
            $sender->sendMessage(Main::$PREFIX.TextFormat::GRAY." This lootbag does not exist " . TextFormat::RED.$arg);
            return;
        } else {
            $lootbag = Main::getInstance()->getLootbagHandler()->getLootbagAsClass($arg);
            $menu = new LootbagMenu($lootbag);
            $menu->send($sender);
        }



    }
}