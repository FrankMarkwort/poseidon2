<?php
declare(strict_types=1);

namespace Core\View;

use Core\Parser\Data\DataFacadenColection;

interface ViewInterface
{
    public function __construct(DataFacadenColection $dataFacade);

    public function present(): string;
}