<?php

namespace bonanoo\Lootbags;

use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerItemUseEvent;
use pocketmine\nbt\tag\StringTag;

class EventListener implements Listener {

        public function onBreak(BlockBreakEvent $event): void{
            if(!$event->isCancelled()){
                $in = Main::getInstance()->getLootbagHandler();
                $in->obtainOnEvent($event);
            }
        }

        public function onEntityAttack(EntityDamageEvent $event){
            if(!$event->isCancelled()){
                $in = Main::getInstance()->getLootbagHandler();
                $in->obtainOnEvent($event);
            }
        }

        public function onInteract(PlayerItemUseEvent $event): void {
            if($event->getItem()->hasNamedTag()){
                if($event->getItem()->getNamedTag()->getTag(LootbagHandler::$LOOTBAG_NAMEDTAG) instanceof StringTag){
                    Main::getInstance()->getLootbagHandler()->handleOpen($event);
                }
            }
        }


        public function onTap(BlockPlaceEvent $event): void{
            if($event->getItem()->hasNamedTag()){
                if($event->getItem()->getNamedTag()->getTag(LootbagHandler::$LOOTBAG_NAMEDTAG) instanceof StringTag){
                    Main::getInstance()->getLootbagHandler()->handleOpen($event);
                }
            }
        }

}