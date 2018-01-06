<?php

namespace Cysha\Casino\Game;

use Cysha\Casino\Game\Contracts\Name as NameContract;

class Client implements NameContract
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var Chips
     */
    private $wallet;

    /**
     * ClientTest constructor.
     *
     * @param integer $id
     * @param string $name
     * @param Chips $chips
     */
    public function __construct(int $id, $name, Chips $wallet = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->wallet = $wallet ?? Chips::zero();
    }

    /**
     * @param string $name
     * @param Chips  $chips
     *
     * @return Client
     */
    public static function register(int $id, $name, Chips $chips = null): Client
    {
        return new static($id, $name, $chips);
    }

    /**
     * @return integer
     */
    public function id(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * @return Chips
     */
    public function wallet(): Chips
    {
        return $this->wallet;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->name();
    }
}
