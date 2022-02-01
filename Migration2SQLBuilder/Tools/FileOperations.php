<?php
/**
 * @author selcukmart
 * 16.05.2021
 * 13:32
 */

namespace Migration2SQLBuilder\Tools;


use Brick\VarExporter\VarExporter;

trait FileOperations
{


    public function scan()
    {
        $dir = '';
        $lists = scandir($dir);

        $sql_file_lists = [];

        foreach ($lists as $list) {
            if ($list !== '.' && $list !== '..') {
                $list = $dir . '/' . $list;
                if (is_dir($list)) {
                    $files = scandir($list);
                    foreach ($files as $file) {
                        if ($file !== '.' && $file !== '..') {
                            $file = $list . '/' . $file;
                            if (is_file($file)) {
                                $sql_file = $list . '/sql.php';
                                if (file_exists($sql_file)) {
                                    if (!in_array($sql_file, $sql_file_lists, true)) {
                                        $sql_file_lists[] = $sql_file;
                                    }
                                } else {
                                    $index_file = $list . '/index.php';
                                    if (file_exists($index_file) && !in_array($index_file, $sql_file_lists, true)) {
                                        $sql_file_lists[] = $index_file;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        $this->sql_file_lists = $sql_file_lists;
    }

    public function extractSQL()
    {
        $this->scan();
        foreach ($this->sql_file_lists as $sql_file_list) {
            $add = false;


            $handle = fopen($sql_file_list, 'rb');
            $sql = '';
            while (($line = fgets($handle)) !== false) {
                if (preg_match('/\$ana_sql/', $line)) {
                    $sql .= $line;
                    $add = true;
                } elseif ($add) {
                    $sql .= $line;
                    if (preg_match('/;/', $line)) {
                        break;
                    }

                }
            }

            $x = explode(';', $sql);
            $sql = $x[0];

            $xplode = explode('=', $sql);
            unset($xplode[0]);
            $sql = implode('=', $xplode);

            $sql = $this->sqlModify($sql);

            $sql = ltrim($sql, "\"");
            $sql = ltrim($sql, "'");
            $sql = rtrim($sql, ";");

            $result = $this->sqlBuilder($sql);

            if ($result) {
                $this->export2File($this->getSQLGeneratorFile($sql_file_list), $result);
            }
        }
    }

    /**
     * @throws \Brick\VarExporter\ExportException
     */
    private function export2File($sql_file_list, $arr)
    {
        $sql_generator_file = $this->getSQLGeneratorFile($sql_file_list);
        $this->export = str_replace("'", '"', VarExporter::export($arr));
        file_put_contents($sql_generator_file, '<?php $ana_sql = ' . $this->export . ';');
    }

    private function getSQLGeneratorFile($file)
    {
        $x = explode('/', $file);
        unset($x[count($x) - 1]);
        $xfile = implode('/', $x);
        return $xfile . '/sql-generator.php';
    }

    private function var_export_bogdaan($expression, $return = false)
    {
        $export = var_export($expression, true);
        $export = preg_replace("/^([ ]*)(.*)/m", '$1$1$2', $export);
        $array = preg_split("/\r\n|\n|\r/", $export);
        $array = preg_replace(["/\s*array\s\($/", "/\)(,)?$/", "/\s=>\s$/"], [null, ']$1', ' => ['], $array);
        $export = implode(PHP_EOL, array_filter(["["] + $array));
        if ($return) {
            return $export;
        }

        echo $export;
    }
}