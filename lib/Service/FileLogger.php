<?

namespace Vt\Forms\Service;

use Vt\Forms\Contracts\LoggerInterface;

class FileLogger implements LoggerInterface
{
    public function log(string $message, ?array $context = null): void
    {
        throw new \Exception('Not implemented');
    }
}
