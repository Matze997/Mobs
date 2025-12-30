<?php

declare(strict_types=1);

namespace matze\mobs\entity\ai\goal;

use matze\mobs\util\BlockFinder;
use pocketmine\math\Vector3;

class WaterAvoidingRandomStrollGoal extends RandomStrollGoal {
    public function getPosition(): ?Vector3{
        if($this->mob->isHeadInsideOfLiquid()) {
            return BlockFinder::findLandBlock($this->mob->getPosition())?->getPosition();
        }
        return parent::getPosition();
    }
}