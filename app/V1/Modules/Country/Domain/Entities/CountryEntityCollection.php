<?php

declare(strict_types=1);

namespace App\V1\Modules\Country\Domain\Entities;

use ArrayIterator;
use Countable;
use Illuminate\Support\Collection;
use IteratorAggregate;
use Traversable;

/**
 * @implements IteratorAggregate<string, CountryEntity>
 */
class CountryEntityCollection implements IteratorAggregate, Countable
{
    /**
     * @var array<string, CountryEntity>
     */
    private array $items;

    public function __construct(CountryEntity ...$items)
    {
        $this->items = [];

        foreach ($items as $item) {
            $this->add($item);
        }
    }

    public function add(CountryEntity $country): self
    {
        $this->items[$country->getCode()] = $country;

        return $this;
    }

    /**
     * @return Traversable<string, CountryEntity>
     */
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

    /**
     * @return array<string, CountryEntity>
     */
    public function toArray(): array
    {
        return $this->items;
    }

    /**
     * @return Collection<string, CountryEntity>
     */
    public function toCollection(): Collection
    {
        return new Collection($this->items);
    }
}
