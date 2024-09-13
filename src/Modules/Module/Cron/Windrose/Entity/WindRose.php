<?php
declare(strict_types=1);

namespace Modules\Module\Cron\Windrose\Entity;

class WindRose
{
    private array $data = [];
    public function add(string $segment, array $data):self
    {
        $this->data[$segment] = $data;

        return $this;
    }

    public function count():int
    {
        $count = 0;
        foreach ($this->toArray() as $segment => $data) {
            foreach ($data as $item) {
                $count += $item['count'];
            }
        }

        return $count;
    }

    public function toArray():array
    {
        return $this->data;
    }

    private function toProcent(int $count):float
    {
        return round(($count * 100) / $this->count(),1);
    }

    public function getTableData():string
    {
        $html = '';
        $cols = '';
        $i = 0;
        $totals = array_fill(0, 12, 0);
        foreach ($this->toArray() as $segment => $data) {
            $cols .= $this->td('class="dir"', $segment);
            foreach ($data as $key => $value) {
                $cols .= $this->td('class="data"', (string)$this->toProcent($value['count']));
                $totals[$key] += $value['count'];
            }
            $i++;
            if ($i === 1) {
                $attrib = 'nowrap' ;
            } else {
                $attrib = 'nowrap bgcolor="#DDDDDD"' ;
                $i = 0;
            }
            $html .= $this->tr($attrib, $cols);
            $cols = '';
        }

        array_walk($totals, function (&$value, $key) {
            $value = $this->toProcent($value);
        });

        $cols = $this->td('class="totals"', 'Total');
        foreach ($totals as $key => $value) {
            $cols .= $this->td('class="totals"', $value)  ;
        }

        $html .= $this->tr('nowrap', $cols);

        return $html;
    }

    public function tr(string $attribute, string $row):string
    {
        return sprintf('<tr %s>%s</tr>', $attribute ,$row);
    }

    public function th(string $attribute, string $col):string
    {
        return sprintf('<th %s>%s</th>',$attribute, $col);
    }

    public function td(string $attribute, string|int|float $col):string
    {
        return sprintf('<td %s>%s</td>', $attribute, $col);
    }

    public function table(string $rows):string
    {
        return sprintf('<table id="freq" border="0" cellspacing="0" cellpadding="0"><tr nowrap bgcolor="#CCCCFF"><th colspan="9" class="hdr">Table of Frequencies (percent)</th></tr>%s</table>', $rows);
    }
}