<?php

declare(strict_types=1);

namespace Juaniquillo\BackendComponents\Concerns;

use Juaniquillo\BackendComponents\Components\DefaultContentsComponent;
use Juaniquillo\BaseBackendComponent\Concerns\HasContent as BaseHasContent;
use Juaniquillo\BaseBackendComponent\Contracts\ContentsComponent as BaseContentsComponent;

trait HasContent
{
    use BaseHasContent;

    public function processContent(): BaseContentsComponent
    {
        return new DefaultContentsComponent($this->getContents());
    }
}
