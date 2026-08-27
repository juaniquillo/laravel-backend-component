<?php

declare(strict_types=1);

namespace Juaniquillo\BackendComponents\Cache;

use Psr\SimpleCache\InvalidArgumentException;

final class InvalidCacheArgumentException extends \RuntimeException implements InvalidArgumentException {}
