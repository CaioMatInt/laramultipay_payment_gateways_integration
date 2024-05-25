<?php

namespace Tests\Traits;

trait ClassReflectionTrait
{
    public function invokeProtectedOrPrivateMethod($object, $methodName, array $args = []): mixed
    {
        $reflection = new \ReflectionClass($object);
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $args);
    }
}
