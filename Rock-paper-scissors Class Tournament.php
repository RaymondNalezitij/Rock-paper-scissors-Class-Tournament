<?php

class Element
{

    private $element;
    private array $win = [];

    public function __construct(string $element)
    {
        $this->element = $element;
    }

    public function getElement(): string
    {
        return $this->element;
    }

    public function addWinners(Element $element): void
    {
        foreach ($element as $elements) {
            if (!$element instanceof Element)
                $this->addWin($elements);
        }
    }

    public function addWin(Element $element): void
    {
        $this->win[] = $element;
    }

    public function isStrongAgainst(Element $element): bool
    {
        return in_array($element, $this->win);
    }

}

class Player
{
    private string $name;
    private ?Element $selection = null;
    private int $isWinner = 0;
    private bool $isPlayer;

    public function __construct($name, $isPlayer = false)
    {
        $this->name = $name;
        $this->isPlayer = $isPlayer;
    }

    public function getSelection(): Element
    {
        return $this->selection;
    }

    public function setSelection(Element $selection): void
    {
        $this->selection = $selection;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getIsWinner(): int
    {
        return $this->isWinner;
    }

    public function setIsWinner($number = 1): void
    {
        $this->isWinner = ($this->isWinner + 1) * $number;
    }

    public function getIsPlayer()
    {
        return $this->isPlayer;
    }

}

class Game
{

    private array $elements = [];
    private $player1;
    private $player2;

    public function __construct($player1, $player2)
    {
        $this->setup();
        $this->player1 = $player1;
        $this->player2 = $player2;
    }

    public function setup(): void
    {

        $this->elements = [
            $rock = new Element("rock"),
            $paper = new Element("paper"),
            $scissors = new Element("scissors"),
        ];

        $rock->addWin($scissors);
        $paper->addWin($rock);
        $scissors->addWin($paper);

    }

    public function run()
    {
        $p1 = $this->player1;
        $p2 = $this->player2;
        $p1->setIsWinner(0);
        $p2->setIsWinner(0);

        $turn = 1;
        echo "\n";
        echo "{$p1->getName()} vs {$p2->getName()}\n";
        do {

            if ($p1->getIsPlayer()) {
                do {
                    echo "{$p1->getName()} select an element: ";
                    $playerChoice = (int)readline($this->displayElements());
                } while ($playerChoice < 0 || $playerChoice > 2);
                $p1->setSelection($this->elements[$playerChoice]);
            } else {
                $p1->setSelection($this->elements[rand(0, 2)]);
            }

            if ($p2->getIsPlayer()) {
                do {
                    echo "{$p1->getName()} select an element: ";
                    $playerChoice = (int)readline($this->displayElements());
                } while ($playerChoice < 0 || $playerChoice > 2);
                $p1->setSelection($this->elements[$playerChoice]);
            } else {
                $p2->setSelection($this->elements[rand(0, 2)]);
            }

            $p1choise = $p1->getSelection();
            $p2choise = $p2->getSelection();

            if ($p1choise === $p2choise) {
                echo "| $turn | tie both chose {$p1choise->getElement()}\n";
            } else if ($p1choise->isStrongAgainst($p2choise)) {
                echo "| $turn | {$p1->getName()} won using {$p1choise->getElement()} \n";
                $p1->setIsWinner();
            } else {
                echo "| $turn | {$p2->getName()} won using {$p2choise->getElement()} \n";
                $p2->setIsWinner();
            }
            $turn++;

        } while ($p1->getIsWinner() <= 1 && $p2->getIsWinner() <= 1);

        if ($p1->getIsWinner() == 2) {
            return $p1;
        } else {
            return $p2;
        }
    }

    public function displayElements(): void
    {
        foreach ($this->elements as $key => $element) {
            echo "[$key] {$element->getElement()} ";
        }
    }
}

class Tournament
{

    private array $winner1;
    private array $winner2;
    private array $winner3;

    public function tournament()
    {
        echo "\nRound 1\n";
        echo "-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-\n";

        $game1 = new Game(new Player(readline("Enter user name: "), true), new Player("Aldis"));
        $this->winner1[] = $game1->run();
        print_r($this->winner1[0]->getName() . " has won\n");

        $game2 = new Game(new Player("Patrīcija"), new Player("Mārtiņš"));
        $this->winner1[] = $game2->run();
        print_r($this->winner1[1]->getName() . " has won\n");

        $game3 = new Game(new Player("Rihards"), new Player("Marta"));
        $this->winner1[] = $game3->run();
        print_r($this->winner1[2]->getName() . " has won\n");

        $game4 = new Game(new Player("Valters"), new Player("Anna"));
        $this->winner1[] = $game4->run();
        print_r($this->winner1[3]->getName() . " has won\n");

        echo "\nRound 2\n";
        echo "-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-\n";

        $game5 = new Game($this->winner1[0], $this->winner1[1]);
        $this->winner2[] = $game5->run();
        print_r($this->winner2[0]->getName() . " has won\n");

        $game6 = new Game($this->winner1[2], $this->winner1[3]);
        $this->winner2[] = $game6->run();
        print_r($this->winner2[1]->getName() . " has won\n");

        echo "\nRound 3\n";
        echo "-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-\n";

        $game7 = new Game($this->winner2[0], $this->winner2[1]);
        $this->winner3[] = $game7->run();

        print_r($this->winner3[0]->getName() . " has won the tournament!\n");

    }
}

$tournament = new Tournament();
$tournament->tournament();
