<?php

namespace Crow\Faq\Helpers;

use Illuminate\Support\Collection;
use Crow\Faq\Services\FaqService;
use ReflectionMethod;

class FaqHelperHandler
{
    private Collection $methods;

    public function __construct(
        private FaqService $service
    ) {
        $this->methods = collect();
    }

    public function __call(string $methodName, array $arguments): mixed
    {
        if (!$this->methods->has($methodName)) {
            $this->methods->put(
                $methodName,
                (new ReflectionMethod($this->service, $methodName))->isPublic()
            );
        }

        if ($this->methods->get($methodName)) {
            return $this->service->$methodName(...$arguments);
        }

        return null;
    }
}
