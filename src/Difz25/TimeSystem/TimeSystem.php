<?php

namespace Difz25\TimeSystem;

use Difz25\TimeSystem\listener\EventListener;
use Difz25\TimeSystem\listener\TagResolveListener;
use JsonException;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class TimeSystem extends PluginBase implements Listener {

    private static TimeSystem $instance;

    public function onEnable(): void {
        $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
        $this->getServer()->getPluginManager()->registerEvents(new TagResolveListener($this), $this);
    }
    
    public function getPlayerConfig(string|Player $player): Config {
        if (!is_file($this->getDataFolder() . "players/" . strtolower($player->getName()) . ".yml")) {
            return new Config($this->getDataFolder() . "players/" . strtolower($player->getName()) . ".yml", Config::YAML, [
                    "Seconds" => 0,
                    "Minutes" => 0,
                    "Hours" => 0
            ]);
        } else {   
            return new Config($this->getDataFolder() . "players/" . strtolower($player) . ".yml", Config::YAML, [
                    "Seconds" => 0,
                    "Minutes" => 0,
                    "Hours" => 0
            ]);
        }
    }
    
    public function getPlayerTime(string|Player $player): bool {
        if(($data = $this->getPlayerConfig($player)) !== null){
            $seconds = $data->get("Seconds");
            $minutes = $data->get("Minutes");
            $hours = $data->get("Hours");
            return $hours . $minutes . $seconds;
        }
        
        return false;
    }
    
    public function getPlayerTimeFormat(string|Player $player): bool {
        if(($data = $this->getPlayerConfig($player)) !== null){
            $seconds = $data->get("Seconds");
            $minutes = $data->get("Minutes");
            $hours = $data->get("Hours");
            if($seconds == 60){
                $data->set("Seconds", 0);
                $minutes += 1;
                $data->set("Minutes", $minutes);
            }
            if($minutes == 60){
                $data->set("Minutes", 0);
                $hours += 1;
                $data->set("Hours", $hours);
            }
            $timeFormat = date($hours . ":" . $minutes . ":" . $seconds);
            return number_format($timeFormat);
        }
        
        return false;
    }
    
    public function getAllPlayerTime(string|Player $player): bool {
        if(($data = $this->getPlayerConfig($player)) !== null){
            $seconds = $data->get("Seconds");
            $minutes = $data->get("Minutes");
            $hours = $data->get("Hours");
            return $hours . $minutes . $seconds;
        }
        
        return false;
    }

    /**
     * @throws JsonException
     */
    public function addPlayerTime(string|Player $player, int $hour, int $minute, int $second): bool {
        if (($data = $this->getPlayerConfig($player)) !== null){
            $seconds = $data->get("Seconds");
            $minutes = $data->get("Minutes");
            $hours = $data->get("Hours");
            $seconds += $second;
            $data->set("Seconds", $seconds);
            $minutes += $minute;
            $data->set("Minutes", $minutes);
            $hours += $hour;
            $data->set("Hours", $hours);
            $data->save();
            return true;
        }
    
        return false;
    }

    /**
     * @throws JsonException
     */
    public function reducePlayerTime(string|Player $player, int $hour, int $minute, int $second): bool {
        if (($data = $this->getPlayerConfig($player)) !== null) {
            $seconds = $data->get("Seconds");
            $minutes = $data->get("Minutes");
            $hours = $data->get("Hours");
            $seconds -= $second;
            $data->set("Seconds", $seconds);
            $minutes -= $minute;
            $data->set("Minutes", $minutes);
            $hours -= $hour;
            $data->set("Hours", $hours);
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
        if($player->isOnline()){
            if (($data = $this->getPlayerConfig($player)) !== null){
                $seconds = $data->get("Seconds");
                $seconds += 1;
                $data->set("Seconds" , $seconds);
                $data->save();
                return true;
            }
        }
        
        return false;
    }

    /**
     * @throws JsonException
     */
    public function onLogin(PlayerLoginEvent $event): bool {
        $p = $event->getPlayer();
        if($p->isOnline()){
            if (($data = $this->getPlayerConfig($p)) !== null){
                $seconds = $data->get("Seconds");
                $seconds += 1;
                $data->set("Seconds" , $seconds);
                $data->save();
                return true;
            }
        }
        
        return false;
    }

    /**
     * @throws JsonException
     */
    public function onQuit(PlayerQuitEvent $event): bool {
        $player = $event->getPlayer();
        if(!$player->isOnline()){
            if (($data = $this->getPlayerConfig($player)) !== null){
                $data->reload();
                $data->save();
                return true;
            }
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
