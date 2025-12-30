<?php

declare(strict_types=1);

namespace matze\mobs\entity\spawning\rule;

use pocketmine\block\Block;
use pocketmine\world\Position;

class RequiredBlockTypesSpawnRule extends SpawnRule {
    /** @var Block[] */
    protected array $blocks = [];

    public function __construct(
        Block... $blocks
    ){
        foreach($blocks as $block) {
            $this->blocks[] = $block;
        }
    }

    public function appliesToPosition(Position $position): bool{
        $check = $position->getWorld()->getBlock($position->down());
        foreach($this->blocks as $block) {
            if($check->isSameState($block)) {
                return true;
            }
        }
        return false;
    }
}