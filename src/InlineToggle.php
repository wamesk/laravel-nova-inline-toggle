<?php

namespace Wame\InlineToggle;

use Laravel\Nova\Fields\Field;

class InlineToggle extends Field
{
    public $component = 'inline-toggle';

    protected function resolveAttribute($resource, $attribute)
    {
        $this->withMeta([
            'id' => data_get($resource, $resource->getKeyName()),
        ]);

        return parent::resolveAttribute($resource, $attribute);
    }

    public function onColor(string $color): static
    {
        return $this->withMeta(['onColor' => $color]);
    }

    public function offColor(string $color): static
    {
        return $this->withMeta(['offColor' => $color]);
    }

    public function inlineOnDetail(bool $value = true): static
    {
        return $this->withMeta(['inlineOnDetail' => $value]);
    }

    public function successMessage(string $message): static
    {
        return $this->withMeta(['successMessage' => $message]);
    }

    public function onMessage(string $message): static
    {
        return $this->withMeta(['onMessage' => $message]);
    }

    public function offMessage(string $message): static
    {
        return $this->withMeta(['offMessage' => $message]);
    }

    public function errorMessage(string $message): static
    {
        return $this->withMeta(['errorMessage' => $message]);
    }
}
