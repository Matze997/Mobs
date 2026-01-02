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
🚫 Nametags
```

# Screenshots

![Animals](https://github.com/Matze997/Mobs/blob/master/images/animals.png)
![Holding a carrot](https://github.com/Matze997/Mobs/blob/master/images/holding_a_carrot.png)
![Debug](https://github.com/Matze997/Mobs/blob/master/images/debug_mode.png)

# Config

```
#Mob Config file
version=0.0.3
debug=no

# Pathfinder settings
# Choose how many iterations the pathfinder makes until it stops (Higher value => More CPU usage)
maxPathfinderIterations=24
# Choose how many random positions are generated and checked until the system stops (Higher value => More "real" positions, but higher CPU usage)
maxRandomPositionGeneratorIterations=8

# Animal settings
animalsDropXpWhenBred=yes
# Choose how animals shall despawn:
# 0: Never
# 1: Only despawn if the mob had no interaction (Damage, Tamed, Tempt,...)
# 2: Always
animalDespawnBehavior=0
# Choose how animals shall behave when outside of simulation range:
# 0: Full simulation (Until they are out of the pocketmine simulation distance)
# 1: Limited simulation (No pathfinding, except when hurt and tempted) <- Recommended but not active by default
# 2: No simulation
animalSimulationBehavior=0

# Pig settings
registerPigs=yes
# Animals beyond that distance will despawn immediately
pigDespawnDistance=128
# Animals beyond that distance can despawn randomly
pigNoDespawnDistance=32
# See animalSimulationBehavior for more information
pigSimulationDistance=32

# Cow settings
registerCows=yes
cowDespawnDistance=128
cowNoDespawnDistance=32
cowSimulationDistance=32

# Chicken settings
registerChickens=yes
chickenDespawnDistance=128
chickenNoDespawnDistance=32
chickenSimulationDistance=32

# Sheep settings
registerSheep=yes
sheepDespawnDistance=128
sheepNoDespawnDistance=32
sheepSimulationDistance=32
sheepCanDestroyGrass=yes
```

# Credits

This plugin is basically a copy of [Minecraft JE mob system](https://github.com/mahtomedi/minecraft/tree/main/src/main/java/net/minecraft/world/entity)

Some parts where taken from [Altay](https://github.com/TuranicTeam/Altay) and from an old project of mine


Made by Matze, December 2022

Updated to PM5 in december 2025