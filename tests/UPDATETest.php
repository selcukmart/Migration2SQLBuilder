<?php
/**
 * @author selcukmart
 * 1.02.2022
 * 08:31
 */


use Migration2SQLBuilder\Migration2SQLBuilder;
use PHPUnit\Framework\TestCase;

class UPDATETest extends TestCase
{

    public function testExport()
    {
        $expected = [
            1 => [
                'type' => 'UPDATE',
                'table' => [
                    'abc'
                ]
            ],
            2 => [
                'type' => 'SET',
                0 => [
                    'a.column1' => 'value1',
                    'b.column2' => 'value2',
                    'column3' => 'value3'
                ]
            ],
            3 => [
                'type' => 'WHERE',
                'WHERE' => ' a = 1 AND b = C '
            ],
            4 => [
                'type' => 'LIMIT',
                0 => '10'
            ]
        ];
        $sql = "UPDATE abc SET a.column1 = value1, b.column2 = value2, column3 = value3 WHERE a = 1 AND b = C LIMIT 10";
        $migration = new Migration2SQLBuilder();
        $output = $migration->sqlBuilder($sql);
        $this->assertSame($expected, $output);
    }
}
