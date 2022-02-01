<?php
/**
 * @author selcukmart
 * 19.05.2021
 * 12:30
 */

namespace Migration2SQLBuilder\Form;


use Brick\VarExporter\ExportException;
use Brick\VarExporter\VarExporter;
use Migration2SQLBuilder\Tools\SQLValidator;
use SqlFormatter;
use Migration2SQLBuilder\Migration2SQLBuilder;

class Migration2SQLBuilderForm
{
    private
        $post,
        $content,
        $error_messages,
        $result = true,
        $output,
        $validation = true,
        $modified = false;

    public function __construct(array $post)
    {
        $this->post = $post;
        $this->content = $post['content'];
        if (empty($this->content)) {
            $this->error_messages = ('Boş form göndermeyin');
        } else {
            $this->modifyContent();
        }
    }

    private function modifyContent()
    {
        if ($this->modified) {
            return $this->content;
        }
        $this->modified = true;
        $this->content = trim(preg_replace('/\t+/', '', $this->content));
        $this->content = preg_replace('/\s+/', ' ', $this->content);
        $search = [
            "' . ",
            " . '",
            ' . "',
            '" . ',
            "'. ",
            " .'",
            ' ."',
            '". ',
            '"'
        ];
        $this->content = str_replace($search, '', $this->content);
    }

    private function validate()
    {
        if ($this->validation) {
            $sql_validation = new SQLValidator($this->content);
            if (!$sql_validation->checkMySqlSyntax()) {
                $this->setErrorMessages(('SQL syntax geçersizdir, Hata Detayları: ' . $sql_validation->getErrorMessages()));
            }
        }
    }

    public function migrate()
    {
        if ($this->isResult()) {
            if (!empty($this->output)) {
                return $this->output;
            }
            $migration = new Migration2SQLBuilder();
            $this->output = $migration->sqlBuilder($this->content);
            return $this->output;
        }
    }

    /**
     * @return mixed
     */
    public function getOutput()
    {
        return $this->migrate();
    }

    /**
     * @throws ExportException
     */
    public function export()
    {
        if (!$this->getOutput()) {
            return 'Geçersiz Format';
        }
        return '
$sql_array=' . VarExporter::export($this->getOutput()) . '; 
$sql_builder = new \SelcukMart\SQLBuilder();        
$sql_builder->build($sql_array);
$sql_built = $sql_builder->getOutput();';
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

    /**
     * @return bool
     */
    public function isResult(): bool
    {
        return $this->result;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    public function getContentFormatted()
    {
        return SqlFormatter::format($this->content);
    }

    /**
     * @param bool $validation
     */
    public function setValidation(bool $validation): void
    {
        $this->validation = $validation;
    }

    public function __destruct()
    {
        
    }
}