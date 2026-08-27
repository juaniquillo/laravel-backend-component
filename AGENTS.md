# AGENTS.md — Laravel Backend Component

## Commands

- `composer test` — Run PHPUnit (Orchestra Testbench)
- `composer qa` — Run format (Pint) + typecheck (PHPStan) + tests in sequence
- `vendor/bin/pint` — Fix code style (enforces `declare_strict_types`)
- `vendor/bin/phpstan` — Static analysis at level 8 (memory limit: 1G)

**Order matters**: `qa` runs `format → analyse → test`. Run them in this order before committing.

## Code style

- `declare(strict_types=1)` is enforced by Pint (`pint.json` rule)
- PSR-4: `Juaniquillo\BackendComponents` → `src/`
- Prefix global PHP functions with `\` (e.g., `\is_array()`, `\count()`, `\trim()`)
- Fluent return types (`: static`) on all setters
- Namespaced helpers in `src/helpers.php`; import with `use function Juaniquillo\BackendComponents\processThemes;`
- Trait-per-concern pattern: 8 traits in `src/Concerns/`

## Testing

- Feature tests extend `Tests\TestCase` (Orchestra Testbench-based)
- Use `#[Test]` attribute (not `@test` docblock)
- Render components via `$this->blade('{{ $component }}', ['component' => $component])` with `assertSee`/`assertDontSee`
- Standard test method names: `empty_{component}`, `{component}_accepts_content`, `{component}_accepts_contents_array`, `{component}_accepts_attributes`, `{component}_accepts_theme`
- Nested content tests use `ComponentBuilder::make(ComponentEnum::OPTION)`

## Creating components

```php
use Juaniquillo\BackendComponents\Builders\ComponentBuilder;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;

$button = ComponentBuilder::make(ComponentEnum::BUTTON);
$div = new MainBackendComponent('div');
$div = new MainBackendComponent('div', new LocalThemeManager);  // app-local themes
```

## Setting content

Use **`setContent()`** for a single item and **`setContents()`** for multiple items at once:

```php
$div = ComponentBuilder::make(ComponentEnum::DIV)
    ->setContent('Hello')                               // appended (no key)
    ->setContent('World', 'key_1')                      // appended with key
    ->setContents([                                      // batch (ignores keys)
        ComponentBuilder::make(ComponentEnum::SPAN)->setContent('A'),
        ComponentBuilder::make(ComponentEnum::SPAN)->setContent('B'),
    ])
    ->setContents([                                      // batch with keys (overwrites existing)
        'item_1' => ComponentBuilder::make(ComponentEnum::SPAN)->setContent('A'),
        'item_2' => ComponentBuilder::make(ComponentEnum::SPAN)->setContent('B'),
    ], overwrite: true)
    ->prependContent('First')                           // prepended (no key)
    ->prependContent('Really First', 'key_0')           // prepended with key
    ->unsetContent()                                     // clear all content
    ->unsetContent('key_1');                             // remove by key
```

## Setting attributes

Use **`setAttribute()`** for a single attribute and **`setAttributes()`** for multiple at once:

```php
$div = ComponentBuilder::make(ComponentEnum::DIV)
    ->setAttribute('id', 'my-id')
    ->setAttribute('class', 'custom-class')
    ->setAttributes(['data-foo' => 'bar', 'style' => 'display:none']);
```

## Livewire integration

```php
$livewire = ComponentBuilder::make(ComponentEnum::DIV)
    ->setLivewire()
    ->setLivewireKey('my-key')
    ->setLivewireParams(['param' => 'value']);
```

## Settings

```php
$component = ComponentBuilder::make(ComponentEnum::DIV)
    ->setSetting('transition', 'fade')
    ->setSettings(['setting_1' => 'value_1', 'setting_2' => 'value_2']);
```

## Theme system (Tailwind CSS)

```php
$button = ComponentBuilder::make(ComponentEnum::BUTTON)
    ->setTheme('action', 'success')                       // single variant
    ->setTheme('table', ['th', 'th-dark'])                // array of variant keys
    ->setThemes(['action' => 'success', 'size' => 'lg']); // batch
```

Themes accumulate by default — calling `setTheme` with the same name appends rather than replaces:

```php
$button = ComponentBuilder::make(ComponentEnum::BUTTON)
    ->setTheme('action', 'success')
    ->setTheme('action', 'error');
// theme['action'] = ['success', 'error']

// Use overwrite: true to replace instead
$button->setTheme('action', 'link', overwrite: true);
// theme['action'] = 'link'
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

## Individual components

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

## Modal utility

`ModalUtil` builds a complete modal component tree with Alpine.js interactivity:

```php
use Juaniquillo\BackendComponents\Utils\ModalUtil;

$modal = ModalUtil::make(
    content: 'Hello World',
    button: ComponentBuilder::make(ComponentEnum::BUTTON)
        ->setContent('Open')
        ->setAttribute('@click', 'showModal = true'),
    title: ComponentBuilder::make(ComponentEnum::H2)->setContent('Title'),
    footer: ComponentBuilder::make(ComponentEnum::DIV)->setContent('Footer'),
)
    ->setAttribute('id', 'my-modal')
    ->setTheme('modal', 'lg')
    ->getComponent();
```

The modal is composed from `DIV` components with Alpine.js attributes — no separate blade template or slots needed.

## Table utilities

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

## Serialization / Factory

```php
$array = $component->toArray();
$restored = ComponentFactory::fromArray($array);
```

## Cached components

`CachedBackendComponent` is a standalone component class that caches its rendered HTML output. It uses the `IsCachable` trait and stores cached HTML to disk via PSR-16 (`Psr\SimpleCache\CacheInterface`).

```php
use Juaniquillo\BackendComponents\Components\CachedBackendComponent;

$button = new CachedBackendComponent(ComponentEnum::BUTTON);
$html = $button->getCachedHtml();   // renders + caches on first call
$html = $button->getCachedHtml();   // served from cache on subsequent calls
$button->clearCache();              // invalidates the cached entry
```

The cache key is generated from `md5(json_encode($toArray()))` — same component state always produces the same key.

Default cache directory: `cache/backend-components/` in the project root.

Livewire components bypass caching automatically.

Cache components that are expensive to render and whose content doesn't change per-request — such as documentation pages, static navigation, footer blocks, or reusable layout sections. Avoid caching components with dynamic or user-specific content unless you handle invalidation.

### Cache configuration

```php
$component = new CachedBackendComponent(ComponentEnum::DIV);
$component->setCacheDirectory('/custom/path'); // override default
$component->disableCache();                    // bypass cache entirely
$component->enableCache();                     // re-enable after disabling
$component->isCacheEnabled();                  // check current state
$component->getCacheKey();                     // get the md5 hash key
```

### Using the IsCachable trait directly

Any component class can use the `IsCachable` trait for caching:

```php
use Juaniquillo\BackendComponents\Concerns\IsCachable;

class MyCustomComponent implements BackendComponent {
    use IsBackendComponent, IsCachable;
    // ...
}
```

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

## Local component building (for apps consuming the package)

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

## Key patterns

- **ComponentBuilder** (`src/Builders/ComponentBuilder.php`) — fluent builder with `setContent()`, `setAttribute()`, `setTheme()`, `setLivewire()`, `setSetting()` methods. Returns `static` for chaining.
- **ComponentEnum** (`src/Enums/ComponentEnum.php`) — defines all available component types. Values are dotted view paths (e.g., `'form.datalist'`).
- **MainBackendComponent** (`src/MainBackendComponent.php`) — implements `Htmlable`; renders via `{{ $component }}` in Blade.
- **Themes** live in `resources/views/_themes/tailwind/`. Each file returns a PHP array keyed by variant name.
- **Serialization**: `$component->toArray()` / `ComponentFactory::fromArray($array)` preserves full component tree recursively.
- **Livewire**: Use `->setLivewire()`, `->setLivewireKey('key')`, `->setLivewireParams(['param' => 'value'])`.
- **Themes accumulate by default**: `->setTheme('action', 'success')` then `->setTheme('action', 'error')` produces `['success', 'error']`. Use `->setTheme('action', 'error', overwrite: true)` to replace.

## Builders & local resolution

| Builder              | Component path                      | Theme path                          |
|----------------------|-------------------------------------|-------------------------------------|
| `ComponentBuilder`   | package `resources/views/components/` | package `resources/views/_themes/tailwind/` |
| `LocalComponentBuilder` | app `resources/views/components/`   | app `resources/views/_themes/tailwind/` |
| `LocalThemeComponentBuilder` | package `resources/views/components/` | app `resources/views/_themes/tailwind/` |

Use `ComponentBuilder::make(ComponentEnum::BUTTON)->useLocal()` to resolve from app views instead of package views.
