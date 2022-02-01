<?php
/**
 * @author selcukmart
 * 16.05.2021
 * 13:16
 */

namespace Migration2SQLBuilder;


use Migration2SQLBuilder\Tools\MigrationTrait;

interface MigrationCommandInterface
{
    public function __construct(Migration2SQLBuilder $migration);

    public function migrate();

    public function edit();

    public function __destruct();
}