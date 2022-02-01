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

class INSERT implements MigrationCommandInterface
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
        unset($this->migration->wait[0]);
        $this->migration->wait_next_index = $this->migration->wait;
        $table = $this->migration->wait[1];
        $this->migration->wait = [];
        $this->migration->wait[] = $table;
        $this->table();


    }


    public function __destruct()
    {
        
    }


}