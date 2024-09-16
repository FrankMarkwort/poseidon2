<?php
declare(strict_types=1);

namespace Core\View;

use Core\Config\ConfigException;

class Json extends AbstractView
{
    /**
     * @throws ConfigException
     */
    public function present(): string
    {
        return $this->toJson();
    }

    /**
     * @throws ConfigException
     */
    private function toJson():string
    {
        $result = [];
        $fields = [];
        foreach ($this->dataFacaden as $dataFacade) {
            foreach ($dataFacade->getOrderIds() as $orderId) {
                $fields[] = [
                    'timestamp' => $dataFacade->getTimestamp(),
                    'name' => $dataFacade->getFieldValue($orderId)->getName(),
                    'type' => $dataFacade->getFieldValue($orderId)->getType(),
                    'value' => $dataFacade->getFieldValue($orderId)->getValue(),
                    'baseUnit' => $dataFacade->getFieldValue($orderId)->getUnit(),
                    'valueWithUnit' => $this->getValueWithUnit($dataFacade, $orderId),
                ];
            }
            $array = [
                'description' => $dataFacade->getDescription(),
                'pgn' => $dataFacade->getPng(),
                'fields' => $fields
            ];
            if(count($this->dataFacaden) > 1) {
                $result[] = $array;
            } else {
                $result = $array;
            }
        }
        header('Content-Type: application/json; charset=utf-8');
        return json_encode($result, JSON_PRETTY_PRINT);
    }
}