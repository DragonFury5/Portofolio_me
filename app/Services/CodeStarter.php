<?php

namespace App\Services;

class CodeStarter
{
    public const LIMIT_BYTES = 300_000;

    /**
     * @return array<string, array<string, string>>
     */
    public function all(): array
    {
        return [
            'blank' => [
                'key' => 'blank',
                'name' => 'Blank canvas',
                'tagline' => 'A clean page with three project rows. Nothing to delete.',
                'level' => 'Start here',
                'html' => $this->read('blank', 'index.html'),
                'css' => $this->read('blank', 'styles.css'),
                'js' => $this->read('blank', 'script.js'),
            ],
            'editorial' => [
                'key' => 'editorial',
                'name' => 'Editorial',
                'tagline' => 'A serif one-pager with scroll reveals and large plates.',
                'level' => 'A little structure',
                'html' => $this->read('editorial', 'index.html'),
                'css' => $this->read('editorial', 'styles.css'),
                'js' => $this->read('editorial', 'script.js'),
            ],
            'kinetic' => [
                'key' => 'kinetic',
                'name' => 'Kinetic type',
                'tagline' => 'A moving masthead, a pointer-following orb, a project ledger.',
                'level' => 'Full motion',
                'html' => $this->read('kinetic', 'index.html'),
                'css' => $this->read('kinetic', 'styles.css'),
                'js' => $this->read('kinetic', 'script.js'),
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function find(?string $key): array
    {
        $all = $this->all();

        return $all[$key] ?? $all['blank'];
    }

    public function exists(?string $key): bool
    {
        return is_string($key) && array_key_exists($key, $this->all());
    }

    private function read(string $starter, string $file): string
    {
        $path = resource_path("starters/{$starter}/{$file}");

        return is_file($path) ? (string) file_get_contents($path) : '';
    }
}
