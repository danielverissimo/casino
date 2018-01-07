<?php

namespace Cysha\Casino\Game;

use Cysha\Casino\Game\Contracts\Player;
use Illuminate\Support\Collection;
use JsonSerializable;

class ChipStackCollection extends Collection implements JsonSerializable
{
    public function total(): Chips
    {
        return Chips::fromAmount($this->sum(function (Chips $chips) {
            return $chips->amount();
        }));
    }

    /**
     * @return static
     */
    public function sortByChipAmount()
    {
        return self::make($this->sortBy->amount());
    }

    /**
     * @return Chips
     */
    public function findByPlayer(Player $player): Chips
    {
        return $this->get($player->name()) ?? Chips::zero();
    }
}
