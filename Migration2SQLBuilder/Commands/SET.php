<?php
/**
 * @author selcukmart
 * 16.05.2021
 * 13:20
 */

namespace Migration2SQLBuilder\Commands;


use Migration2SQLBuilder\Migration2SQLBuilder;
use Migration2SQLBuilder\MigrationCommandInterface;
use Migration2SQLBuilder\Tools\MigrationTrait;

class SET implements MigrationCommandInterface
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
        $this->set();
        //c($this->migration->wait);
    }

    public function __destruct()
    {
        
    }


}