<?php
/**
 * @author Roemer(https://stackoverflow.com/users/2321785/roemer), edited: selcukmart
 * 19.05.2021
 * 12:34
 */

namespace Migration2SQLBuilder\Tools;


class SQLValidator
{
    private
        $sql,
        $result = true,
        $error_messages = '';


    public function __construct($sql)
    {
        $this->sql = $sql;
    }

    public function checkMySqlSyntax()
    {
        global $dbh, $sth;
        $query = $this->sql;
        if (trim($query)) {
            // Replace characters within string literals that may *** up the process
            $query = $this->replaceCharacterWithinQuotes($query, '#', '%');
            $query = $this->replaceCharacterWithinQuotes($query, ';', ':');
            // Prepare the query to make a valid EXPLAIN query
            // Remove comments # comment ; or  # comment newline
            // Remove SET @var=val;
            // Remove empty statements
            // Remove last ;
            // Put EXPLAIN in front of every MySQL statement (separated by ;)
            $query = 'EXPLAIN ' .
                preg_replace(["/#[^\n\r;]*([\n\r;]|$)/",
                    '/[Ss][Ee][Tt]\s+@\w+\s*:?=\s*[^;]+(;|$)/',
                    '/;\s*;/',
                    '/;\s*$/',
                    '/;/'],
                    ['', '', ';', '', '; EXPLAIN '], $query);

            foreach (explode(';', $query) as $q) {
                $sth = $dbh->prepare($q);
                try {
                    $sth->execute();
                } catch (\PDOException $Exception) {

                    $this->setErrorMessages($Exception->getMessage().', Code: '.$Exception->getCode());
                    break;

                }
            }
            return $this->result;
        }
        $this->setErrorMessages('SQL boş geldi');
        return $this->result;
    }

    /**
     * @return bool
     */
    public function isResult(): bool
    {
        return $this->result;
    }

    /**
     * @param string $error_messages
     */
    private function setErrorMessages(string $error_messages): void
    {
        $this->result = false;
        $this->error_messages .= $error_messages;
    }

    /**
     * @return string
     */
    public function getErrorMessages(): string
    {
        return $this->error_messages;
    }

    private function replaceCharacterWithinQuotes($str, $char, $repl)
    {
        if (strpos($str, $char) === false) return $str;

        $placeholder = chr(7);
        $inSingleQuote = false;
        $inDoubleQuotes = false;
        for ($p = 0; $p < strlen($str); $p++) {
            switch ($str[$p]) {
                case "'":
                    if (!$inDoubleQuotes) $inSingleQuote = !$inSingleQuote;
                    break;
                case '"':
                    if (!$inSingleQuote) $inDoubleQuotes = !$inDoubleQuotes;
                    break;
                case '\\':
                    $p++;
                    break;
                case $char:
                    if ($inSingleQuote || $inDoubleQuotes) $str[$p] = $placeholder;
                    break;
            }
        }
        return str_replace($placeholder, $repl, $str);
    }

    public function __destruct()
    {
        // TODO: Implement __destruct() method.
    }
}