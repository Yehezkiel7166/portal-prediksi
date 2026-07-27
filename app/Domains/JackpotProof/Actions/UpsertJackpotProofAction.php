<?php

namespace App\Domains\JackpotProof\Actions;

use App\Domains\Brand\Support\BrandContext;
use App\Domains\JackpotProof\Models\JackpotProof;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

final class UpsertJackpotProofAction
{
    public function __construct(private readonly BrandContext $brandContext) {}

    public function execute(array $data, ?JackpotProof $proof = null): JackpotProof
    {
        $proof ??= new JackpotProof();
        if (! $proof->exists) {
            $proof->brand_id = $this->brandContext->get()?->getKey();
        }

        $data['title'] = trim((string) ($data['title'] ?? ''));
        $data['slug'] = Str::slug(trim((string) ($data['slug'] ?? '')) ?: $data['title']);
        $status = (string) ($data['status'] ?? JackpotProof::STATUS_DRAFT);

        if ($status === JackpotProof::STATUS_APPROVED && blank($data['published_at'] ?? null)) {
            throw ValidationException::withMessages([
                'published_at' => 'Tanggal publikasi wajib diisi sebelum bukti jackpot disetujui.',
            ]);
        }

        $validated = Validator::make($data, [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', Rule::unique('jackpot_proofs', 'slug')->where(fn ($query) => $query->where('brand_id', $proof->brand_id))->ignore($proof->getKey())],
            'description' => ['nullable', 'string'],
            'image_path' => ['required', 'string', 'max:2048'],
            'thumbnail_path' => ['nullable', 'string', 'max:2048'],
            'status' => ['required', Rule::in([JackpotProof::STATUS_DRAFT, JackpotProof::STATUS_PENDING, JackpotProof::STATUS_APPROVED, JackpotProof::STATUS_REJECTED])],
            'published_at' => ['nullable', 'date'],
            'sort_order' => ['required', 'integer', 'min:0'],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string'],
            'moderation_notes' => ['nullable', 'string'],
        ])->validate();

        if (in_array($status, [JackpotProof::STATUS_APPROVED, JackpotProof::STATUS_REJECTED], true)) {
            $validated['moderated_at'] = now();
            $validated['moderated_by'] = Auth::id();
        } else {
            $validated['moderated_at'] = null;
            $validated['moderated_by'] = null;
        }

        $proof->fill(Arr::only($validated, $proof->getFillable()));
        $proof->save();

        return $proof->refresh();
    }
}
