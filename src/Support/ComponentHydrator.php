<?php

namespace Spatie\LivewireWizard\Support;

use Livewire\Component;
use Livewire\Drawer\Utils;
use Livewire\Mechanisms\HandleComponents\ComponentContext;
use Livewire\Mechanisms\HandleComponents\CorruptComponentPayloadException;
use Livewire\Mechanisms\HandleSynths\HandleSynths;

class ComponentHydrator
{
    protected HandleSynths $synths;

    public function __construct()
    {
        $this->synths = app(HandleSynths::class);
    }

    public function dehydrateData(Component $component, mixed $data): mixed
    {
        $context = new ComponentContext($component);

        $data = $this->synths->dehydrate($data, $context, '');

        if (! Utils::isSyntheticTuple($data)) {
            return $data;
        }

        $payload = json_encode($data, JSON_THROW_ON_ERROR);

        return [$payload, ['s' => 'wizard', 'checksum' => $this->checksum($payload)]];
    }

    public function hydrateData(Component $component, array $data): mixed
    {
        if (! Utils::isSyntheticTuple($data)) {
            return $data;
        }

        if ($data[1]['s'] !== 'wizard') {
            throw new CorruptComponentPayloadException();
        }

        if (! is_string($data[0])) {
            throw new CorruptComponentPayloadException();
        }

        $checksum = $data[1]['checksum'] ?? null;

        if (! is_string($checksum)) {
            throw new CorruptComponentPayloadException();
        }

        if (! hash_equals($this->checksum($data[0]), $checksum)) {
            throw new CorruptComponentPayloadException();
        }

        $data = json_decode($data[0], true, flags: JSON_THROW_ON_ERROR);

        $context = new ComponentContext($component);

        return $this->synths->hydrate($data, $context, '');
    }

    protected function checksum(string $payload): string
    {
        return hash_hmac('sha256', 'livewire-wizard:'.$payload, app('encrypter')->getKey());
    }
}
