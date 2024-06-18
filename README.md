# TimeSystem
TimeSystem was developed by [Difz25](https://github.com/Difz25)

# Depend
| Authors | Github | Plugin |
|---------|--------|-----|
| Ifera / Tayyab R. | [Ifera](https://github.com/Ifera) | [ScoreHud](https://github.com/Ifera/ScoreHud) |

# Bug / Issues
- Scoretag not showing time

# ScoreHud Support
| Scoretag | Feature |
| - | - |
| `{timesystem.time}` | To show player time |

# Table of contents
- [Examples](#examples)
  - [Get player's time](#get-players-time)
  - [Add player's time](#add-players-time)
  - [Reduce player's time](#reduce-players-time)
  - [Get all player's time](#get-all-players-time)

## Examples

### Get player's time

You can get player's thirst using the `getPlayerTime`  method. here's is an example:

```php
    public function Example(string|Player $player): void {
        $time = ThirstSystem::getInstance()->getPlayerTime($player);
        if($time < 0){
            $player->sendMessage("you thirst is " . $time);
        }
    }
```

### Add player's time

You can add player's thirst using the `addPlayerTime`  method. here's is an example:

```php
    public function Example(string|Player $player, int $amount): void {
        ThirstSystem::getInstance()->addPlayerTime($player, $amount);
        $player->sendMessage("you're earned" . $amount);
    }
```

### Reduce player's time

You can reduce player's thirst using the `reducePlayerTime`  method. here's is an example:

```php
    public function Example(string|Player $player, int $amount): void {
        ThirstSystem::getInstance()->reducePlayerTime($player, $amount);
        $player->sendMessage("you're thirst reduce to " $amount);
    }
```

### Get all player's time

You can reduce player's thirst using the `getAllPlayerTime`  method. here's is an example:

```php
    public function Example(string|Player $player, int $amount): void {
        ThirstSystem::getInstance()->getAllPlayerTime($player);
        $player->sendMessage("you all time is " . $amount);
    }
```
