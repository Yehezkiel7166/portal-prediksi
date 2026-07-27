<?php

namespace App\Domains\DreamBook\Support;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

final class DreamBookRepository
{
    public function search(?string $query, int $page = 1): LengthAwarePaginator
    {
        $entries = $this->all();
        $needle = Str::lower(trim((string) $query));

        if ($needle !== '') {
            $entries = $entries->filter(function (array $entry) use ($needle): bool {
                $haystack = Str::lower(implode(' ', [
                    $entry['number'],
                    $entry['title'],
                    $entry['slug'],
                    $entry['interpretation'],
                    implode(' ', $entry['keywords']),
                ]));

                return str_contains($haystack, $needle);
            })->values();
        }

        $perPage = max(1, (int) config('dream-book.per_page', 12));

        return new LengthAwarePaginator(
            $entries->forPage($page, $perPage)->values(),
            $entries->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()],
        );
    }

    public function findBySlug(string $slug): ?array
    {
        return $this->all()->firstWhere('slug', $slug);
    }

    public function related(array $entry, int $limit = 4): Collection
    {
        return $this->all()
            ->reject(fn (array $candidate): bool => $candidate['slug'] === $entry['slug'])
            ->sortBy(fn (array $candidate): int => abs((int) $candidate['number'] - (int) $entry['number']))
            ->take($limit)
            ->values();
    }

    public function all(): Collection
    {
        return collect(config('dream-book.entries', []))
            ->sortBy(fn (array $entry): int => (int) $entry['number'])
            ->values();
    }
}
