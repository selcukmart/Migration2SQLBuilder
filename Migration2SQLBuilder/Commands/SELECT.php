<?php
/**
 * @author selcukmart
 * 16.05.2021
 * 13:04
 */

namespace Migration2SQLBuilder\Commands;


use Migration2SQLBuilder\Migration2SQLBuilder;
use Migration2SQLBuilder\MigrationCommandInterface;
use Migration2SQLBuilder\Tools\MigrationTrait;

class SELECT implements MigrationCommandInterface
{
    use MigrationTrait;

    private $migration;

    public function __construct(Migration2SQLBuilder $migration)
    {
        $this->migration = $migration;
    }

    public function migrate()
    {
        $this->select();
    }

    public function edit()
    {
        $the_last_array = $this->select();
        $this->migration->export[$this->migration->indis] = $this->taxonomy($the_last_array);
    }

    public function __destruct()
    {
    }
}