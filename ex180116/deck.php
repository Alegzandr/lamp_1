<?php

class Cards
{
    private $face, $color;

    public function __construct($color, $face)
    {
        $this->face = $face;
        $this->color = $color;
    }

    public function getFace()
    {
        return $this->face;
    }

    public function getColor()
    {
        return $this->color;
    }

    public function getValue()
    {
        if (is_int($this->face)) {
            return $this->face;
        } else {
            return 10;
        }
    }

    public function __toString()
    {
        return $this->face . ' de ' . $this->color;
    }
}

class Deck
{
    private $cards;

    public function __construct()
    {
        $this->cards = [];
        $faces = range(1, 10);
        $faces = array_merge($faces, ['Jack', 'Queen', 'King']);
        $colors = ['Heart', 'Club', 'Diamond', 'Spade'];

        foreach ($colors as $color) {
            foreach ($faces as $face) {
                $this->cards[] = new Cards($color, $face);
            }
        }
    }

    public function shuffle()
    {
        shuffle($this->cards);
        return this;
    }

    public function deal($n = 1)
    {
        return;
    }
}

$d = new Deck();
list($card) = $d->shuffle()->deal(1);

echo $card;