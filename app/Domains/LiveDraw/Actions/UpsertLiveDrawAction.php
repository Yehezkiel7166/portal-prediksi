<?php

namespace App\Domains\LiveDraw\Actions;

use App\Core\Support\TimezoneCatalog;
use App\Domains\LiveDraw\Models\LiveDraw;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UpsertLiveDrawAction
{
    /**
     * @param array<string, mixed> $data
     */
    public function execute(
        array $data,
        ?LiveDraw $liveDraw = null,
    ): LiveDraw {
        $liveDraw ??= new LiveDraw();

        $data = $this->normalize($data);

        $validated = Validator::make($data, [
            'market_id' => [
                'required',
                'integer',
                'exists:markets,id',
            ],
            'title' => [
                'required',
                'string',
                'max:255',
            ],
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('live_draws', 'slug')
                    ->ignore($liveDraw->getKey()),
            ],
            'provider' => [
                'required',
                Rule::in([
                    LiveDraw::PROVIDER_OFFICIAL,
                    LiveDraw::PROVIDER_YOUTUBE,
                    LiveDraw::PROVIDER_VIMEO,
                    LiveDraw::PROVIDER_CUSTOM,
                ]),
            ],
            'stream_type' => [
                'required',
                Rule::in([
                    LiveDraw::STREAM_TYPE_URL,
                    LiveDraw::STREAM_TYPE_IFRAME,
                    LiveDraw::STREAM_TYPE_HLS,
                ]),
            ],
            'source_url' => [
                'nullable',
                'url:http,https',
                'max:4096',
            ],
            'draw_days' => [
                'nullable',
                'array',
            ],
            'draw_days.*' => [
                'integer',
                Rule::in([1, 2, 3, 4, 5, 6, 7]),
            ],
            'draw_time' => [
                'nullable',
                'regex:/^\d{2}:\d{2}(:\d{2})?$/',
            ],
            'timezone' => [
                'required',
                'string',
                Rule::in(array_keys(TimezoneCatalog::options())),
            ],
            'status' => [
                'required',
                Rule::in([
                    LiveDraw::STATUS_OFFLINE,
                    LiveDraw::STATUS_SCHEDULED,
                    LiveDraw::STATUS_LIVE,
                    LiveDraw::STATUS_FINISHED,
                    LiveDraw::STATUS_CANCELLED,
                ]),
            ],
            'headline' => [
                'nullable',
                'string',
                'max:255',
            ],
            'footer' => [
                'nullable',
                'string',
                'max:5000',
            ],
            'logo_path' => [
                'nullable',
                'string',
                'max:2048',
            ],
            'background_path' => [
                'nullable',
                'string',
                'max:2048',
            ],
            'background_focal_point' => [
                'required',
                Rule::in([
                    'top-left',
                    'top',
                    'top-right',
                    'left',
                    'center',
                    'right',
                    'bottom-left',
                    'bottom',
                    'bottom-right',
                ]),
            ],
            'priority' => [
                'required',
                'integer',
                'min:0',
            ],
            'notes' => [
                'nullable',
                'string',
                'max:5000',
            ],
        ])->validate();

        return DB::transaction(function () use (
            $liveDraw,
            $validated,
        ): LiveDraw {
            $liveDraw->fill(
                Arr::only($validated, $liveDraw->getFillable())
            );

            $liveDraw->save();

            return $liveDraw->refresh();
        });
    }

    /**
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    private function normalize(array $data): array
    {
        $data['title'] = trim((string) ($data['title'] ?? ''));

        $data['slug'] = Str::slug(
            trim((string) ($data['slug'] ?? ''))
                ?: $data['title']
        );

        $data['source_url'] = $this->nullableTrim(
            $data['source_url'] ?? null
        );

        $data['headline'] = $this->nullableTrim(
            $data['headline'] ?? null
        );

        $data['footer'] = $this->nullableTrim(
            $data['footer'] ?? null
        );

        $data['notes'] = $this->nullableTrim(
            $data['notes'] ?? null
        );

        $data['draw_days'] = array_values(
            array_unique(
                array_map(
                    'intval',
                    Arr::wrap($data['draw_days'] ?? [])
                )
            )
        );

        sort($data['draw_days']);

        if ($data['draw_days'] === []) {
            $data['draw_days'] = null;
        }

        return $data;
    }

    private function nullableTrim(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $value = trim((string) $value);

        return $value === '' ? null : $value;
    }
}
