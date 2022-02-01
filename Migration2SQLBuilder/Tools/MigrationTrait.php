<?php
/**
 * @author selcukmart
 * 16.05.2021
 * 13:04
 */

namespace Migration2SQLBuilder\Tools;


trait MigrationTrait
{

    /**
     * @author selcukmart
     * 16.05.2021
     * 16:38
     */
    private function set()
    {
        $export = [];
        $add_value = false;
        $previous_token = '';
        foreach ($this->migration->wait as $item) {

            if ($item === ',') {
                continue;
            }

            if (!$add_value && $item !== '=') {
                $previous_token .= $item;
            } else {
                if ($item === '=') {
                    $add_value = true;
                    continue;
                }
                $export[$previous_token] = $item;
                $previous_token = '';
                $add_value = false;
            }

        }

        $this->migration->export[$this->migration->indis][] = $export;
        $this->migration->wait = [];
        $this->migration->will_wait = true;
    }

    /**
     * INSERT INTO abc (column1, column2, column3)
     * VALUES (value1, value2, value3)
     * @author selcukmart
     * 16.05.2021
     * 16:19
     */
    private function values()
    {
        $this->migration->export[$this->migration->indis] = [];
        $this->migration->export[$this->migration->indis]['type'] = 'SET';
        unset($this->migration->wait_next_index[1]);
        $export_columns = [];
        foreach ($this->migration->wait_next_index as $index => $item) {
            unset($this->migration->wait_next_index[$index]);
            if ($item == '(' || $item == ',') {
                continue;
            }
            if ($item == ')') {
                break;
            }
            $export_columns [] = $item;
        }

        $export_values = [];
        foreach ($this->migration->wait as $index => $item) {
            unset($this->migration->wait[$index]);
            if ($item == 'VALUES' || $item == '(' || $item == ',') {
                continue;
            }
            if ($item == ')') {
                break;
            }
            $export_values [] = $item;
        }
        $export = [];
        foreach ($export_columns as $index => $export_column) {
            $export[$export_column] = $export_values[$index];
        }
        $this->migration->export[$this->migration->indis][] = $export;
        $this->migration->wait = [];
        $this->migration->will_wait = true;
    }

    private function limit()
    {
        $str = '';
        $i = 0;

        foreach ($this->migration->wait as $index => $item) {
            $i++;
            $str .= $item;
            if (isset($this->migration->wait[$index + 1]) && $this->migration->wait[$index + 1] != ',') {
                $str .= ' ';
            }
        }
        $str = str_replace(' . ', '.', $str);

        if (!isset($this->migration->export[$this->migration->indis][0])) {
            $this->migration->export[$this->migration->indis][0] = '';
        }
        $this->migration->export[$this->migration->indis][0] .= $str . '';

        $this->migration->wait = [];
        $this->migration->will_wait = true;
        return $this->migration->export[$this->migration->indis];
    }

    private function where()
    {
        $str = '';

        foreach ($this->migration->wait as $item) {

            $str .= $item;
            $str .= ' ';

        }
        $str = str_replace(' . ', '.', $str);

        if (!isset($this->migration->export[$this->migration->indis]['WHERE'])) {
            $this->migration->export[$this->migration->indis]['WHERE'] = ' ';
        }
        $this->migration->export[$this->migration->indis]['WHERE'] .= $str . '';

        $this->migration->wait = [];
        $this->migration->will_wait = true;
        return $this->migration->export[$this->migration->indis];
    }

    private function condition()
    {
        unset($this->migration->export[$this->migration->indis]);
        $this->migration->indis--;

        $str = '';
        $i = 0;

        foreach ($this->migration->wait as  $item) {
            $str .= $item;
            $str .= ' ';

        }
        $str = str_replace(' . ', '.', $str);

        if (!isset($this->migration->export[$this->migration->indis]['WHERE'])) {
            $this->migration->export[$this->migration->indis]['WHERE'] = ' ';
        }
        $this->migration->export[$this->migration->indis]['WHERE'] .= $str . '';

        $this->migration->wait = [];
        $this->migration->will_wait = true;
        return $this->migration->export[$this->migration->indis];
    }

    private function select()
    {
        $str = '';
        $i = 0;

        foreach ($this->migration->wait as $index => $item) {
            $i++;
            $str .= $item;
            if ($i >= 3) {
                $str .= ' ';
            }
        }

        $this->migration->export[$this->migration->indis][] = $str;
        $this->migration->wait = [];
        $this->migration->will_wait = true;
        return $this->migration->export[$this->migration->indis];
    }

    private function from()
    {
        foreach ($this->migration->wait as $index => $item) {
            $this->migration->export[$this->migration->indis][] = $item;
        }

        $this->migration->wait = [];
        $this->migration->will_wait = true;
        return $this->migration->export[$this->migration->indis];
    }

    private function table()
    {
        foreach ($this->migration->wait as $index => $item) {
            $this->migration->export[$this->migration->indis]['table'][] = $item;
        }

        $this->migration->wait = [];
        $this->migration->will_wait = true;
        return $this->migration->export[$this->migration->indis];
    }

    private function on()
    {

        unset($this->migration->export[$this->migration->indis]);
        $this->migration->indis--;

        $previous_item = '';
        foreach ($this->migration->wait as $index => $item) {
            if ($index == 0 || $index == 4) {
                $previous_item = $item;
            } elseif ($index == 2 || $index == 6) {
                $this->migration->export[$this->migration->indis]['ON'][$previous_item] = $item;
            }
        }
        $this->migration->will_wait = true;
        $this->migration->wait = [];
        return $this->migration->export[$this->migration->indis];
    }

    private function taxonomy($the_last_array)
    {
        $export = [];
        $export['type'] = $the_last_array['type'];
        unset($the_last_array['type']);
        foreach ($the_last_array as  $item) {
            if (false !== strpos($item, ".")) {
                $x = explode('.', $item);
                $export[$x[0]][] = $x[1];
            } else {
                $export[] = $item;
            }
        }
        return $export;
    }
}