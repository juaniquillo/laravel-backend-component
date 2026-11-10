# Changelog

## [Unreleased]

### Changed (breaking)

- `Builders\ComponentBuilder` is now `Factories\ComponentFactory`.
- `Builders\LocalComponentBuilder` is now `Factories\LocalComponentFactory`.
- `Builders\LocalThemeComponentBuilder` is now `Factories\LocalThemeComponentFactory`.
- `Factories\ComponentFactory` is now `Deserializers\ComponentDeserializer`.
- `Factories\IndividualComponentFactory` is now `Deserializers\IndividualComponentDeserializer`.
- `Concerns\isFactory` is now `Concerns\DeserializesComponents`.
- `Contracts\StaticBuilder` is now `Contracts\StaticFactory`.

### Migration

| Old | New |
|---|---|
| `Juaniquillo\BackendComponents\Builders\ComponentBuilder` | `Juaniquillo\BackendComponents\Factories\ComponentFactory` |
| `Juaniquillo\BackendComponents\Builders\LocalComponentBuilder` | `Juaniquillo\BackendComponents\Factories\LocalComponentFactory` |
| `Juaniquillo\BackendComponents\Builders\LocalThemeComponentBuilder` | `Juaniquillo\BackendComponents\Factories\LocalThemeComponentFactory` |
| `Juaniquillo\BackendComponents\Factories\ComponentFactory` | `Juaniquillo\BackendComponents\Deserializers\ComponentDeserializer` |
| `Juaniquillo\BackendComponents\Factories\IndividualComponentFactory` | `Juaniquillo\BackendComponents\Deserializers\IndividualComponentDeserializer` |
| `Juaniquillo\BackendComponents\Concerns\isFactory` | `Juaniquillo\BackendComponents\Concerns\DeserializesComponents` |
| `Juaniquillo\BackendComponents\Contracts\StaticBuilder` | `Juaniquillo\BackendComponents\Contracts\StaticFactory` |

`make()` and `fromArray()` are unchanged, update only the class names and imports.

## [0.10.1] - 2026-11-10

### Added

- `IsCachable::setCacheStore(CacheInterface $store)` injects a custom PSR-16 store per instance (e.g. `Cache::store('redis')`).
- `IsCachable::getCacheStore()` is now public.

## [0.10.0] - 2026-10-22

### Added

- `HasPath::getViewPath()` returns the Blade view name of a component: `backend-component::components.div`, or `components.div` for local components.
- `DivComponent::getViewPath()` (the class does not use the `HasPath` trait).
- Tests: `ResolveComponentTest` and `HasPathTest`.

### Changed

- Package components render their template with `@include` using `getViewPath()` instead of `x-dynamic-component`.
- `resolve-content` builds its output with `implode()` instead of `@foreach`, so Livewire 4 does not inject block markers into the rendered content.
- Component templates receive `$attributes` (a `ComponentAttributeBag`) and `$slot` (processed content) directly. The `attrs` prop is gone.

### Fixed

- Alpine-style attributes such as `@click="open = true"` render as-is. `x-dynamic-component` compiled attribute names into PHP variables (`$@click`), which broke components like `ModalUtil`.
- Template settings are no longer exposed as props, so they cannot leak into the HTML.

### Migration

If you published or overrode package templates, replace the `attrs` prop pattern:

```blade
@props(['attrs' => null])

<div {{ $attributes->merge($attrs?->getAttributes() ?? []) }}>
    {{ $attrs?->content }}{{ $slot }}
</div>
```

with:

```blade
<div {{ $attributes }}>{{ $slot }}</div>
```

Templates that need settings can keep reading `$settings` (a plain array that is never rendered).
