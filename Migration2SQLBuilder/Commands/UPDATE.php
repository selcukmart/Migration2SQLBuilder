<?php
/**
 * @author selcukmart
 * 16.05.2021
 * 13:21
 */

namespace Migration2SQLBuilder\Commands;


use Migration2SQLBuilder\Migration2SQLBuilder;
use Migration2SQLBuilder\MigrationCommandInterface;
use Migration2SQLBuilder\Tools\MigrationTrait;

class UPDATE implements MigrationCommandInterface
{
    use MigrationTrait;

    private $migration;

    public function __construct(Migration2SQLBuilder $migration)
    {
        $this->migration = $migration;
    }

    public function migrate()
    {
        
    }

    public function edit()
    {
        $this->table();
    }

    public function __destruct()
    {
        
    }


}