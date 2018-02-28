<?php

namespace Cysha\Casino\Cards;

use Cysha\Casino\Game\Contracts\Player;

class Hand implements \Countable, \JsonSerializable
{
    /**
     * @var CardCollection
     */
    private $cards;

    /**
     * @var Player
     */
    private $player;

    /**
     * @var int
     */
    private $winCount;

    /**
     * @var int
     */
    private $tieCount;

    /**
     * Hand constructor.
     *
     * @param CardCollection $cards
     * @param Player         $player
     */
    private function __construct(CardCollection $cards, Player $player)
    {
        $this->cards = $cards;
        $this->player = $player;
    }

    /**
     * @var string
     * @var Player $player
     *
     * @return static
     */
    public static function fromString(string $cards, Player $player)
    {
        $cards = explode(' ', $cards);

        return static::create(CardCollection::make($cards)->map(function ($card) {
            return Card::fromString($card);
        }), $player);
    }

    /**
     * @var string
     * @var Player $player
     *
     * @return static
     */
    public static function create(CardCollection $cards, Player $player)
    {
        return new static($cards, $player);
    }

    /**
     * @return Player
     */
    public function player(): Player
    {
        return $this->player;
    }

    /**
     * @return CardCollection
     */
    public function cards(): CardCollection
    {
        return $this->cards;
    }

    /**
     * Count cards in the hand.
     */
    public function count(): int
    {
        return $this->cards()->count();
    }

    /**
     * @param Card $card
     */
    public function addCard(Card $card)
    {
        $this->cards = $this->cards()->push($card);
    }

    /**
     * Return winner count
     *
     * @return int
     */
    public function winCount()
    {
        return $this->winCount;
    }

    /**
     * Increment winner count
     *
     * @return int
     */
    public function incrementWinCount()
    {
        return $this->winCount++;
    }

    /**
     * Return winner tie count
     *
     * @return int
     */
    public function tieCount()
    {
        return $this->tieCount;
    }

    /**
     * Increment winner tie count
     *
     * @return int
     */
    public function incrementTieCount()
    {
        return $this->tieCount++;
    }

    public function __toString()
    {
        return $this->cards()->__toString();
    }

    function jsonSerialize()
    {
        return [
            'cards' => $this->cards->jsonSerialize(),
            'player' => $this->player->jsonSerialize()
        ];
    }
}
