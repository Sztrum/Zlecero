<?php

declare(strict_types=1);

use App\V1\Shared\Domain\DTO\AbstractRemoteFileInfoDTO;
use App\V1\Shared\Domain\DTO\RemoteImageInfoDTO;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

if (!function_exists('enum_rand')) {
    /**
     * @param  mixed               $enum
     * @throws ReflectionException
     */
    function enum_rand($enum)
    {
        $constants = (new ReflectionClass($enum))->getConstants();

        return $constants[array_rand($constants)];
    }
}

if (!function_exists('recurrent_extract_key_from_object')) {
    /**
     * @param  mixed               $result
     * @throws ReflectionException
     */
    function recurrent_extract_key_from_object(stdClass $data, string $search, string $childrenKey, &$result): void
    {
        if (property_exists($data, $search)) {
            $result[] = $data->{$search};
        }

        if (property_exists($data, $childrenKey)) {
            foreach ($data->{$childrenKey} as $child) {
                recurrent_extract_key_from_object($child, $search, $childrenKey, $result);
            }
        }
    }
}

if (!function_exists('remove_key_with_special_value')) {
    function remove_key_with_special_value(array $array, string $specialValueToRemove): array
    {
        foreach ($array as $key => $item) {
            if ($item === $specialValueToRemove) {
                unset($array[$key]);

                continue; // Skips further processing for this item
            }

            if (is_array($item)) {
                $array[$key] = remove_key_with_special_value($item, $specialValueToRemove);
            }
        }

        // Re-index the array only if the keys are numeric
        if (array_has_numeric_keys($array)) {
            $array = array_values($array);
        }

        return $array;
    }
}

if (!function_exists('array_has_numeric_keys')) {
    function array_has_numeric_keys(array $array): bool
    {
        foreach ($array as $key => $value) {
            if (!is_numeric($key)) {
                return false;
            }
        }

        return true;
    }
}

if (!function_exists('add_element_to_array_by_wire_key')) {
    function add_element_to_array_by_wire_key(
        array $array,
        array $chain,
        mixed $newElement,
    ): array {
        $target = &$array;

        for ($build_key = 0; $build_key < count($chain); $build_key++) { //Laravel collections ->each method not working here
            if (!isset($target[$chain[$build_key]])) {
                $target[$chain[$build_key]] = [];
            }

            $target = &$target[$chain[$build_key]];
        }

        $target[] = $newElement;

        return $array;
    }
}

if (!function_exists('remove_element_from_array_by_wire_key')) {
    function remove_element_from_array_by_wire_key(
        array $array,
        array $chain,
    ): array {
        $target = &$array;

        $beforeLast = null;

        for ($build_key = 0; $build_key < count($chain); $build_key++) { //Laravel collections ->each method not working here
            if (!isset($target[$chain[$build_key]])) {
                $target[$chain[$build_key]] = [];
            }

            if ($build_key == count($chain) - 1) {
                $beforeLast = &$target;
            }

            $target = &$target[$chain[$build_key]];
        }

        $beforeLast = array_values($beforeLast);

        $target = 'special_value_to_remove'; //temporally fix is setting "special" value and later removing key by this value

        //        return $array;

        return remove_key_with_special_value($array, 'special_value_to_remove');
    }
}


if (!function_exists('convert_name_to_wire_key')) {
    function convert_name_to_wire_key(
        string $name
    ): string {
        $output = preg_replace('/\[(.*?)\]/', '.$1', $name);

        if ($output[0] === '.') {
            $output = substr($output, 1);
        }

        return $output;
    }
}

if (!function_exists('get_remote_file_info')) {
    function get_remote_file_info(
        string $url,
    ): AbstractRemoteFileInfoDTO|RemoteImageInfoDTO|null {
        try {
            // Cache for 7 days
            return Cache::remember('http-get-file-' . Str::slug($url), 604800, static function () use ($url) {
                $response = Http::get($url);

                if ($response->ok()) {
                    $content = $response->body();
                    $size = strlen($content);
                    $contentType = $response->header('Content-Type');

                    // Determine the appropriate DTO based on the content type
                    if (str_starts_with($contentType, 'image/')) {
                        // Process image files
                        $imageManager = new ImageManager(new Driver());
                        $image = $imageManager->read($content);

                        return RemoteImageInfoDTO::from([
                            'uri' => $url,
                            'imageWidth' => $image->width(),
                            'imageHeight' => $image->height(),
                            'size' => $size,
                            'contentType' => $contentType,
                        ]);
                    } else {
                        return AbstractRemoteFileInfoDTO::from([
                            'uri' => $url,
                            'size' => $size,
                            'contentType' => $contentType,
                        ]);
                    }
                } else {
                    throw new App\V1\Core\Domain\Exceptions\DomainException('Could not fetch the file: ' . $url);
                }
            });
        } catch (Exception $e) {
            return null;
        }
    }
}

if (!function_exists('json_decode_recurrency')) {
    function json_decode_recurrency(array $array): array
    {
        foreach ($array as $key => &$item) {
            if (is_json($item)) {
                $item = json_decode($item, true);
            }

            if (is_array($item)) {
                $item = json_decode_recurrency($item);
            }
        }

        return $array;
    }
}

if (!function_exists('merge_arrays_recursively')) {
    /**
     * Merges two arrays recursively. Values from the second array will overwrite values in the first array if they exist.
     * If the values are arrays themselves, the function will recursively merge those arrays.
     *
     * @param array $base_array       the base array to which values will be merged
     * @param array $additional_array the array whose values will be merged into the base array
     *
     * @return array the merged array
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

if (!function_exists('nullify_empty_string')) {
    /**
     * Replace empty strings with nulls
     *
     * @param  string|null $value
     * @return string|null
     */
    function nullify_empty_string(
        ?string $value,
    ): ?string {
        if ($value === '') {
            return null;
        }

        return $value;
    }
}
