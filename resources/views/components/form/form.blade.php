@php
    use Juaniquillo\BackendComponents\Enums\ComponentEnum;

    use function Juaniquillo\BackendComponents\makeBackendComponent;

    $settings = $settings ?? [];

    $disableCsrf = (bool) ($settings['disable_csrf'] ?? false);
    $disableMethodInput = (bool) ($settings['disable_method_input'] ?? false);

    $method = (string) ($attributes->get('method') ?? 'POST');

    $attributes = $attributes->except('method');

    $methodInput = $disableMethodInput ? null : makeBackendComponent(ComponentEnum::HIDDEN_INPUT)
        ->setAttribute('name', '_method')
        ->setAttribute('value', strtoupper($method));
@endphp

<form {{ $attributes->merge(['method' => strtoupper($method) === 'GET' ? 'GET' : 'POST']) }}>
    {{ $methodInput }}
    @if(!$disableCsrf) @csrf @endif
    {{ $slot }}
</form>
