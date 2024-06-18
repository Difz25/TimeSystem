<?php

namespace Difz25\TimeSystem;

use Difz25\TimeSystem\listener\EventListener;
use Difz25\TimeSystem\listener\TagResolveListener;
use JsonException;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class TimeSystem extends PluginBase implements Listener{

    private static TimeSystem $instance;
    public Config $time;

    public function onEnable(): void {
        $player = Player::class;
        $this->time = new Config($this->getDataFolder() . "players/" . strtolower($player->getName()) . ".yml", Config::YAML, [
            "Time" => 0,
        ]);
        
        $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
        $this->getServer()->getPluginManager()->registerEvents(new TagResolveListener($this), $this);
    }
    
    public function getPlayerConfig(string|Player $player): ?Config {
        if ($player instanceof Player) {
            if (is_file($this->getDataFolder() . "players/" . strtolower($player->getName()) . ".yml")) {
                return new Config($this->getDataFolder() . "players/" . strtolower($player->getName()) . ".yml", Config::YAML);
            }
        } else {
            if (is_file($this->getDataFolder() . "players/" . strtolower($player) . ".yml")) {
                return new Config($this->getDataFolder() . "players/" . strtolower($player) . ".yml", Config::YAML);
            }
        }
        
        return null;
    }
    
    public function Format($number): string {
        if(!is_numeric($number)) return '0:0:0';
    	$format = number_format((int) $number, 0, ':', ':');
    	return ':' . $format;
	}
    
    public function getPlayerTime(string|Player $player): ?array {
        if(($data = $this->getPlayerConfig($player)) !== null){
            return $data->get("Time");
        }
        
        return null;
    }
    
    public function getAllPlayerTime(string|Player $player): ?array {
        if(($data = $this->getPlayerConfig($player)) !== null){
            return $data->get("Time") !== null ? $data->get("Time") : [];
        }
        
        return null;
    }

    /**
     * @throws JsonException
     */
    public function addPlayerTime(string|Player $player, int $amount): bool {
        if (($data = $this->getPlayerConfig($player)) !== null){
            $time = $data->get("Time");
            $time += $amount;
            $data->set("Time", $time);
            $data->save();
            return true;
        }
    
    return false;
    }

    /**
     * @throws JsonException
     */
    public function reducePlayerTime(string|Player $player, int $amount): bool {
        if (($data = $this->getPlayerConfig($player)) !== null) {
            $time = $data->get("Time");
            $time -= $amount;
            $data->set("Time", $time);
            $data->save();
            return true;
        }

        return false;
    }

    /**
     * @throws JsonException
     */
    public function onJoin(PlayerJoinEvent $event): bool {
        $player = $event->getPlayer();
        if (($data = $this->getPlayerConfig($player)) !== null){
            $time = $data->get("Time");
            $time += 1;
            $data->set("Time", $time);
            $data->save();
            return true;
        }
        
        return false;
    }

    /**
     * @throws JsonException
     */
    public function onQuit(PlayerQuitEvent $event): bool {
        $player = $event->getPlayer();
        if (($data = $this->getPlayerConfig($player)) !== null){
            $data->reload();
            $data->save();
            return true;
        }
        
        return false;
    }
    
    public static function getInstance(): self {
        return self::$instance;
    }

    public function onLoad(): void {
        self::$instance = $this;
    }
}