<?php

namespace memclutter\PhpTodo;

class UtilsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param $path
     * @param $expected
     *
     * @dataProvider normalizeFilePathDataProvider
     */
    public function testNormalizeFilePath($path, $expected)
    {
        $this->assertEquals($expected, Utils::normalizeFilePath($path));
    }

    /**
     * @param $a
     * @param $b
     * @param $expected
     *
     * @dataProvider arrayMergeDataProvider
     */
    public function testArrayMerge($a, $b, $expected)
    {
        $this->assertEquals($expected, Utils::arrayMerge($a, $b));
    }

    /**
     * @return array
     */
    public function normalizeFilePathDataProvider()
    {
        return [
            'unix path' => ['/template/index.tpl.php', 'template' . DIRECTORY_SEPARATOR . 'index.tpl.php'],
            'windows path' => ['\\template\\index.tpl.php', 'template' . DIRECTORY_SEPARATOR . 'index.tpl.php'],
        ];
    }

    /**
     * @return array
     */
    public function arrayMergeDataProvider()
    {
        return [
            'one levels numeric key array' => [
                // a
                ['green', 'red'],
                // b
                ['black'],
                // expected
                ['green', 'red', 'black'],
            ],
        ];
    }
}
