<?php
/**
 * @author selcukmart
 * 15.05.2021
 * 18:52
 */

namespace Migration2SQLBuilder;


use PhpMyAdmin\SqlParser\Parser;
use Migration2SQLBuilder\Tools\FileOperations;
use SelcukMart\Tools\SQLVariablesTrait;

class Migration2SQLBuilder
{
    use SQLVariablesTrait;
    use FileOperations;

    private
        $sql_file_lists,
        $modified = false,
        $not_token = [
        'AS',
        'IN',
        'DESC',
        'ASC',
        'NOT',
        'OFFSET',
        'INTO',
        'CONCAT',
        'concat',
        'VERSION',
        'CASE',
        'WHEN',
        'THEN',
        'END',
        'SUM',
        'COUNT',
        'CHAR_LENGTH'
//            'AND',
//            'OR',
//            '||',
//            'XOR'
    ];

    public function __construct()
    {
    }

    private function sqlModify($sql)
    {
        if ($this->modified) {
            return $sql;
        }
        $this->modified = true;
        $sql = trim(preg_replace('/\t+/', '', $sql));
        return preg_replace('/\s+/', ' ', $sql);
    }

    public function sqlBuilder($sql)
    {
        $this->sqlModify($sql);

        $this->modified = false;

        $parser = new Parser($sql);

        $this->export = [];
        $this->indis = 0;
        $this->wait = [];
        $this->will_wait = true;
        //c($parser->list->tokens);

        $comma_separateds = [
            'SELECT',
            'GROUPBY',
            'ORDERBY'
        ];
        $this->namespace = __NAMESPACE__ . '\Commands\\';
        $this->current_token = '';

        foreach ($parser->list->tokens as $this->token) {
            if ($this->token->type == 1 && !in_array($this->token->value, $this->not_token, true)) {
                if (count($this->wait) > 0 && !empty($this->current_token)) {
                    $class = $this->namespace . $this->command;
                    $command_obje = new $class($this);
                    $command_obje->edit();
                }

                $this->indis++;
                $this->export[$this->indis] = [
                    'type' => $this->token->value
                ];

                $this->current_token = strtoupper($this->token->value);
                $this->command = $this->commandPrepare($this->token->value);
                if ($this->command === 'CONDITION') {
                    $this->wait[] = $this->token->value;
                }
                if (false !== strpos($this->command, "JOIN")) {
                    $this->command = 'JOIN';
                }

                $this->will_wait = true;

            } else {
                if (empty($this->current_token)) {
                    continue;
                }
                $this->token->value = preg_replace('/\s+/', '', $this->token->value);

                if ($this->token->value == '') {
                    continue;
                }

                if (preg_match('/\$vtable/', $this->token->value)) {
                    $this->token->value = str_replace('.', '', $this->token->value);
                    $this->wait[] = $this->token->value;
                    $this->will_wait = true;
                } else {

                    if (in_array($this->command, $comma_separateds, true) && preg_match('/,/', $this->token->value)) {
                        $this->will_wait = false;
                    }

                    if ($this->will_wait) {
                        $this->wait[] = $this->token->value;
                    } elseif (in_array($this->command, $comma_separateds, true) && count($this->wait)) {
                        $class = $this->namespace . $this->command;
                        $command_obje = new $class($this);
                        $command_obje->migrate();

                    }
                }
            }
        }
        if (!empty($this->current_token)) {

            $class = $this->namespace . $this->command;
            $command_obje = new $class($this);
            $command_obje->edit();
            return $this->export;
        }
    }

    public function __destruct()
    {

    }
}