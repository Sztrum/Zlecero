<?php

declare(strict_types=1);

namespace App\V1\Core\Infrastructure\Packages\Vite\Domain\Abstracts;

use Illuminate\Foundation\Vite;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use RuntimeException;

/**
 * @phpstan-type ViteChunk array{file: string, src?: string, imports?: list<string>, css?: list<string>}
 * @phpstan-type ViteManifest array<string, ViteChunk>
 * @phpstan-type PreloadArguments array{0: string, 1: string, 2: ViteChunk, 3: ViteManifest}
 */
class AbstractVite extends Vite
{
    public function __invoke($entrypoints, $buildDirectory = null)
    {
        $this->prepareHotFilePath($buildDirectory);

        $entrypointList = $this->entrypointList($entrypoints);
        $currentBuildDirectory = $buildDirectory ?? $this->buildDirectory;

        if ($this->isRunningHot()) {
            $hotTags = [];

            foreach (array_merge(['@vite/client'], $entrypointList) as $entrypoint) {
                $hotTags[] = $this->makeTagForChunk($entrypoint, $this->hotAsset($entrypoint), null, null);
            }

            return new HtmlString(implode('', array_unique($hotTags)));
        }

        $manifest = $this->typedManifest($currentBuildDirectory);
        $tags = [];
        $preloads = [];

        foreach ($entrypointList as $entrypoint) {
            $chunk = $this->typedChunk($manifest, $entrypoint);
            $chunkFile = $chunk['file'];

            $preloads[] = [
                $chunk['src'] ?? $entrypoint,
                $this->assetPath("{$currentBuildDirectory}/{$chunkFile}"),
                $chunk,
                $manifest,
            ];

            foreach ($chunk['imports'] ?? [] as $import) {
                $importChunk = $this->typedChunk($manifest, $import);
                $importFile = $importChunk['file'];

                $preloads[] = [
                    $import,
                    $this->assetPath("{$currentBuildDirectory}/{$importFile}"),
                    $importChunk,
                    $manifest,
                ];

                foreach ($importChunk['css'] ?? [] as $css) {
                    $this->pushCssChunk($css, $currentBuildDirectory, $manifest, $preloads, $tags);
                }
            }

            $tags[] = $this->makeTagForChunk(
                $entrypoint,
                $this->assetPath("{$currentBuildDirectory}/{$chunkFile}"),
                $chunk,
                $manifest
            );

            foreach ($chunk['css'] ?? [] as $css) {
                $this->pushCssChunk($css, $currentBuildDirectory, $manifest, $preloads, $tags);
            }
        }

        $stylesheets = [];
        $scripts = [];

        foreach (array_unique($tags) as $tag) {
            if (str_starts_with($tag, '<link')) {
                $stylesheets[] = $tag;
            } else {
                $scripts[] = $tag;
            }
        }

        $preloads = $this->uniquePreloads($preloads);
        usort($preloads, fn (array $left, array $right): int => $this->preloadSortValue($right) <=> $this->preloadSortValue($left));

        $preloadTags = [];

        foreach ($preloads as $preload) {
            $preloadTags[] = $this->makePreloadTagForChunk($preload[0], $preload[1], $preload[2], $preload[3]);
        }

        return new HtmlString(implode('', $preloadTags) . implode('', $stylesheets) . implode('', $scripts));
    }

    private function prepareHotFilePath(?string $buildDirectory): void
    {
        if ($buildDirectory) {
            $this->hotFile = public_path('/hot-' . Str::slug($buildDirectory));
        }
    }

    /**
     * @return list<string>
     */
    private function entrypointList(mixed $entrypoints): array
    {
        if (is_string($entrypoints)) {
            return [$entrypoints];
        }

        if (!is_array($entrypoints)) {
            throw new RuntimeException('Vite entrypoints must be a string or an array of strings.');
        }

        $entrypointList = [];

        foreach ($entrypoints as $entrypoint) {
            if (!is_string($entrypoint)) {
                throw new RuntimeException('Each Vite entrypoint must be a string.');
            }

            $entrypointList[] = $entrypoint;
        }

        return $entrypointList;
    }

    /**
     * @return ViteManifest
     */
    private function typedManifest(string $buildDirectory): array
    {
        return $this->normaliseManifest($this->manifest($buildDirectory));
    }

    /**
     * @param ViteManifest $manifest
     * @return ViteChunk
     */
    private function typedChunk(array $manifest, string $entrypoint): array
    {
        if (!isset($manifest[$entrypoint])) {
            throw new RuntimeException("Unable to locate Vite chunk for [{$entrypoint}].");
        }

        return $manifest[$entrypoint];
    }

    /**
     * @param array<array-key, mixed> $manifest
     * @return ViteManifest
     */
    private function normaliseManifest(array $manifest): array
    {
        $typedManifest = [];

        foreach ($manifest as $key => $chunk) {
            if (!is_string($key) || !is_array($chunk)) {
                throw new RuntimeException('Vite manifest entries must be keyed arrays.');
            }

            $typedManifest[$key] = $this->normaliseChunk($chunk);
        }

        return $typedManifest;
    }

    /**
     * @param array<array-key, mixed> $chunk
     * @return ViteChunk
     */
    private function normaliseChunk(array $chunk): array
    {
        if (!isset($chunk['file']) || !is_string($chunk['file'])) {
            throw new RuntimeException('Vite manifest chunk must contain a file string.');
        }

        $typedChunk = ['file' => $chunk['file']];

        if (isset($chunk['src']) && is_string($chunk['src'])) {
            $typedChunk['src'] = $chunk['src'];
        }

        $imports = $this->stringList($chunk['imports'] ?? []);

        if ($imports !== []) {
            $typedChunk['imports'] = $imports;
        }

        $css = $this->stringList($chunk['css'] ?? []);

        if ($css !== []) {
            $typedChunk['css'] = $css;
        }

        return $typedChunk;
    }

    /**
     * @return list<string>
     */
    private function stringList(mixed $value): array
    {
        if (!is_array($value)) {
            return [];
        }

        $items = [];

        foreach ($value as $item) {
            if (!is_string($item)) {
                throw new RuntimeException('Vite manifest list values must be strings.');
            }

            $items[] = $item;
        }

        return $items;
    }

    /**
     * @param ViteManifest $manifest
     * @param list<PreloadArguments> $preloads
     * @param list<string> $tags
     */
    private function pushCssChunk(
        string $css,
        string $buildDirectory,
        array $manifest,
        array &$preloads,
        array &$tags
    ): void {
        $cssEntry = $this->findManifestEntryByFile($manifest, $css);

        $preloads[] = [
            $cssEntry,
            $this->assetPath("{$buildDirectory}/{$css}"),
            $manifest[$cssEntry],
            $manifest,
        ];

        $tags[] = $this->makeTagForChunk(
            $cssEntry,
            $this->assetPath("{$buildDirectory}/{$css}"),
            $manifest[$cssEntry],
            $manifest
        );
    }

    /**
     * @param ViteManifest $manifest
     */
    private function findManifestEntryByFile(array $manifest, string $file): string
    {
        foreach ($manifest as $entrypoint => $chunk) {
            if ($chunk['file'] === $file) {
                return $entrypoint;
            }
        }

        throw new RuntimeException("Unable to locate Vite CSS chunk for [{$file}].");
    }

    /**
     * @param list<PreloadArguments> $preloads
     * @return list<PreloadArguments>
     */
    private function uniquePreloads(array $preloads): array
    {
        $unique = [];
        $seen = [];

        foreach ($preloads as $preload) {
            $key = serialize($preload);

            if (isset($seen[$key])) {
                continue;
            }

            $seen[$key] = true;
            $unique[] = $preload;
        }

        return $unique;
    }

    /**
     * @param PreloadArguments $preload
     */
    private function preloadSortValue(array $preload): int
    {
        return $this->isCssPath($preload[1]) ? 1 : 0;
    }
}
