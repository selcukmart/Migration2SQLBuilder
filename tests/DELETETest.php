<?php
/**
 * @author selcukmart
 * 1.02.2022
 * 08:31
 */


use Migration2SQLBuilder\Migration2SQLBuilder;
use PHPUnit\Framework\TestCase;

class DELETETest extends TestCase
{

    public function testExport()
    {
        $expected = [
            1 => [
                'type' => 'DELETE'
            ],
            2 => [
                'type' => 'FROM',
                0 => 'abc'
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
        $sql = "DELETE FROM abc WHERE a = 1 AND b = C LIMIT 10";
        $migration = new Migration2SQLBuilder();
        $output = $migration->sqlBuilder($sql);
        $this->assertSame($expected, $output);
    }
}
