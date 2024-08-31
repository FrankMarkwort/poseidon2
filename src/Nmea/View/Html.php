<?php
declare(strict_types=1);

namespace Nmea\View;

use Exception;

class Html extends AbstractView
{
    public function present(): string
    {
        return  $this->getHead(null)
            . $this->htmlRows()
            . $this->getEndHtml();
    }

    private function htmlRows(): string
    {
        $html = '';
        foreach ($this->dataFacaden as $dataFacade) {
            try {
                $html .= $this->tableRowMainHead(
                    (string)$dataFacade->getPng(),
                    $dataFacade->getFrameType(),
                    $dataFacade->getDescription()
                );
                $html .= $this->tableRowHead('orderId => Name', 'Type', 'Value');
                foreach ($dataFacade->getOrderIds() as $orderId) {
                    $valueWithUnit = $this->getValueWithUnit($dataFacade, $orderId);
                    if ($dataFacade->getFieldValue($orderId)->getValue() !== null) {
                        $html .= $this->tableRow(
                            $orderId,
                            $dataFacade->getFieldValue($orderId)->getName(),
                            $dataFacade->getFieldValue($orderId)->getType(),
                            $valueWithUnit
                        );
                    }
                }
            } catch (Exception $e) {
                $a = 1;
            }
        }

        return $html;
    }

    private function tableRow(int $id, string $name, $type, $value): string
    {
        return "<tr><td>$id => $name</td><td>$type</td><td>$value</td></tr>";
    }

    private function tableRowHead(string $name, $type, $value): string
    {
        return "<tr align='left'><th>$name</th><th>$type</th><th>$value</th></th></tr>";
    }

    private function tableRowMainHead(string $pgn, $frameType, $descripion): string
    {
        return "<tr align='left' border-bottom='1pt solid black'><th><a href='index.phtml'> <=</th><th><a href='index.phtml?pgn=$pgn'>$pgn</a> $frameType</th><th colspan='2'>$descripion</th></tr>";
    }

    private function getHead(int|null $refresh):string
    {
        $result = '<!DOCTYPE html><head><title>nmea</title>';
        if ($refresh !== null) {
            $result .= '<meta http-equiv="refresh" content="'.$refresh.'" />';
        }
        $result .= '</head><body><table>';
        return $result;
    }
    private function getEndHtml():string
    {
        return '</table></body></html>';
    }
}