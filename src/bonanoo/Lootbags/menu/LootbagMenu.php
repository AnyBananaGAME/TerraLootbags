<?php

namespace bonanoo\Lootbags\menu;

use bonanoo\Lootbags\Lootbag;
use muqsit\invmenu\InvMenu;
use muqsit\invmenu\transaction\InvMenuTransaction;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\enchantment\StringToEnchantmentParser;
use pocketmine\item\StringToItemParser;
use pocketmine\item\VanillaItems;
use pocketmine\player\Player;

class LootbagMenu  {
    public InvMenu $menu;
    private Lootbag $lootbag;

    public function __construct(Lootbag $lootbag){
        $this->lootbag = $lootbag;
        $this->menu = InvMenu::create(InvMenu::TYPE_DOUBLE_CHEST);
        $this->prepare();
        $this->menu->setListener(InvMenu::readonly(\Closure::fromCallable([$this, "handle"])));
    }
    public function send(Player $player){
        $this->menu->send($player);
    }

    public function prepare(){
        $inventory = $this->menu->getInventory();
        foreach ($this->lootbag->rewards as $key => $reward){
            $data = explode(":", $reward);
            if($data[0] !== "command"){
                $item = StringToItemParser::getInstance()->parse($data[0]);
                if ($item === null) {
                    $item = VanillaItems::AIR();
                }
                $item->setCount($data[1]);

                if(strtolower($data[2]) !== "false"){
                    $item->setCustomName($data[2]);
                }

                $enchantment = null;

                if(isset($data[3])){
                    for ($i = 3; $i < count($data); $i += 2) {
                        $enchantment = StringToEnchantmentParser::getInstance()->parse((string) $data[$i]);
                        if ($enchantment !== null && isset($data[$i + 1])) {
                            $item->addEnchantment(new EnchantmentInstance($enchantment, (int) $data[$i + 1]));
                        }
                    }
                }
                $inventory->addItem($item);
            } else {
                $item = VanillaItems::PAPER();
                $item->setCount(1);
                $item->setCustomName($data[1]);
                $inventory->addItem($item);
            }
        }

    }

    public function handle(InvMenuTransaction $transaction): void{}

}