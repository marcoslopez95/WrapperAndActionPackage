<?php

namespace Manu\WrapAndActionPackage;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
abstract class Wrapper extends Collection
{
    protected Collection $inputs;
    public function __construct(
        Collection|array $inputs,
        protected ?Request $request = null
    ) {
        parent::__construct($inputs);
    }

    public function setProperty(string $property, \Closure $fn): static
    {
        if (!array_key_exists($property, $this->items)) {
            $this->items[$property] = null;
        }
        $this->items[$property] = $fn($this->items[$property], $this);

        return $this;
    }

    protected function hasArray(string $input, int $count = 1): bool
    {
        return $this->has($input) && $count <= count($this->get($input));
    }

    protected function getArray(string $input, ?array $default = []): ?array
    {
        $value = $this->get($input);

        return $value ? (array) $value : $default;
    }

    protected function getBool(string $input, mixed $default = null): bool
    {
        $value = $this->get($input, $default);

        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }

    protected function getInt(string $input, mixed $default = null): ?int
    {
        $value = $this->get($input);

        return $value ? (int) $value : $default;
    }

    protected function getCollect(string $input, ?array $default = null): ?Collection
    {
        $args = func_get_args();
        $value = $this->getArray($input, $default);

        return (array_key_exists(1, $args) && null === $args[1]) && is_null($value)
            ? null
            : collect($value ?? []);
    }

    protected function getFileByName(string $input, string $name, array $allowedfileExtension): ?UploadedFile
    {
        if (!$this->request->hasFile($input)) {
            return null;
        }

        /** @var UploadedFile[] $files */
        $files = $this->request->file($input);

        foreach ($files as $file) {
            if ($name !== $file->getClientOriginalName()) {
                continue;
            }

            $extension = $file->getClientOriginalExtension();

            if (!in_array($extension, $allowedfileExtension)) {
                throw new \Exception('Invalid file type');
            }

            return $file;
        }

        return null;
    }

    protected function cast(string $input, string $class): ?object
    {
        return $this->has($input)
            ? new $class($this->get($input), $this->request)
            : null;
    }

    protected function castMany(string $input, string $class): Collection
    {
        return $this->getCollect($input)
            ->map(fn (array $input) => new $class(collect($input), $this->request));
    }
}
