<?php

namespace Tests\Feature\Shio;

use App\Domains\Shio\Actions\GenerateShioBannerAction;
use App\Domains\Shio\Models\ShioNumber;
use App\Domains\Shio\Models\ShioPeriod;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Tests\TestCase;

class GenerateShioBannerActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_action_generates_png_and_updates_period(): void
    {
        Storage::fake('public');

        $templatePath = 'shio/banner-templates/template.png';

        Storage::disk('public')->put(
            $templatePath,
            $this->createPng(800, 600),
        );

        $period = ShioPeriod::factory()->create([
            'banner_template' => $templatePath,
        ]);

        ShioNumber::factory()->create([
            'shio_period_id' => $period->id,
            'name' => 'KAMBING',
            'numbers' => ['12', '24', '36', '48'],
            'sort_order' => 1,
        ]);

        ShioNumber::factory()->create([
            'shio_period_id' => $period->id,
            'name' => 'KUDA',
            'numbers' => ['11', '23', '35', '47'],
            'sort_order' => 2,
        ]);

        $result = app(GenerateShioBannerAction::class)
            ->execute($period);

        $expectedPath = sprintf(
            'shio/generated/shio-period-%d.png',
            $period->id,
        );

        $this->assertSame(
            $expectedPath,
            $result->generated_banner,
        );

        Storage::disk('public')->assertExists($expectedPath);

        $generatedContents = Storage::disk('public')
            ->get($expectedPath);

        $metadata = getimagesizefromstring($generatedContents);

        $this->assertIsArray($metadata);
        $this->assertSame(800, $metadata[0]);
        $this->assertSame(600, $metadata[1]);
        $this->assertSame('image/png', $metadata['mime']);

        $this->assertDatabaseHas('shio_periods', [
            'id' => $period->id,
            'generated_banner' => $expectedPath,
        ]);
    }

    public function test_action_replaces_existing_generated_banner(): void
    {
        Storage::fake('public');

        $templatePath = 'shio/banner-templates/replacement.png';

        Storage::disk('public')->put(
            $templatePath,
            $this->createPng(640, 480),
        );

        $period = ShioPeriod::factory()->create([
            'banner_template' => $templatePath,
            'generated_banner' => 'shio/generated/old-banner.png',
        ]);

        Storage::disk('public')->put(
            'shio/generated/old-banner.png',
            'old banner',
        );

        $result = app(GenerateShioBannerAction::class)
            ->execute($period);

        $expectedPath = sprintf(
            'shio/generated/shio-period-%d.png',
            $period->id,
        );

        $this->assertSame(
            $expectedPath,
            $result->generated_banner,
        );

        Storage::disk('public')->assertExists($expectedPath);
    }

    public function test_action_rejects_missing_template_file(): void
    {
        Storage::fake('public');

        $period = ShioPeriod::factory()->create([
            'banner_template' =>
                'shio/banner-templates/missing.png',
        ]);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(
            'File template banner Shio tidak ditemukan.'
        );

        app(GenerateShioBannerAction::class)->execute($period);
    }

    public function test_action_rejects_template_outside_shio_directory(): void
    {
        Storage::fake('public');

        Storage::disk('public')->put(
            'other/template.png',
            $this->createPng(800, 600),
        );

        $period = ShioPeriod::factory()->create([
            'banner_template' => 'other/template.png',
        ]);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(
            'Template banner harus berada di direktori Shio.'
        );

        app(GenerateShioBannerAction::class)->execute($period);
    }

    public function test_action_rejects_template_that_is_too_small(): void
    {
        Storage::fake('public');

        $templatePath = 'shio/banner-templates/small.png';

        Storage::disk('public')->put(
            $templatePath,
            $this->createPng(200, 100),
        );

        $period = ShioPeriod::factory()->create([
            'banner_template' => $templatePath,
        ]);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(
            'Ukuran template banner minimal 320 × 240 piksel.'
        );

        app(GenerateShioBannerAction::class)->execute($period);
    }

    private function createPng(
        int $width,
        int $height,
    ): string {
        $image = imagecreatetruecolor($width, $height);

        $background = imagecolorallocate(
            $image,
            240,
            240,
            240,
        );

        imagefill($image, 0, 0, $background);

        ob_start();

        try {
            $this->assertTrue(imagepng($image));

            $contents = ob_get_contents();

            $this->assertIsString($contents);
            $this->assertNotSame('', $contents);

            return $contents;
        } finally {
            ob_end_clean();
            imagedestroy($image);
        }
    }
}
