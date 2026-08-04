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
    public function search(
        ?string $query,
        int $page = 1,
        ?string $category = null,
    ): LengthAwarePaginator {
        $entries = $this->all();
        $needle = Str::lower(trim((string) $query));
        $category = Str::upper(trim((string) $category));

        if (in_array($category, ['2D', '3D', '4D'], true)) {
            $entries = $entries
                ->where('category', $category)
                ->values();
        }

        if ($needle !== '') {
            $entries = $entries
                ->filter(function (array $entry) use ($needle): bool {
                    $haystack = Str::lower(implode(' ', [
                        $entry['number'],
                        $entry['category'],
                        $entry['description'],
                        $entry['numbers'],
                        $entry['title'],
                        $entry['slug'],
                        $entry['interpretation'],
                        implode(' ', $entry['keywords']),
                    ]));

                    return str_contains($haystack, $needle);
                })
                ->values();
        }

        $perPage = max(
            1,
            (int) config('dream-book.per_page', 12),
        );

        return new LengthAwarePaginator(
            $entries->forPage($page, $perPage)->values(),
            $entries->count(),
            $perPage,
            $page,
            [
                'path' => request()->url(),
                'query' => request()->query(),
            ],
        );
    }

    public function findBySlug(string $slug): ?array
    {
        return $this->all()->firstWhere('slug', $slug);
    }

    public function related(
        array $entry,
        int $limit = 4,
    ): Collection {
        return $this->all()
            ->reject(
                fn (array $candidate): bool => $candidate['slug'] === $entry['slug']
            )
            ->sortBy(
                fn (array $candidate): int => abs(
                    (int) $candidate['number']
                    - (int) $entry['number']
                )
            )
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
                    ->map(
                        fn (DreamBookEntry $entry): array => [
                            'number' => $entry->number,
                            'category' => $entry->category ?? '2D',
                            'description' => $entry->description
                                ?: $entry->title,
                            'numbers' => $entry->numbers
                                ?: $entry->number,
                            'slug' => $entry->slug,
                            'title' => $entry->title,
                            'keywords' => $entry->keywords ?? [],
                            'interpretation' => $entry->interpretation,
                        ]
                    );

                if ($entries->isNotEmpty()) {
                    return $entries;
                }
            }
        } catch (Throwable) {
        }

        return collect(config('dream-book.entries', []))
            ->map(
                fn (array $entry): array => [
                    'number' => (string) $entry['number'],
                    'category' => '2D',
                    'description' => $entry['title'],
                    'numbers' => (string) $entry['number'],
                    'slug' => $entry['slug'],
                    'title' => $entry['title'],
                    'keywords' => $entry['keywords'] ?? [],
                    'interpretation' => $entry['interpretation'] ?? '',
                ]
            )
            ->sortBy(
                fn (array $entry): int => (int) $entry['number']
            )
            ->values();
    }
}
