<?php

namespace Tests\Feature\Prediction;

use Tests\TestCase;

class PredictionResponsiveWorkspaceContractTest extends TestCase
{
    private function indexSource(): string
    {
        return file_get_contents(
            resource_path('views/frontend/predictions/index.blade.php')
        );
    }

    private function showSource(): string
    {
        return file_get_contents(
            resource_path('views/frontend/predictions/show.blade.php')
        );
    }

    public function test_prediction_index_declares_workspace_contract(): void
    {
        $source = $this->indexSource();

        $this->assertStringContainsString(
            'data-prediction-workspace',
            $source
        );

        $this->assertStringContainsString(
            'data-prediction-filter-panel',
            $source
        );

        $this->assertStringContainsString(
            'data-prediction-list',
            $source
        );
    }

    public function test_prediction_filter_controls_are_width_safe(): void
    {
        $source = $this->indexSource();

        $this->assertStringContainsString(
            'data-prediction-filter-panel',
            $source
        );

        $this->assertMatchesRegularExpression(
            '/data-prediction-filter-panel[^>]*class="[^"]*(?:min-w-0|w-full)[^"]*"/s',
            $source
        );
    }

    public function test_prediction_listing_has_explicit_responsive_grid_contract(): void
    {
        $source = $this->indexSource();

        $this->assertStringContainsString(
            'data-prediction-list',
            $source
        );

        $this->assertMatchesRegularExpression(
            '/data-prediction-list[^>]*class="[^"]*grid[^"]*"/s',
            $source
        );

        $this->assertMatchesRegularExpression(
            '/data-prediction-list[^>]*class="[^"]*(?:md:grid-cols-|lg:grid-cols-)[^"]*"/s',
            $source
        );
    }

    public function test_prediction_cards_are_width_safe(): void
    {
        $source = $this->indexSource();

        $this->assertStringContainsString(
            'data-prediction-card',
            $source
        );

        $this->assertMatchesRegularExpression(
            '/data-prediction-card[^>]*class="[^"]*min-w-0[^"]*"/s',
            $source
        );
    }

    public function test_prediction_number_content_can_wrap_without_forcing_viewport_overflow(): void
    {
        $source = $this->indexSource();

        $this->assertStringContainsString(
            'data-prediction-number-content',
            $source
        );

        $this->assertMatchesRegularExpression(
            '/data-prediction-number-content[^>]*class="[^"]*(?:break-words|overflow-wrap|whitespace-normal)[^"]*"/s',
            $source
        );
    }

    public function test_prediction_datepicker_is_anchored_to_width_safe_container(): void
    {
        $source = $this->indexSource();

        $this->assertStringContainsString(
            'data-prediction-datepicker',
            $source
        );

        $this->assertMatchesRegularExpression(
            '/data-prediction-datepicker[^>]*class="[^"]*(?:w-full|max-w-|min-w-0)[^"]*"/s',
            $source
        );
    }

    public function test_prediction_index_does_not_require_horizontal_page_scroll(): void
    {
        $source = $this->indexSource();

        $this->assertStringNotContainsString(
            'min-w-[700px]',
            $source
        );

        $this->assertStringNotContainsString(
            'min-w-[800px]',
            $source
        );

        $this->assertStringNotContainsString(
            'min-w-[900px]',
            $source
        );

        $this->assertStringNotContainsString(
            'min-w-[1000px]',
            $source
        );
    }

    public function test_prediction_detail_declares_width_safe_surface(): void
    {
        $source = $this->showSource();

        $this->assertStringContainsString(
            'data-prediction-detail-workspace',
            $source
        );

        $this->assertMatchesRegularExpression(
            '/data-prediction-detail-workspace[^>]*class="[^"]*min-w-0[^"]*"/s',
            $source
        );
    }
}
