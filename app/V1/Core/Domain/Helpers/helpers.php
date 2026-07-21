<?php

declare(strict_types=1);

use App\V1\Shared\Domain\DTO\AbstractRemoteFileInfoDTO;
use App\V1\Shared\Domain\DTO\RemoteImageInfoDTO;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

if (! function_exists('enum_rand')) {
    /**
     * @param  class-string|object  $enum
     *
     * @throws ReflectionException
     */
    function enum_rand(string|object $enum): mixed
    {
        $constants = (new ReflectionClass($enum))->getConstants();

        return $constants[array_rand($constants)];
    }
}

if (! function_exists('recurrent_extract_key_from_object')) {
    /**
     * @param  list<mixed>  $result
     */
    function recurrent_extract_key_from_object(stdClass $data, string $search, string $childrenKey, array &$result): void
    {
        if (property_exists($data, $search)) {
            $result[] = $data->{$search};
        }

        if (! property_exists($data, $childrenKey) || ! is_iterable($data->{$childrenKey})) {
            return;
        }

        foreach ($data->{$childrenKey} as $child) {
            if ($child instanceof stdClass) {
                recurrent_extract_key_from_object($child, $search, $childrenKey, $result);
            }
        }
    }
}

if (! function_exists('remove_key_with_special_value')) {
    /**
     * @param  array<array-key, mixed>  $array
     * @return array<array-key, mixed>
     */
    function remove_key_with_special_value(array $array, string $specialValueToRemove): array
    {
        foreach ($array as $key => $item) {
            if ($item === $specialValueToRemove) {
                unset($array[$key]);

                continue;
            }

            if (is_array($item)) {
                $array[$key] = remove_key_with_special_value($item, $specialValueToRemove);
            }
        }

        if (array_has_numeric_keys($array)) {
            $array = array_values($array);
        }

        return $array;
    }
}

if (! function_exists('array_has_numeric_keys')) {
    /**
     * @param  array<array-key, mixed>  $array
     */
    function array_has_numeric_keys(array $array): bool
    {
        foreach ($array as $key => $value) {
            if (! is_numeric($key)) {
                return false;
            }
        }

        return true;
    }
}

if (! function_exists('add_element_to_array_by_wire_key')) {
    /**
     * @param  array<array-key, mixed>  $array
     * @param  list<int|string>  $chain
     * @return array<array-key, mixed>
     */
    function add_element_to_array_by_wire_key(
        array $array,
        array $chain,
        mixed $newElement,
    ): array {
        $target = &$array;

        foreach ($chain as $key) {
            if (! isset($target[$key]) || ! is_array($target[$key])) {
                $target[$key] = [];
            }

            $target = &$target[$key];
        }

        $target[] = $newElement;

        return $array;
    }
}

if (! function_exists('remove_element_from_array_by_wire_key')) {
    /**
     * @param  array<array-key, mixed>  $array
     * @param  list<int|string>  $chain
     * @return array<array-key, mixed>
     */
    function remove_element_from_array_by_wire_key(
        array $array,
        array $chain,
    ): array {
        $key = array_shift($chain);

        if ($key === null) {
            return $array;
        }

        if ($chain === []) {
            $array[$key] = 'special_value_to_remove';

            return remove_key_with_special_value($array, 'special_value_to_remove');
        }

        $child = $array[$key] ?? [];

        if (! is_array($child)) {
            $child = [];
        }

        $array[$key] = remove_element_from_array_by_wire_key($child, $chain);

        return remove_key_with_special_value($array, 'special_value_to_remove');
    }
}

if (! function_exists('convert_name_to_wire_key')) {
    function convert_name_to_wire_key(string $name): string
    {
        $output = preg_replace('/\[(.*?)]/', '.$1', $name);

        if (! is_string($output)) {
            return $name;
        }

        return ltrim($output, '.');
    }
}

if (! function_exists('get_remote_file_info')) {
    function get_remote_file_info(
        string $url,
    ): AbstractRemoteFileInfoDTO|RemoteImageInfoDTO|null {
        try {
            return Cache::remember('http-get-file-'.Str::slug($url), 604800, static function () use ($url): AbstractRemoteFileInfoDTO|RemoteImageInfoDTO|null {
                $response = Http::get($url);

                if (! $response->ok()) {
                    return null;
                }

                $content = $response->body();
                $size = strlen($content);
                $contentType = $response->header('Content-Type');

                if (str_starts_with($contentType, 'image/')) {
                    $imageManager = new ImageManager(new Driver);
                    $image = $imageManager->decodeBinary($content);

                    return RemoteImageInfoDTO::from([
                        'uri' => $url,
                        'imageWidth' => $image->width(),
                        'imageHeight' => $image->height(),
                        'size' => $size,
                        'contentType' => $contentType,
                    ]);
                }

                return AbstractRemoteFileInfoDTO::from([
                    'uri' => $url,
                    'size' => $size,
                    'contentType' => $contentType,
                ]);
            });
        } catch (Throwable) {
            return null;
        }
    }
}

if (! function_exists('is_json')) {
    function is_json(mixed $value): bool
    {
        if (! is_string($value)) {
            return false;
        }

        json_decode($value);

        return json_last_error() === JSON_ERROR_NONE;
    }
}

if (! function_exists('json_decode_recurrency')) {
    /**
     * @param  array<array-key, mixed>  $array
     * @return array<array-key, mixed>
     */
    function json_decode_recurrency(array $array): array
    {
        foreach ($array as &$item) {
            if (is_string($item) && is_json($item)) {
                $item = json_decode($item, true);
            }

            if (is_array($item)) {
                $item = json_decode_recurrency($item);
            }
        }

        return $array;
    }
}

if (! function_exists('merge_arrays_recursively')) {
    /**
     * @param  array<array-key, mixed>  $base_array
     * @param  array<array-key, mixed>  $additional_array
     * @return array<array-key, mixed>
     */
    function merge_arrays_recursively(array $base_array, array $additional_array): array
    {
        foreach ($additional_array as $key => $value) {
            if (is_array($value) && isset($base_array[$key]) && is_array($base_array[$key])) {
                $base_array[$key] = merge_arrays_recursively($base_array[$key], $value);
            } else {
                $base_array[$key] = $value;
            }
        }

        return $base_array;
    }
}

if (! function_exists('nullify_empty_string')) {
    function nullify_empty_string(?string $value): ?string
    {
        if ($value === '') {
            return null;
        }

        return $value;
    }
}
