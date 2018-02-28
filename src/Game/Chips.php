<?php

namespace Cysha\Casino\Game;

use JsonSerializable;

class Chips implements JsonSerializable
{
    /**
     * @var int
     */
    private $chips;

    /**
     * @var int
     */
    private $oldChips;

    /**
     * Chips constructor.
     *
     * @param int $chips
     */
    private function __construct(int $chips)
    {
        $this->chips = $chips;
        $this->oldChips = $chips;
    }

    /**
     * @return Chips
     */
    public static function zero(): self
    {
        return new self(0);
    }

    /**
     * @param int $int
     *
     * @return Chips
     */
    public static function fromAmount(int $int): self
    {
        return new self($int);
    }

    /**
     * @return int
     */
    public function amount()
    {
        return $this->chips;
    }

    /**
     * @return int
     */
    public function oldChips()
    {
        return $this->oldChips;
    }

    /**
     * @param Chips $chips
     */
    public function add(Chips $chips)
    {
        $this->oldChips = $this->chips;
        $this->chips += $chips->amount();
    }

    /**
     * @param Chips $chips
     */
    public function subtract(Chips $chips)
    {
        $this->oldChips = $this->chips;
        $this->chips -= $chips->amount();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->amount();
    }

    public function jsonSerialize()
    {
        return [
            'chips' => $this->chips,
        ];
    }
}
