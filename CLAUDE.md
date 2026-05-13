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
    ->setTheme('action', 'success')                       // single variant
    ->setTheme('table', ['th', 'th-dark'])                // array of variant keys
    ->setThemes(['action' => 'success', 'size' => 'lg']); // batch
```

Theme files live in `resources/views/_themes/tailwind/` and return PHP arrays of CSS classes keyed by variant name:

```php
// resources/views/_themes/tailwind/action.blade.php
return [
    'default'   => "whitespace-nowrap bg-blue-700 hover:bg-blue-800",
    'success'   => "whitespace-nowrap bg-green-700 hover:bg-green-800",
    'error'     => "whitespace-nowrap bg-red-700 hover:bg-red-800",
    'link'      => "text-blue-500 underline hover:no-underline",
    // ...
];
```

### Individual components

`DivComponent` is both a utility **and** a blueprint for creating new targeted component classes that bypass the enum/builder entirely. To create a new individual component, duplicate the `DivComponent` pattern:

1. Put the class in `src/Components/Individual/`
2. Implement `BackendComponent`, `IndividualComponent`, `ThemeComponent`, `Htmlable` (omit `ContentsComponent`+`HasContent` for self-closing elements)
3. Use traits `IsBackendComponent`, `IsThemeable` (add `HasContent` only if the component can hold children)
4. Define `getName()` to return the `ComponentEnum` value (or any dotted view path)
5. Define `getComponentPath()` and `getPathOnly()` following the existing convention
6. Wire up `getAttributeBag()`, `toHtml()`, `toArray()`, and a static `make()` factory
7. Mark the class `final` (optional but recommended)

```php
use Juaniquillo\BackendComponents\Components\Individual\DivComponent;

$div = new DivComponent;
$div->setAttribute('class', 'my-class');
$div->setContent('Hello');
```

Currently only `DivComponent` exists in this category — add more as needed.

### Table utilities

`TableUtil` builds a complete `<table>` component tree from head/body arrays. `CellBag` passes per-cell data:

```php
use Juaniquillo\BackendComponents\Utils\TableUtil;
use Juaniquillo\BackendComponents\Utils\CellBag;

$table = TableUtil::make(
    head: ['Name', 'Email', 'Role'],
    body: [
        [                         // plain values
            'Alice',
            'alice@example.com',
            'Admin',
        ],
        [                         // CellBag for per-cell control
            new CellBag(content: 'Bob', theme: ['color' => 'success']),
            'bob@example.com',
            'Editor',
        ],
    ],
)->getComponent();
```

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

**Template:** `TEMPLATE`
**Collection:** `COLLECTION`
**Block:** `DIV`, `PARAGRAPH`
**Inline:** `BUTTON`, `LINK`, `IMG`, `SPAN`, `BOLD`, `EM`, `ITALIC`, `STRONG`, `SMALL`
**Headers:** `H1`, `H2`, `H3`, `H4`, `H5`, `H6`
**Form:** `FORM`, `LABEL`, `LEGEND`, `FIELDSET`, `TEXT_INPUT`, `FILE_INPUT`, `EMAIL_INPUT`, `SEARCH_INPUT`, `PASSWORD_INPUT`, `CHECKBOX_INPUT`, `HIDDEN_INPUT`, `RADIO_INPUT`, `DATALIST`, `TEXTAREA`, `SELECT`, `OPTGROUP`, `OPTION`
**Table:** `TABLE`, `THEAD`, `TBODY`, `TFOOT`, `TR`, `TH`, `TD`, `CAPTION`, `COLGROUP`, `COL`
**Lists:** `OL`, `UL`, `LI`
**Details:** `DETAILS`, `SUMMARY`
**Layers:** `DIALOG`
**Custom:** `MODAL`

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
