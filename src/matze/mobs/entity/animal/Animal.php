<?php

declare(strict_types=1);

namespace matze\mobs\entity\animal;

use matze\mobs\entity\AgeableMob;
use matze\mobs\entity\ai\navigation\GroundPathNavigation;
use matze\mobs\entity\ai\navigation\PathNavigation;
use matze\mobs\entity\ai\pathfinder\evaluator\NodeEvaluator;
use matze\mobs\entity\ai\pathfinder\evaluator\WalkNodeEvaluator;
use matze\mobs\util\MobsConfig;
use pocketmine\entity\Entity;
use pocketmine\item\Item;
use pocketmine\item\VanillaItems;
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use pocketmine\world\particle\HeartParticle;

abstract class Animal extends AgeableMob {
    protected int $inLove = 0;

    protected function entityBaseTick(int $tickDiff = 1): bool{
        if($this->inLove > 0) {
            $this->inLove -= $tickDiff;

            if($this->inLove % 10 === 0) {
                $this->getWorld()->addParticle($this->location->add(random_int(-5, 5) / 10, random_int(0, 5) / 10, random_int(-5, 5) / 10), new HeartParticle());
            }
        }
        return parent::entityBaseTick($tickDiff);
    }

    public function onInteract(Player $player, Vector3 $clickPos): bool{
        if(parent::onInteract($player, $clickPos)) {
            return true;
        }
        $item = $player->getInventory()->getItemInHand();

        if($this->isFood($item)) {
            if($this->isBaby()) {
                $this->age += (int)($this::AGE_ADULT * 0.1);
                $item->pop();
                $player->getInventory()->setItemInHand($item);
                $this->scheduleUpdate();
                return true;
            }
            if($this->isInLove() || $this->hasBreedCooldown()) {
                return false;
            }
            $item->pop();
            $player->getInventory()->setItemInHand($item);
            $this->setInLove();
        }
        return true;
    }

    public function canMate(Entity $entity): bool {
        if(!$entity instanceof Animal) {
            return false;
        }
        if($entity->getId() === $this->getId()) {
            return false;
        }
        if($entity::class !== $this::class) {
            return false;
        }
        return $entity->isInLove() && $this->isInLove();
    }

    public function spawnChildFromBreeding(Animal $animal): void {
        $mob = $this->getBreedOffspring($animal);
        $mob->spawnToAll();

        $animal->resetBreedCooldown();
        $this->resetBreedCooldown();
        $animal->setInLove(false);
        $this->setInLove(false);

        if(MobsConfig::$animalsDropXpWhenBred) {
            //TODO: Add check for game rule 'doMobLoot'
            //TODO: PocketMine does not have game rules at the moment
            $this->getWorld()->dropExperience($this->location, $this->getBreedXpAmount());
        }
    }

    public function isInLove(): bool {
        return $this->inLove > 0;
    }

    public function setInLove(bool $inLove = true): void {
        $this->inLove = $inLove ? 300 : 0;
    }

    public function getBreedXpAmount(): int {
        return 0;
    }

    public function removeWhenFarAway(): bool{
        return false;
    }

    public function isFood(Item $item): bool {
        return $item->equals(VanillaItems::WHEAT(), false, false);
    }

    protected function initNodeEvaluator(): NodeEvaluator{
        return new WalkNodeEvaluator($this);
    }

    protected function initPathNavigation(): PathNavigation{
        return new GroundPathNavigation($this);
    }

    public function getDebugInfo(array &$info): void{
        parent::getDebugInfo($info);
        $info[] = "§6Love Ticks§7: ".$this->inLove;
    }
}