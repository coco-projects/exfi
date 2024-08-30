<?php

    namespace Coco\exfi;

use Coco\exfi\Adapter\Exiftool;
use Coco\exfi\Adapter\Native;

class Reader
{
    public static function readExfi($path): array
    {
        if (strtoupper(PHP_OS) !== 'LINUX') {
            throw new \RuntimeException('工具只能在 Linux 操作系统上执行。');
        }

        $exiftool = new Exiftool($path);
        $native   = new Native($path);

        $result = [];

        foreach ($exiftool->getUnitizedData() as $k => $v) {
            $result[$k] = $v;
        }

        foreach ($native->getUnitizedData() as $k => $v) {
            if (!isset($result[$k]) || !$v['value']) {
                $result[$k] = $v;
            }
        }

        return $result;
    }
}
