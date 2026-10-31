<?php

namespace App\Filament\Resources\Pireps\Infolists\Components;

use Filament\Infolists\Components\Entry;

class DashboardStat extends Entry
{
    protected string $view = 'filament.resources.pireps.infolists.components.dashboard-stat';

    public string|array|\Closure|\Filament\Support\Icons\Heroicon|null $icon = null;
    public string|array|\Closure $value = '';
    public string|array|\Closure $unit = '';

    public static function make(?string $name = null): static
    {
        return parent::make($name);
    }

    public function icon(string|array|\Closure|\Filament\Support\Icons\Heroicon $icon): static
    {
        $this->icon = $icon;
        return $this;
    }

    public function value(string|array|\Closure $value): static
    {
        $this->value = $value;
        return $this;
    }

    public function unit(string|array|\Closure $unit): static
    {
        $this->unit = $unit;
        return $this;
    }

    public function getIcon(): string|\Filament\Support\Icons\Heroicon|null
    {
        return $this->evaluate($this->icon);
    }

    public function getValue(): string
    {
        return (string) $this->evaluate($this->value);
    }

    public function getUnit(): string
    {
        return (string) $this->evaluate($this->unit);
    }
}
