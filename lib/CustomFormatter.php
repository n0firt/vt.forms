<?php

namespace Vt\Forms;

use Bitrix\Main\Diag\LogFormatterInterface;

class CustomFormatter implements LogFormatterInterface
{
    public function format($message, array $context = []): string
    {
        $date = date('Y-m-d H:i:s');
        $contextStr = !empty($context) ? "\nContext: " . json_encode($context, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) : "";

        return "[{$date}] {$message}{$contextStr}\n-------------------\n";
    }
}
