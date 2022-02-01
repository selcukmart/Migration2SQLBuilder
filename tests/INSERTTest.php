<?php
/**
 * @author selcukmart
 * 1.02.2022
 * 08:31
 */


use Migration2SQLBuilder\Migration2SQLBuilder;
use PHPUnit\Framework\TestCase;

class INSERTTest extends TestCase
{

    public function testExport()
    {
        $expected = [
            1 => [
                'type' => 'INSERT',
                'table' => [
                    'abc'
                ]
            ],
            2 => [
                'type' => 'SET',
                0 => [
                    'column1' => 'value1',
                    'column2' => 'value2',
                    'column3' => 'value3'
                ]
            ],
            3 => [
                'type' => 'WHERE',
                'WHERE' => ' a = 1 AND b = C '
            ]
        ];
        $sql = "INSERT INTO abc SET column1 = value1, column2 = value2, column3 = value3 WHERE a = 1 AND b = C";
        $migration = new Migration2SQLBuilder();
        $output = $migration->sqlBuilder($sql);
        $this->assertSame($expected, $output);
    }
}
