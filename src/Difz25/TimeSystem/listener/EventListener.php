<?php
declare(strict_types = 1);

namespace Difz25\TimeSystem\listener;

use Difz25\TimeSystem\TimeSystem;
use Ifera\ScoreHud\event\PlayerTagsUpdateEvent;
use Ifera\ScoreHud\scoreboard\ScoreTag;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\player\Player;

class EventListener implements Listener{

    /** @var TimeSystem */
    private TimeSystem $plugin;

    public function __construct(TimeSystem $plugin){
        $this->plugin = $plugin;
    }

    public function onJoin(PlayerJoinEvent $event): void {
        $player = $event->getPlayer();
        $this->sendTags($player);
    }

    private function sendTags(Player $player): void{
        (new PlayerTagsUpdateEvent($player, [
            new ScoreTag("timesystem.time", $this->plugin->getPlayerTimeFormat($player))
        ]))->call();
    }
}