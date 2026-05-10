# Laravel Backend Component — AI Guide

## Overview

A Laravel package for building dynamic, class-based HTML components in PHP. Instead of blade HTML, you compose component trees via PHP objects and render them as HTML through Laravel's `x-dynamic-component`.

Components implement `Htmlable` so they render in Blade via `{{ $component }}`.

## Quick Reference

### Creating components

```php
use Juaniquillo\BackendComponents\Builders\ComponentBuilder;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;

$button = ComponentBuilder::make(ComponentEnum::BUTTON);
$div = new MainBackendComponent('div');
```

### Setting content

Use **`setContent()`** for a single item and **`setContents()`** for multiple items at once:

```php
$div = ComponentBuilder::make(ComponentEnum::DIV)
    ->setContent('Hello')                               // appended (no key)
    ->setContent('World', 'key_1')                      // appended with key
    ->setContents([                                      // batch
        'item_1' => ComponentBuilder::make(ComponentEnum::SPAN)->setContent('A'),
        'item_2' => ComponentBuilder::make(ComponentEnum::SPAN)->setContent('B'),
    ])
    ->prependContent('First')                           // prepended (no key)
    ->prependContent('Really First', 'key_0')           // prepended with key
    ->unsetContent()                                     // clear all content
    ->unsetContent('key_1');                             // remove by key
```

### Setting attributes

Use **`setAttribute()`** for a single attribute and **`setAttributes()`** for multiple at once:

```php
$div = ComponentBuilder::make(ComponentEnum::DIV)
    ->setAttribute('id', 'my-id')
    ->setAttribute('class', 'custom-class')
    ->setAttributes(['data-foo' => 'bar', 'style' => 'display:none']);
```

### Livewire integration

```php
$livewire = ComponentBuilder::make(ComponentEnum::DIV)
    ->setLivewire()
    ->setLivewireKey('my-key')
    ->setLivewireParams(['param' => 'value']);
```

### Settings bag

```php
$component = ComponentBuilder::make(ComponentEnum::MODAL)
    ->setSetting('transition', 'fade')
    ->setSettings(['setting_1' => 'value_1', 'setting_2' => 'value_2']);
```

### Theme system (Tailwind CSS)

```php
$button = ComponentBuilder::make(ComponentEnum::BUTTON)
    ->setTheme('action', 'success')                       // single
    ->setThemes(['action' => 'success', 'size' => 'lg']); // batch
```

Theme files live in `resources/views/_themes/tailwind/` and return PHP arrays of CSS classes.

### Serialization / Factory

```php
$array = $component->toArray();
$restored = ComponentFactory::fromArray($array);
```

### Local component building (for apps consuming the package)

Three builders let you resolve components/themes from the app's own `resources/views/` instead of the package:

```php
use Juaniquillo\BackendComponents\Builders\LocalComponentBuilder;
use Juaniquillo\BackendComponents\Builders\LocalThemeComponentBuilder;

// Local builder: resolves BOTH components and themes from the app's views
$local = LocalComponentBuilder::make(ComponentEnum::BUTTON);

// Local theme builder: resolves THEMES from the app's views, components from the package
$localTheme = LocalThemeComponentBuilder::make(ComponentEnum::BUTTON);

// ComponentBuilder::make()->useLocal() is equivalent to LocalComponentBuilder
$component = ComponentBuilder::make(ComponentEnum::BUTTON)
    ->useLocal();
```

| Builder | Component path | Theme path |
|---|---|---|
| `ComponentBuilder` | package `resources/views/components/` | package `resources/views/_themes/tailwind/` |
| `LocalComponentBuilder` | app `resources/views/components/` | app `resources/views/_themes/tailwind/` |
| `LocalThemeComponentBuilder` | package `resources/views/components/` | app `resources/views/_themes/tailwind/` |

## Available component enums

**Block:** `DIV`, `PARAGRAPH`
**Inline:** `BUTTON`, `LINK`, `IMG`, `SPAN`, `BOLD`, `EM`, `ITALIC`, `STRONG`, `SMALL`
**Headers:** `H1` through `H6`
**Form:** `FORM`, `LABEL`, `LEGEND`, `FIELDSET`, `TEXT_INPUT`, `FILE_INPUT`, `EMAIL_INPUT`, `SEARCH_INPUT`, `PASSWORD_INPUT`, `CHECKBOX_INPUT`, `HIDDEN_INPUT`, `RADIO_INPUT`, `DATALIST`, `TEXTAREA`, `SELECT`, `OPTGROUP`, `OPTION`
**Table:** `TABLE`, `THEAD`, `TBODY`, `TFOOT`, `TR`, `TH`, `TD`, `CAPTION`, `COLGROUP`, `COL`
**Lists:** `OL`, `UL`, `LI`
**Details:** `DETAILS`, `SUMMARY`
**Layers:** `DIALOG`
**Custom:** `MODAL`, `COLLECTION`, `TEMPLATE`

## Blade template conventions

Each component view:
1. Declares `@props(['attrs' => null])`
2. Has `@php` block extracting `$serverAttrs`, `$content`, `$slot`
3. Renders the HTML element with `{{ $attributes->merge($serverAttrs) }}>{{ $content }}{{ $slot }}`
4. Self-closing tags (input, img, col) use `/>` instead

## Adding a new component

1. Add case to `ComponentEnum` (value = dotted view path, e.g. `'form.datalist'`)
2. Create blade file at `resources/views/components/{path}.blade.php`
3. Write feature test in `tests/Feature/Components/{Category}/{Name}Test.php`

## Test conventions

- Feature tests extend `Tests\TestCase` (Orchestra Testbench-based)
- Use `#[Test]` attribute (not `@test` docblock)
- `$this->blade('{{ $component }}', ['component' => $component])` with `assertSee`/`assertDontSee`
- Standard test methods: `empty_{component}`, `{component}_accepts_content`, `{component}_accepts_contents_array`, `{component}_accepts_attributes`, `{component}_accepts_theme`
- Content/sub-component tests use `ComponentBuilder::make(ComponentEnum::OPTION)` nested content

## Commands

```bash
composer test        # Run PHPUnit
composer qa          # Pint + PHPStan + PHPUnit (CI gate)
vendor/bin/pint      # Code style fix
vendor/bin/phpstan   # Static analysis (level 8)
```

## Code style

- PHP 8.2+, strict types, PSR-4 (`Juaniquillo\BackendComponents` → `src/`)
- Namespaced helpers in `src/helpers.php` (import with `use function Juaniquillo\BackendComponents\processThemes;`)
- `declare(strict_types=1)` on all PHP files
- Fluent return types (`: static`) on setters
- Docblock `@param` / `@return` for array generics
- Trait-per-concern pattern (9 traits in `src/Concerns/`)
