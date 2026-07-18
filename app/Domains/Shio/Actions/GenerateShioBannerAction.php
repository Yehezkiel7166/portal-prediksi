<?php

namespace App\Domains\Shio\Actions;

use App\Domains\Shio\Models\ShioPeriod;
use GdImage;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;
use Throwable;

class GenerateShioBannerAction
{
    private const TEMPLATE_DIRECTORY = 'shio/banner-templates/';

    private const GENERATED_DIRECTORY = 'shio/generated';

    public function execute(ShioPeriod $period): ShioPeriod
    {
        $disk = Storage::disk('public');
        $templatePath = $this->validatedTemplatePath($period, $disk);
        $templateContents = $disk->get($templatePath);

        $image = $this->createImage($templateContents);

        try {
            $this->render($image, $period);

            $generatedPath = sprintf(
                '%s/shio-period-%d.png',
                self::GENERATED_DIRECTORY,
                $period->getKey(),
            );

            $generatedContents = $this->encodePng($image);

            if (! $disk->put($generatedPath, $generatedContents)) {
                throw new RuntimeException(
                    'Banner Shio gagal disimpan ke public storage.'
                );
            }

            $period->forceFill([
                'generated_banner' => $generatedPath,
            ])->save();

            return $period->refresh();
        } catch (Throwable $exception) {
            throw $exception;
        } finally {
            imagedestroy($image);
        }
    }

    private function validatedTemplatePath(
        ShioPeriod $period,
        FilesystemAdapter $disk,
    ): string {
        $templatePath = ltrim(
            trim((string) $period->banner_template),
            '/',
        );

        if ($templatePath === '') {
            throw new RuntimeException(
                'Template banner Shio belum dipilih.'
            );
        }

        if (! str_starts_with(
            $templatePath,
            self::TEMPLATE_DIRECTORY,
        )) {
            throw new RuntimeException(
                'Template banner harus berada di direktori Shio.'
            );
        }

        if (! $disk->exists($templatePath)) {
            throw new RuntimeException(
                'File template banner Shio tidak ditemukan.'
            );
        }

        return $templatePath;
    }

    private function createImage(string $contents): GdImage
    {
        $metadata = @getimagesizefromstring($contents);

        if ($metadata === false) {
            throw new RuntimeException(
                'File template banner bukan gambar yang valid.'
            );
        }

        $allowedMimeTypes = [
            'image/jpeg',
            'image/png',
            'image/webp',
        ];

        $mimeType = $metadata['mime'] ?? null;

        if (! in_array($mimeType, $allowedMimeTypes, true)) {
            throw new RuntimeException(
                'Format template banner tidak didukung.'
            );
        }

        $image = @imagecreatefromstring($contents);

        if (! $image instanceof GdImage) {
            throw new RuntimeException(
                'Template banner gagal dibaca oleh GD.'
            );
        }

        imagealphablending($image, true);
        imagesavealpha($image, true);

        return $image;
    }

    private function render(
        GdImage $image,
        ShioPeriod $period,
    ): void {
        $width = imagesx($image);
        $height = imagesy($image);

        if ($width < 320 || $height < 240) {
            throw new RuntimeException(
                'Ukuran template banner minimal 320 × 240 piksel.'
            );
        }

        $shios = $period->shios()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $lineHeight = 18;
        $minimumPanelHeight = 110;
        $desiredPanelHeight = 78 + ($shios->count() * $lineHeight);
        $maximumPanelHeight = max(
            $minimumPanelHeight,
            (int) floor($height * 0.65),
        );

        $panelHeight = min(
            max($desiredPanelHeight, $minimumPanelHeight),
            $maximumPanelHeight,
        );

        $panelTop = $height - $panelHeight;

        $panelColor = imagecolorallocatealpha(
            $image,
            0,
            0,
            0,
            35,
        );

        $primaryTextColor = imagecolorallocate(
            $image,
            255,
            255,
            255,
        );

        $accentTextColor = imagecolorallocate(
            $image,
            255,
            215,
            0,
        );

        imagefilledrectangle(
            $image,
            0,
            $panelTop,
            $width,
            $height,
            $panelColor,
        );

        $padding = 16;
        $currentY = $panelTop + 12;

        $title = Str::upper(Str::ascii(
            trim($period->title) !== ''
                ? $period->title
                : "Tabel Shio {$period->year}"
        ));

        imagestring(
            $image,
            5,
            $padding,
            $currentY,
            $this->fitText($title, 5, $width - ($padding * 2)),
            $accentTextColor,
        );

        $currentY += 24;

        $dateRange = sprintf(
            '%s - %s',
            $period->start_date?->format('Y-m-d') ?? '-',
            $period->end_date?->format('Y-m-d') ?? '-',
        );

        imagestring(
            $image,
            3,
            $padding,
            $currentY,
            $this->fitText(
                Str::ascii($dateRange),
                3,
                $width - ($padding * 2),
            ),
            $primaryTextColor,
        );

        $currentY += 24;

        foreach ($shios as $shio) {
            if (($currentY + $lineHeight) > ($height - 8)) {
                break;
            }

            $numbers = implode(
                ' ',
                array_map(
                    static fn (mixed $number): string => trim(
                        (string) $number
                    ),
                    is_array($shio->numbers)
                        ? $shio->numbers
                        : [],
                ),
            );

            $line = sprintf(
                '%s: %s',
                Str::upper(Str::ascii($shio->name)),
                $numbers,
            );

            imagestring(
                $image,
                3,
                $padding,
                $currentY,
                $this->fitText(
                    $line,
                    3,
                    $width - ($padding * 2),
                ),
                $primaryTextColor,
            );

            $currentY += $lineHeight;
        }
    }

    private function fitText(
        string $text,
        int $font,
        int $availableWidth,
    ): string {
        $characterWidth = imagefontwidth($font);

        if ($characterWidth <= 0) {
            return $text;
        }

        $maximumCharacters = max(
            1,
            (int) floor($availableWidth / $characterWidth),
        );

        if (strlen($text) <= $maximumCharacters) {
            return $text;
        }

        if ($maximumCharacters <= 3) {
            return substr($text, 0, $maximumCharacters);
        }

        return substr($text, 0, $maximumCharacters - 3).'...';
    }

    private function encodePng(GdImage $image): string
    {
        ob_start();

        try {
            if (! imagepng($image, null, 6)) {
                throw new RuntimeException(
                    'Banner Shio gagal dikonversi menjadi PNG.'
                );
            }

            $contents = ob_get_contents();

            if (! is_string($contents) || $contents === '') {
                throw new RuntimeException(
                    'Hasil banner Shio kosong.'
                );
            }

            return $contents;
        } finally {
            ob_end_clean();
        }
    }
}
