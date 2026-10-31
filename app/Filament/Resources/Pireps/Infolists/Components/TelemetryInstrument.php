<?php

namespace App\Filament\Resources\Pireps\Infolists\Components;

use Filament\Infolists\Components\Entry;

class TelemetryInstrument extends Entry
{
    protected string $view = 'filament.resources.pireps.infolists.components.telemetry-instrument';

    public string|array|\Closure|\Filament\Support\Icons\Heroicon $icon = 'heroicon-o-cpu-chip';
    public string|array|\Closure $label_text = '';
    public string|array|\Closure $metrics = [];

    public string $chartType = 'altitude';

    public static function make(?string $name = null): static
    {
        return parent::make($name);
    }

    public function chartType(string $type): static
    {
        $this->chartType = $type;
        return $this;
    }

    public function getChartType(): string
    {
        return $this->chartType;
    }

    public function icon(string|array|\Closure|\Filament\Support\Icons\Heroicon $icon): static
    {
        $this->icon = $icon;
        return $this;
    }

    public function labelText(string|array|\Closure $label): static
    {
        $this->label_text = $label;
        return $this;
    }

    public function metrics(array|\Closure $metrics): static
    {
        $this->metrics = $metrics;
        return $this;
    }

    public function getMetrics(): array
    {
        return (array) $this->evaluate($this->metrics);
    }

    public function getLabelText(): string
    {
        return (string) $this->evaluate($this->label_text);
    }

    public function getIcon(): string|\Filament\Support\Icons\Heroicon|null
    {
        return $this->evaluate($this->icon);
    }
}
