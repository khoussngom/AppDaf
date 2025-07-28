<?php

namespace App\Core;


class Container
{
    private array $services = [];

    public function set(string $name, callable $resolver): void
    {
        $this->services[$name] = $resolver;
    }

    public function get(string $name)
    {
        if (!isset($this->services[$name])) {
            throw new \Exception("Service non trouvé: $name");
        }

        return $this->services[$name]($this);
    }

    public function has(string $name): bool
    {
        return isset($this->services[$name]);
    }
}
