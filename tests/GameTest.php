<?php

use PHPUnit\Framework\TestCase;
use App\Game;
use App\GameRunner;

class GameTest extends TestCase
{
    protected static $fileNameSuffix = 1;
    protected $filePath;

    /**
     * @var File Write Resource
     */
    protected $fwr;

    /**
     * @var File Read Resource
     */
    protected $frr;

    protected function setUp()
    {
        ob_start();
        $this->filePath = __DIR__ . '/testData/ut_output_test' . self::$fileNameSuffix . '.txt';

        if (!file_exists($gdDir = dirname($this->filePath))) {
            mkdir($gdDir, 0777);
        }

        $this->fwr = fopen($this->filePath, 'wb');
        self::$fileNameSuffix++;
    }

    protected function tearDown()
    {
        if ($this->frr) {
            fclose($this->frr);
        }
    }

    protected function getFileContent()
    {
        $this->frr = fopen($this->filePath, 'r');
        return fread($this->frr, filesize($this->filePath));
    }

    protected function stdOutToFile()
    {
        $contents = ob_get_contents();
        fwrite($this->fwr, $contents);
        ob_end_clean();
    }

    public function test_When_Add1Name_Then_output()
    {
        $aGame = new Game();
        $aGame->add("Chet");
        $this->stdOutToFile();

        $expectString = 'Chet was added' . "\n" . 'They are player number 1' . "\n";
        $this->assertEquals($expectString, $this->getFileContent());
    }

    public function test_When_Add2Name_Then_output()
    {
        $aGame = new Game();
        $aGame->add("Chet");
        $aGame->add("Chris");
        $this->stdOutToFile();

        $expectString = 'Chet was added' . "\n" . 'They are player number 1' . "\n";
        $expectString .= 'Chris was added' . "\n" . 'They are player number 2' . "\n";
        $this->assertEquals($expectString, $this->getFileContent());
    }

    public function test_When_Add3Name_Then_output()
    {
        $aGame = new Game();
        $aGame->add("Chet");
        $aGame->add("Chris");
        $aGame->add("Mica");
        $this->stdOutToFile();

        $expectString = 'Chet was added' . "\n" . 'They are player number 1' . "\n";
        $expectString .= 'Chris was added' . "\n" . 'They are player number 2' . "\n";
        $expectString .= 'Mica was added' . "\n" . 'They are player number 3' . "\n";
        $this->assertEquals($expectString, $this->getFileContent());
    }

    public function test_Given_RandFixed_When_GameRunner_Then_output()
    {
        $gameRunner = new GameRunner();
        $gameRunner->execute(2, 5);
        $this->stdOutToFile();

        $expectString = 'Chet was added
They are player number 1
Pat was added
They are player number 2
Sue was added
They are player number 3
Chet is the current player
They have rolled a 3
Chet\'s new location is 3
The category is Rock
Rock Question 0
Answer was corrent!!!!
Chet now has 1 Gold Coins.
Pat is the current player
They have rolled a 3
Pat\'s new location is 3
The category is Rock
Rock Question 1
Answer was corrent!!!!
Pat now has 1 Gold Coins.
Sue is the current player
They have rolled a 3
Sue\'s new location is 3
The category is Rock
Rock Question 2
Answer was corrent!!!!
Sue now has 1 Gold Coins.
Chet is the current player
They have rolled a 3
Chet\'s new location is 6
The category is Sports
Sports Question 0
Answer was corrent!!!!
Chet now has 2 Gold Coins.
Pat is the current player
They have rolled a 3
Pat\'s new location is 6
The category is Sports
Sports Question 1
Answer was corrent!!!!
Pat now has 2 Gold Coins.
Sue is the current player
They have rolled a 3
Sue\'s new location is 6
The category is Sports
Sports Question 2
Answer was corrent!!!!
Sue now has 2 Gold Coins.
Chet is the current player
They have rolled a 3
Chet\'s new location is 9
The category is Science
Science Question 0
Answer was corrent!!!!
Chet now has 3 Gold Coins.
Pat is the current player
They have rolled a 3
Pat\'s new location is 9
The category is Science
Science Question 1
Answer was corrent!!!!
Pat now has 3 Gold Coins.
Sue is the current player
They have rolled a 3
Sue\'s new location is 9
The category is Science
Science Question 2
Answer was corrent!!!!
Sue now has 3 Gold Coins.
Chet is the current player
They have rolled a 3
Chet\'s new location is 0
The category is Pop
Pop Question 0
Answer was corrent!!!!
Chet now has 4 Gold Coins.
Pat is the current player
They have rolled a 3
Pat\'s new location is 0
The category is Pop
Pop Question 1
Answer was corrent!!!!
Pat now has 4 Gold Coins.
Sue is the current player
They have rolled a 3
Sue\'s new location is 0
The category is Pop
Pop Question 2
Answer was corrent!!!!
Sue now has 4 Gold Coins.
Chet is the current player
They have rolled a 3
Chet\'s new location is 3
The category is Rock
Rock Question 3
Answer was corrent!!!!
Chet now has 5 Gold Coins.
Pat is the current player
They have rolled a 3
Pat\'s new location is 3
The category is Rock
Rock Question 4
Answer was corrent!!!!
Pat now has 5 Gold Coins.
Sue is the current player
They have rolled a 3
Sue\'s new location is 3
The category is Rock
Rock Question 5
Answer was corrent!!!!
Sue now has 5 Gold Coins.
Chet is the current player
They have rolled a 3
Chet\'s new location is 6
The category is Sports
Sports Question 3
Answer was corrent!!!!
Chet now has 6 Gold Coins.
';

        $this->assertEquals($expectString, $this->getFileContent());
    }
}