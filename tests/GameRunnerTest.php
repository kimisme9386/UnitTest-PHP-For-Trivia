<?php

use PHPUnit\Framework\TestCase;
use App\GameRunner;

class GameRunnerTest extends TestCase
{
    const GOLDEN_MASTER_DIR_NAME = 'goldenMasterData';
    const TEST_RUNTIME_DIR_NAME = 'runtimeData';

    protected static $fileNameSuffix = 1;
    protected $filePath;

    /**
     * @var resource File write resource
     */
    protected $fwr;

    /**
     * @var resource file read resource
     */
    protected $frr;

    protected function setUp()
    {
        $this->createDir(self::GOLDEN_MASTER_DIR_NAME);
        $this->createDir(self::TEST_RUNTIME_DIR_NAME);
    }

    protected function tearDown()
    {
        if ($this->frr) {
            fclose($this->frr);
        }
    }

    protected function createDir($dirName = self::GOLDEN_MASTER_DIR_NAME)
    {
        $this->filePath = __DIR__ . '/' . $dirName . '/' . self::$fileNameSuffix . '.txt';
        if (!file_exists($dir = dirname($this->filePath))) {
            mkdir($dir, 0777);
        }
    }


    /**
     * @param string $dirName
     */
    protected function generateFile($dirName = self::GOLDEN_MASTER_DIR_NAME)
    {
        ob_start();
        $this->filePath = __DIR__ . '/' . $dirName . '/' . self::$fileNameSuffix . '.txt';
        $this->fwr = fopen($this->filePath, 'wb');
        self::$fileNameSuffix++;
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
        fclose($this->fwr);
    }

    protected function generateGoldenMaster()
    {
        $gameRunner = new GameRunner();
        for ($seed = 0; $seed <= 500; $seed++) {
            $this->generateFile(self::GOLDEN_MASTER_DIR_NAME);
            srand($seed);
            $gameRunner->execute();
            $this->stdOutToFile();
        }
    }

    public function test_Given_GeneratorGoldenMasterDataAnd_When_GameRunner_Then_MustGoldenMasterDataEqualGameRunnerOutput(
    )
    {
        $this->generateGoldenMaster();
        self::$fileNameSuffix = 1;

        $gameRunner = new GameRunner();

        $fileSuffix = 1;
        for ($seed = 0; $seed <= 500; $seed++) {
            $this->generateFile(self::TEST_RUNTIME_DIR_NAME);
            srand($seed);
            $gameRunner->execute();
            $this->stdOutToFile();

            $this->assertFileEquals(
                __DIR__ . '/' . self::GOLDEN_MASTER_DIR_NAME . '/' . $fileSuffix . '.txt',
                __DIR__ . '/' . self::TEST_RUNTIME_DIR_NAME . '/' . $fileSuffix . '.txt'
            );

            $fileSuffix++;
        }
    }
}
