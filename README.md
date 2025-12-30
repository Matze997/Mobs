# Mobs
🐖Mob plugin for PocketMine-MP V5.*

# Features

```
✅️ Highly configurable
✅️ Easy to use
✅️ Vanilla like mob behavior
```

# Note
This project takes quite a lot of time, and I probably can not do it all myself. Every help is welcome and appreciated :)

This plugin is not meant to be efficient. (At the moment)

# Mob List

### Animals
| Name           | Implemented | Note                   |
|----------------|-----------|------------------------|
| `Cow`          | ✅         |
| `Chicken`      | ✅         | Jockey not implemented |
| `Pig`          | ✅         | Riding not implemented |
| `Sheep`        | ✅         | `_jeb`  not implemented |

# Missing Features

```
🚫 Leashes
🚫 Individual block cost
🚫 Natural spawning
```


# Requires

[libPMMLP](https://github.com/PMMLP/libPMMLP)

# Screenshots

![Animals](https://github.com/Matze997/Mobs/blob/master/images/animals.png)
![Holding a carrot](https://github.com/Matze997/Mobs/blob/master/images/holding_a_carrot.png)
![Debug](https://github.com/Matze997/Mobs/blob/master/images/debug_mode.png)

# Config

```
# Set the maximum pathfinder iterations (Higher => More CPU, more precise pathfinding)
maxPathfinderIterations: 24

# Set how much random positions should be generated (Higher => More CPU, better positions)
maxRandomPositionGeneratorIterations: 8

registerPigs: true
pigDespawnDistance: 128
pigNoDespawnDistance: 32

registerCows: true
cowDespawnDistance: 128
cowNoDespawnDistance: 32

registerChickens: true
chickenDespawnDistance: 128
chickenNoDespawnDistance: 32

registerSheep: true
sheepDespawnDistance: 128
sheepNoDespawnDistance: 32

# Set if sheep can destroy grass while eating
sheepCanDestroyGrass: true


# Do not touch!
version: 0.0.1

```

# Credits

This plugin is basically a copy of Minecraft JE mob system

Some parts where taken from [Altay](https://github.com/TuranicTeam/Altay) and from an old project of mine


Made by Matze, December 2022
Updated to PM5 in december 2025