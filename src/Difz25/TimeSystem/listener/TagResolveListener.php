<?php
declare(strict_types = 1);

namespace Difz25\TimeSystem\listener;

use Ifera\ScoreHud\event\TagsResolveEvent;
use Difz25\TimeSystem\TimeSystem;
use pocketmine\event\Listener;
use function count;
use function explode;

/**
* @property $eco
* @property TimeSystem $plugin
*/
class TagResolveListener implements Listener{

    private TimeSystem $plugin;

    public function __construct(TimeSystem $plugin){
        $this->plugin = $plugin;
    }

    public function onTagResolve(TagsResolveEvent $event): void {
        $tag = $event->getTag();
        $player = $event->getPlayer();
        $tags = explode('.', $tag->getName(), 2);
        $value = "";

        if($tags[0] !== 'timesystem' || count($tags) < 2){
            return;
        }

        if ($tags[1] == "time") {
            $value = $this->plugin->Format($this->plugin->getPlayerTime($player));
        }
        $tag->setValue(($value));
    }
}