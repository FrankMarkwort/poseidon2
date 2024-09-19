<?php

declare(strict_types=1);

namespace Core\Parser\Decode;

use Core\Parser\ParserException;

interface DecoderInterface
{
    public static function getInstance() : DecoderInterface;

    /**
     * @throws ParserException
     */
    public function setArray(array $dataArray, int $length): DecoderInterface;

    public function getValue(Request $request):float|int|string|null;
}
