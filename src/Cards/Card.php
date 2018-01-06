<?php

namespace Cysha\Casino\Cards;

use Cysha\Casino\Exceptions\CardException;
use InvalidArgumentException;
use JsonSerializable;

class Card implements JsonSerializable
{
    const ACE = 1;
    const JACK = 11;
    const QUEEN = 12;
    const KING = 13;
    const ACE_HIGH = 14;

    const ACE_SYMBOL = 'A';
    const KING_SYMBOL = 'K';
    const QUEEN_SYMBOL = 'Q';
    const JACK_SYMBOL = 'J';
    const TEN_SYMBOL = 'T';

    const ACE_LONG_NAME = 'Ace';
    const KING_LONG_NAME = 'King';
    const QUEEN_LONG_NAME = 'Queen';
    const JACK_LONG_NAME = 'Jack';

    private static $faceCards = [
        self::ACE,
        self::JACK,
        self::QUEEN,
        self::KING,
        self::ACE_HIGH,
    ];

    private static $faceCardShortNames = [
        self::ACE => self::ACE_SYMBOL,
        self::JACK => self::JACK_SYMBOL,
        self::QUEEN => self::QUEEN_SYMBOL,
        self::KING => self::KING_SYMBOL,
        10 => self::TEN_SYMBOL,
    ];

    /**
     * The Suit of this card.
     */
    protected $suit;

    /**
     * The Value of this card.
     */
    protected $value;

    /**
     * @param int  $value the value of the card
     * @param Suit $suit  the suit of the card
     *
     * @throws InvalidArgumentException
     */
    public function __construct($value, Suit $suit)
    {
        $this->value = $value;

        if (!$this->isValidCardValue()) {
            throw new InvalidArgumentException('Invalid card value given: ' . $value);
        }

        $this->suit = $suit;
    }

    /**
     * Get the Face value of the card.
     *
     * @return int face value
     */
    public function value()
    {
        return $this->value;
    }

    /**
     * Get the Suit of the card.
     *
     * @return Suit
     */
    public function suit()
    {
        return $this->suit;
    }

    /**
     * Gets human readable value for a given card.
     *
     * @return string
     */
    public function name()
    {
        return $this->shortName();
    }

    /**
     * Gets human readable value for a given card.
     *
     * @return string
     */
    public function shortName()
    {
        if ($this->isAce()) {
            return self::ACE_SYMBOL;
        }
        if ($this->isKing()) {
            return self::KING_SYMBOL;
        }
        if ($this->isQueen()) {
            return self::QUEEN_SYMBOL;
        }
        if ($this->isJack()) {
            return self::JACK_SYMBOL;
        }
        if ($this->value() === 10) {
            return self::TEN_SYMBOL;
        }

        return (string) $this->value();
    }

    /**
     * Gets human readable value for a given card.
     *
     * @return string
     */
    public function longName()
    {
        if ($this->isAce()) {
            return self::ACE_LONG_NAME;
        }
        if ($this->isKing()) {
            return self::KING_LONG_NAME;
        }
        if ($this->isQueen()) {
            return self::QUEEN_LONG_NAME;
        }
        if ($this->isJack()) {
            return self::JACK_LONG_NAME;
        }

        $number = new \NumberFormatter('en', \NumberFormatter::SPELLOUT);

        return ucwords($number->format($this->value()));
    }

    /**
     * @return Card
     */
    public static function fromString($value): Card
    {
        try {
            $symbol = substr($value, 1);
            $cardValue = substr($value, 0, 1);

            if (preg_match('/([0-9]+)(.+)/', $value, $matches)) {
                $cardValue = $matches[1];
                $symbol = $matches[2];
            }

            $facecardLetter = ucfirst($cardValue);

            if (ctype_alpha($facecardLetter) === true) {
                $cardValue = static::getRawCardValueFromShortFaceName(ucfirst($facecardLetter));
            }

            return new static((int) $cardValue, Suit::fromString($symbol));
        } catch (\Exception $exception) {
            throw CardException::invalidCardString($value);
        }
    }

    /**
     * @param string $value
     *
     * @return int
     *
     * @throws CardException
     */
    private static function getRawCardValueFromShortFaceName($value)
    {
        $cardValue = ucfirst($value);

        foreach (static::$faceCardShortNames as $key => $symbol) {
            if ($symbol === $cardValue) {
                return $key;
            }
        }

        throw CardException::invalidCardString($value);
    }

    /**
     * @return bool
     */
    protected function isValidCardValue()
    {
        if ($this->isNumberCard()) {
            return true;
        }

        if ($this->isFaceCard()) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function isNumberCard()
    {
        $values = range(2, 10);

        return in_array($this->value(), $values, true);
    }

    /**
     * @return bool
     */
    public function isFaceCard()
    {
        $values = [
            $this->isJack(),
            $this->isQueen(),
            $this->isKing(),
            $this->isAce(),
        ];

        return in_array(true, $values, true);
    }

    /*
     * Is this an Ace
     *
     * @return bool
     */
    public function isAce()
    {
        return $this->value === static::ACE || $this->value === self::ACE_HIGH;
    }

    /**
     * Is this a King.
     *
     * @return bool
     */
    public function isKing()
    {
        return $this->value === static::KING;
    }

    /**
     * Is this a Queen.
     *
     * @return bool
     */
    public function isQueen()
    {
        return $this->value === static::QUEEN;
    }

    /**
     * Is this a Jack.
     *
     * @return bool
     */
    public function isJack()
    {
        return $this->value === static::JACK;
    }

    /**
     * @param mixed $card
     *
     * @return bool
     */
    public function equals($card)
    {
        return get_class($card) === static::class
        && $card->value === $this->value;
    }

    /**
     * @return string
     */
    public function shortIdentifier()
    {
        return sprintf('%s%s', $this->shortName(), $this->suit()->symbol());
    }

    /**
     * @return string
     */
    public function longIdentifier()
    {
        return sprintf('%s of %s', $this->longName(), $this->suit()->name());
    }

    /**
     * Returns a human readable string for outputting the card.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->shortIdentifier();
    }

    function jsonSerialize()
    {
        return [
            'suit' => $this->suit()->value(),
            'value' => $this->value,
        ];
    }
}
