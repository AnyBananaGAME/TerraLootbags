<?php
namespace bonanoo\Lootbags;

use bonanoo\Lootbags\commands\LootbagCommand;
use CortexPE\Commando\PacketHooker;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;

class Main extends PluginBase {
    private static Main $instance;
    public LootbagHandler $lootBagH;
    public Config $lootbags;
    public static string $PREFIX = TextFormat::GRAY."[".TextFormat::BOLD.TextFormat::BLUE."Loot".TextFormat::LIGHT_PURPLE."Bags".TextFormat::RESET.TextFormat::GRAY."]";

    public function onLoad(): void{
        self::$instance = $this;
        if(!is_dir($dataFolder = $this->getDataFolder())) {
            mkdir($dataFolder);
        }
        $this->saveResource("lootbags.yml");
    }

    public function onEnable(): void{
        $this->lootbags = new Config($this->getDataFolder()."lootbags.yml", Config::YAML);
        $this->lootBagH = new LootbagHandler($this);
        if(!PacketHooker::isRegistered()) {
            PacketHooker::register($this);
        }

        $this->getServer()->getCommandMap()->register("lootbags", new LootbagCommand());
        $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
    }

    public static function getInstance(): Main{
        return self::$instance;
    }

    public function getLootbagHandler(): LootbagHandler{
        return $this->lootBagH;
    }

    public function getLootbagConfig(): Config{
        return $this->lootbags;
    }
}