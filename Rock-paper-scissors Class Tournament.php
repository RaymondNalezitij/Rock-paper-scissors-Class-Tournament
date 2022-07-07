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
    private int $score = 0;
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

    public function getScore(): int
    {
        return $this->score;
    }

    public function setScore($number = 1): void
    {
        $this->score = ($this->score + 1) * $number;
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
        $p1->setScore(0);
        $p2->setScore(0);

        $winScore = 2;

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
                $p1->setScore();
            } else {
                echo "| $turn | {$p2->getName()} won using {$p2choise->getElement()} \n";
                $p2->setScore();
            }
            $turn++;

        } while ($p1->getScore() < $winScore && $p2->getScore() < $winScore);

        if ($p1->getScore() == 2) {
            return [$p1, $p2];
        } else {
            return [$p2, $p1];
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

    private array $gameSet1; //winner is [$i][0] and loser is [$i][1] <- $i = game number
    private array $gameSet2; //winner is [$i][0] and loser is [$i][1] <- $i = game number
    private array $gameSet3; //winner is [$i][0] and loser is [$i][1] <- $i = game number

    public function tournament()
    {
        echo "\nRound 1\n";
        echo "-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-\n";

        $game1 = new Game(new Player(readline("Enter user name: "), true), new Player("Aldis"));
        $this->gameSet1[] = $game1->run();
        print_r($this->gameSet1[0][0]->getName() . " has won {$this->gameSet1[0][0]->getScore()} to {$this->gameSet1[0][1]->getScore()}\n");

        $game2 = new Game(new Player("Patrīcija"), new Player("Mārtiņš"));
        $this->gameSet1[] = $game2->run();
        print_r($this->gameSet1[1][0]->getName() . " has won {$this->gameSet1[1][0]->getScore()} to {$this->gameSet1[1][1]->getScore()}\n");

        $game3 = new Game(new Player("Rihards"), new Player("Marta"));
        $this->gameSet1[] = $game3->run();
        print_r($this->gameSet1[2][0]->getName() . " has won {$this->gameSet1[2][0]->getScore()} to {$this->gameSet1[2][1]->getScore()}\n");

        $game4 = new Game(new Player("Valters"), new Player("Anna"));
        $this->gameSet1[] = $game4->run();
        print_r($this->gameSet1[3][0]->getName() . " has won {$this->gameSet1[3][0]->getScore()} to {$this->gameSet1[3][1]->getScore()}\n");

        echo "\nRound 2\n";
        echo "-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-\n";

        $game5 = new Game($this->gameSet1[0][0], $this->gameSet1[1][0]);
        $this->gameSet2[] = $game5->run();
        print_r($this->gameSet2[0][0]->getName() . " has won {$this->gameSet2[0][0]->getScore()} to {$this->gameSet2[0][1]->getScore()}\n");

        $game6 = new Game($this->gameSet1[2][0], $this->gameSet1[3][0]);
        $this->gameSet2[] = $game6->run();
        print_r($this->gameSet2[1][0]->getName() . " has won {$this->gameSet2[1][0]->getScore()} to {$this->gameSet2[1][1]->getScore()}\n");

        echo "\nRound 3\n";
        echo "-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-\n";

        $game7 = new Game($this->gameSet2[0][0], $this->gameSet2[1][0]);
        $this->gameSet3[] = $game7->run();
        print_r($this->gameSet3[0][0]->getName() . " has won {$this->gameSet3[0][0]->getScore()} to {$this->gameSet3[0][1]->getScore()}\n\n");
        print_r($this->gameSet3[0][0]->getName() . " has won the tournament!\n");

    }

}

$tournament = new Tournament();
$tournament->tournament();
