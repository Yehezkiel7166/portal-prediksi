<?php

namespace App\Domains\DreamBook\Support;

use App\Domains\DreamBook\Models\DreamBookEntry;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Throwable;

final class DreamBookRepository
{
    public function search(?string $query, int $page = 1): LengthAwarePaginator
    {
        $entries = $this->all();
        $needle = Str::lower(trim((string) $query));

        if ($needle !== '') {
            $entries = $entries->filter(function (array $entry) use ($needle): bool {
                return str_contains(Str::lower(implode(' ', [
                    $entry['number'],
                    $entry['title'],
                    $entry['slug'],
                    $entry['interpretation'],
                    implode(' ', $entry['keywords']),
                ])), $needle);
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
            ->reject(fn (array $item): bool => $item['slug'] === $entry['slug'])
            ->sortBy(fn (array $item): int => abs((int) $item['number'] - (int) $entry['number']))
            ->take($limit)
            ->values();
    }

    public function all(): Collection
    {
        try {
            if (Schema::hasTable('dream_book_entries')) {
                $entries = DreamBookEntry::query()
                    ->active()
                    ->ordered()
                    ->get()
                    ->map(fn (DreamBookEntry $entry): array => [
                        'number' => $entry->number,
                        'slug' => $entry->slug,
                        'title' => $entry->title,
                        'keywords' => $entry->keywords ?? [],
                        'interpretation' => $entry->interpretation,
                    ]);

                if ($entries->isNotEmpty()) {
                    return $entries;
                }
            }
        } catch (Throwable) {
        }

        return collect(config('dream-book.entries', []))
            ->sortBy(fn (array $entry): int => (int) $entry['number'])
            ->values();
    }
}
