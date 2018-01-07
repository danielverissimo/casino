<?php

namespace Cysha\Casino\Game;

use Cysha\Casino\Game\Contracts\Player;
use Illuminate\Support\Collection;
use JsonSerializable;

class PlayerCollection extends Collection implements JsonSerializable
{
    /**
     * @param string $playerName
     *
     * @return Player|null
     */
    public function findByName($playerName)
    {
        return $this->first(function (Player $player) use ($playerName) {
            return $player->name() === $playerName;
        });
    }

    /**
     * @return LeftToAct
     */
    public function resetPlayerListFromSeat(int $seatNumber): self
    {
        $new = $this->sortBy(function ($value, $idx) use ($seatNumber) {
            if ($idx < $seatNumber) {
                return ($idx + 1) * 10;
            }

            return $idx;
        });

        return new self($new->values());
    }
}
