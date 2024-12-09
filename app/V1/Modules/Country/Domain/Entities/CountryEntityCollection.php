<?php

declare(strict_types=1);

namespace App\V1\Modules\Country\Domain\Entities;

use ArrayIterator;
use Countable;
use Illuminate\Support\Collection;
use IteratorAggregate;
use Traversable;

class CountryEntityCollection implements IteratorAggregate, Countable
{
    private array $items;

    public function __construct(CountryEntity ...$items)
    {
        $this->items = $items;
    }

    public function add(CountryEntity $country): self
    {
        $this->items[$country->getCode()] = $country;

        return $this;
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->items);
    }

    public function count(): int
    {
        return count($this->items);
    }

    public function get(string $code): CountryEntity
    {
        return $this->items[$code];
    }

    public function toArray(): array
    {
        return $this->items;
    }

    public function toCollection(): Collection
    {
        return new Collection($this->items);
    }
}
