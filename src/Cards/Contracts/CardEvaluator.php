<?php

namespace Cysha\Casino\Cards\Contracts;

use Cysha\Casino\Cards\CardCollection;
use Cysha\Casino\Cards\Hand;
use Cysha\Casino\Cards\HandCollection;

interface CardEvaluator
{
    /**
     * @param CardCollection $board
     * @param Hand           $hand
     *
     * @return CardResults
     */
    public static function evaluate(CardCollection $board, Hand $hand): CardResults;

    /**
     * @param CardCollection $board
     * @param HandCollection $hands
     */
    public function evaluateHands(CardCollection $board, HandCollection &$hands);

    /**
     * Calculate hands equity
     *
     * @param CardCollection $board
     * @param HandCollection $playerHands
     * @param $remainCards
     * @return mixed
     */
    public function evaluateHandsEquity(CardCollection $board, HandCollection $playerHands);
}
