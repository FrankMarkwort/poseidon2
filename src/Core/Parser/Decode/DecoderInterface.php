<?php

declare(strict_types=1);

namespace Core\Parser\Decode;

interface DecoderInterface
{
    public static function getInstance() : DecoderInterface;

    public function setArray(array $dataArray, int $length): DecoderInterface;

    public function getValue(Request $request):float|int|string|null;
}
