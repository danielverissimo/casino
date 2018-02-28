<?php

namespace Cysha\Casino\Game;

use Cysha\Casino\Cards\Card;
use Cysha\Casino\Cards\CardCollection;
use Cysha\Casino\Cards\Contracts\CardEvaluator;
use Cysha\Casino\Cards\Deck;
use Cysha\Casino\Cards\HandCollection;
use Cysha\Casino\Cards\ResultCollection;
use Cysha\Casino\Game\Contracts\Dealer as DealerContract;
use Cysha\Casino\Game\Contracts\Name as NameContract;

class Dealer implements DealerContract, NameContract
{
    /**
     * @var Deck
     */
    private $deck;

    /**
     * @var CardEvaluator
     */
    private $cardEvaluationRules;

    /**
     * @var string
     */
    private $name = 'Dealer';

    /**
     * Dealer constructor.
     *
     * @param Deck          $deck
     * @param CardEvaluator $cardEvaluationRules
     */
    private function __construct(Deck $deck, CardEvaluator $cardEvaluationRules)
    {
        $this->deck = $deck;
        $this->cardEvaluationRules = $cardEvaluationRules;
    }

    /**
     * @param Deck          $deck
     * @param CardEvaluator $cardEvaluationRules
     *
     * @return Dealer
     */
    public static function startWork(Deck $deck, CardEvaluator $cardEvaluationRules)
    {
        return new self($deck, $cardEvaluationRules);
    }

    /**
     * @return Deck
     */
    public function deck(): Deck
    {
        return $this->deck;
    }

    /**
     * @return Card
     */
    public function dealCard(): Card
    {
        return $this->deck()->draw();
    }

    /**
     * Shuffles the deck.
     */
    public function shuffleDeck()
    {
        $this->deck()->shuffle();
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * @param CardCollection $board
     * @param HandCollection $playerHands
     *
     * @return ResultCollection
     */
    public function evaluateHands(CardCollection $board, HandCollection &$playerHands): ResultCollection
    {
        return $this->cardEvaluationRules->evaluateHands($board, $playerHands);
    }
}
