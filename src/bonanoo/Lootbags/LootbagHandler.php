<?php
namespace bonanoo\Lootbags;

use pocketmine\console\ConsoleCommandSender;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityDeathEvent;
use pocketmine\event\player\PlayerItemUseEvent;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\enchantment\StringToEnchantmentParser;
use pocketmine\item\StringToItemParser;
use pocketmine\item\VanillaItems;
use pocketmine\nbt\tag\StringTag;
use pocketmine\player\Player;
use pocketmine\item\Item;
use pocketmine\Server;
use pocketmine\utils\TextFormat;

class LootbagHandler{
    private Main $plugin;
    /** @var array<Lootbag> */
    public array $lootbags;
    public static string $LOOTBAG_NAMEDTAG = "ManILikeOtters";

    public function __construct(Main $plugin){
        $this->plugin = $plugin;
        $types = $this->plugin->getLootbagConfig()->getAll()["types"];

        foreach ($types as $type => $lootbag){
            $bag = new Lootbag($lootbag);
            $this->lootbags[strtolower($type)] = $bag;
        }
    }
    public function getLootBag(string $type, $count = 1): Item|string{
        $lowercase = strtolower($type);
        if(!isset($lowercase, $this->lootbags)){
            return TextFormat::RED."This lootbag does not exist.";
        }
        $bag = $this->lootbags[$lowercase];

        $lootbag = VanillaItems::POPPED_CHORUS_FRUIT();
        $lootbag->setCustomName($bag->name);
        $lootbag->setCount($count);
        $nbt = $lootbag->getNamedTag();
        $nbt->setString(self::$LOOTBAG_NAMEDTAG, $lowercase);
        $lootbag->setNamedTag($nbt);
        return $lootbag;
    }

    public function getReward(){

    }
    public function finishOpen($rewards, int $count,  Player $player, Item $lootbagItem){
        $randomItem = array_rand($rewards, $count);
        $data = explode(":", $rewards[$randomItem]);
        if($data[0] === "command"){
            $command = $data[2];
            $command = str_replace("{player}", $player->getName(), $command);
            Main::getInstance()->getServer()->dispatchCommand(new ConsoleCommandSender(Main::getInstance()->getServer(), Main::getInstance()->getServer()->getLanguage()), $command);
            $player->sendMessage(Main::$PREFIX.TextFormat::GRAY." You have received " . $data[1] . " from a ". $lootbagItem->getName() ." lootbag");
        } else {
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
            $inventory = $player->getInventory();
            $player->sendMessage(Main::$PREFIX.TextFormat::GRAY." You have received a " . $item->getName() . " from a ". $lootbagItem->getName() ." lootbag");
            $this->addItem($player, $item);

        }
        $inventory = $player->getInventory();
        $itemInHand = $inventory->getItemInHand();

        if(!$itemInHand->getNamedTag()->getTag(self::$LOOTBAG_NAMEDTAG) instanceof StringTag){
            $player->kick(Main::$PREFIX."There was an issue with opening a lootbag. The item in hand is not a lootbag");
        }
        $itemInHand->setCount($itemInHand->getCount()-1);
        $inventory->setItemInHand($itemInHand);

    }
    public function handleOpen(BlockPlaceEvent | PlayerItemUseEvent $event): void{
        if($event instanceof BlockPlaceEvent || $event instanceof PlayerItemUseEvent) {
            $item = $event->getItem();
            $tag = strtolower($item->getNamedTag()->getString(self::$LOOTBAG_NAMEDTAG));
            if($this->lootbags[$tag] instanceof Lootbag){
                $lootbag = $this->lootbags[$tag];
                $this->finishOpen($lootbag->rewards, $lootbag->reward_count , $event->getPlayer(), $item);
            } else {
                $event->cancel();
            }
        }
    }

    public function addItem(Player $player, Item $item){
        if($player->getInventory()->canAddItem($item)){
            $player->getInventory()->addItem(($item));
        } else {
            $player->getWorld()->dropItem($player->getPosition()->asVector3(), $item);
        }
    }

    public function obtainOnEvent(BlockBreakEvent | EntityDamageEvent $event){
        if($event instanceof EntityDamageByEntityEvent){
            $entity = $event->getEntity();
            $damager = $event->getDamager();
            if($entity instanceof Player) return;
            if(!$damager instanceof Player) return;

            if (($entity->getHealth() - $event->getFinalDamage()) <= 0){
                foreach ($this->lootbags as $key => $lootbag){
                    if(in_array(1, $lootbag->obtainable)){
                        $rand = mt_rand(1, 1000);
                        if($lootbag->chance >= $rand){
                            $damager->sendMessage(Main::$PREFIX.TextFormat::GRAY." You have found a " . $lootbag->name . " lootbag");
                            $this->addItem($damager, Main::getInstance()->getLootbagHandler()->getLootBag($key));
                        }
                    }
                }
            }

        } else {
            if(!$event->isCancelled()){
                $player = $event->getPlayer();
                foreach ($this->lootbags as $key => $lootbag) {
                    if (in_array(1, $lootbag->obtainable)) {
                        $rand = mt_rand(1, 1000);
                        if ($lootbag->chance >= $rand) {
                            $player->sendMessage(Main::$PREFIX . TextFormat::GRAY . " You have found a " . $lootbag->name . " lootbag");
                            $this->addItem($player, Main::getInstance()->getLootbagHandler()->getLootBag($key));
                        }
                    }
                }
            }
        }

    }



}