@php
    // Laravel Boost, AI Guidelines for juaniquillo/laravel-backend-component
    // Auto-loaded when the user runs `php artisan boost:install`
@endphp
## Laravel Backend Component

This package lets you build dynamic, dynamic HTML components in PHP. Instead of writing Blade HTML directly, you compose component trees via PHP objects and render them with:

```php
@{{ $component }}
```

Components implement Laravel's `Htmlable`.

### Creating components

Use the `ComponentBuilder` with a `ComponentEnum`:

```php
use Juaniquillo\BackendComponents\Builders\ComponentBuilder;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;

$button = ComponentBuilder::make(ComponentEnum::BUTTON);
$div    = ComponentBuilder::make(ComponentEnum::DIV);
// Or directly, second param controls theme resolution:
$div = new MainBackendComponent('div');                        // package themes
$div = new MainBackendComponent('div', new LocalThemeManager); // app-local themes
```

### Content management

Use **`setContent()`** for a single item and **`setContents()`** for multiple items at once:

```php
$div = ComponentBuilder::make(ComponentEnum::DIV)
    ->setContent('Hello');                // single (no key)
    ->setContent('World', 'key_1');       // single with key
    ->setContents([...]);                 // batch (ignores keys)
    ->setContents([...], overwrite: true); // batch with keys (overwrites existing)
$div->prependContent('First');            // Prepend
$div->prependContent('Really', 'k0');     // Prepend with key
$div->unsetContent();                     // Clear all
$div->unsetContent('key_1');              // Remove by key
```

### Attributes

Use **`setAttribute()`** for a single attribute and **`setAttributes()`** for multiple at once:

```php
$div = ComponentBuilder::make(ComponentEnum::DIV)
    ->setAttribute('id', 'my-id');        // single
    ->setAttribute('class', 'custom-class');
    ->setAttributes(['data-foo' => 'bar']); // batch
```

### Themes (Tailwind CSS)

Theme files are PHP arrays in `resources/views/_themes/tailwind/`, keyed by variant name:

```php
// resources/views/_themes/tailwind/action.blade.php
return [
    'default' => "whitespace-nowrap bg-blue-700 hover:bg-blue-800",
    'success' => "whitespace-nowrap bg-green-700 hover:bg-green-800",
    'error'   => "whitespace-nowrap bg-red-700 hover:bg-red-800",
    'link'    => "text-blue-500 underline hover:no-underline",
];
```

```php
$button = ComponentBuilder::make(ComponentEnum::BUTTON)
    ->setTheme('action', 'success')                       // single variant
    ->setTheme('table', ['th', 'th-dark'])                // array of variant keys
    ->setThemes(['action' => 'success', 'size' => 'lg']); // batch
```

Themes accumulate by default, calling `setTheme` with the same name appends rather than replaces:

```php
$button = ComponentBuilder::make(ComponentEnum::BUTTON)
    ->setTheme('action', 'success')
    ->setTheme('action', 'error');
// theme['action'] = ['success', 'error']

// Use overwrite: true to replace instead
$button->setTheme('action', 'link', overwrite: true);
// theme['action'] = 'link'
```

### Individual components

`DivComponent` is both a utility **and** a blueprint for creating new targeted component classes that bypass the enum/builder entirely. To create a new individual component, duplicate the `DivComponent` pattern:

1. Put the class in `src/Components/Individual/`
2. Implement `BackendComponent`, `IndividualComponent`, `ThemeComponent`, `Htmlable` (omit `ContentsComponent`+`HasContent` for self-closing elements)
3. Use traits `IsBackendComponent`, `IsThemeable` (add `HasContent` only if the component can hold children)
4. Define `getName()` to return the `ComponentEnum` value (or any dotted view path)
5. Define `getComponentPath()`, `getPathOnly()` and `getViewPath()` following the existing convention
6. Wire up `getAttributeBag()`, `toHtml()`, `toArray()`, and a static `make()` factory
7. Mark the class `final` (optional but recommended)

```php
use Juaniquillo\BackendComponents\Components\Individual\DivComponent;

$div = new DivComponent;
$div->setAttribute('class', 'my-class');
$div->setContent('Hello');
```

Currently only `DivComponent` exists in this category, add more as needed. `DivComponent` does not use `HasPath`, so it defines `getViewPath()` itself.

### Third-party components

Third-party packages can implement `Contracts\BackendComponent` in their own namespace and choose their own view and rendering strategy. The package's concerns (`HasContent`, `HasPath`, `HasProps`, `HasSettings`, `IsBackendComponent`, `IsThemeable`) are opt-in traits.

`PropsComponent` in this package is only a reference implementation of those contracts, not a required base class.

### Table utilities

TableUtil builds a complete `<table>` from head/body arrays. CellBag passes per-cell data:

```php
use Juaniquillo\BackendComponents\Utils\TableUtil;
use Juaniquillo\BackendComponents\Utils\CellBag;

$table = TableUtil::make(
    head: ['Name', 'Email', 'Role'],
    body: [
        ['Alice', 'alice@example.com', 'Admin'],
        [
            new CellBag(content: 'Bob', theme: ['color' => 'success']),
            'bob@example.com',
            'Editor',
        ],
    ],
)->getComponent();
```

### Modal utility

ModalUtil builds a complete modal tree with Alpine.js interactivity, no separate blade template or slots needed:

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

### Settings

```php
$component = ComponentBuilder::make(ComponentEnum::DIV)
    ->setSetting('transition', 'fade')
    ->setSettings(['setting_1' => 'value_1']);
```

### Livewire

```php
ComponentBuilder::make('my-livewire-component')
    ->setLivewire()
    ->setLivewireKey('my-key')
    ->setLivewireParams(['param' => 'value']);
```

### Available components

- **Template:** `TEMPLATE`
- **Collection:** `COLLECTION`
- **Block:** `DIV`, `PARAGRAPH`
- **Inline:** `BUTTON`, `LINK`, `IMG`, `SPAN`, `BOLD`, `EM`, `ITALIC`, `STRONG`, `SMALL`
- **Headers:** `H1`, `H2`, `H3`, `H4`, `H5`, `H6`
- **Form:** `FORM`, `LABEL`, `LEGEND`, `FIELDSET`, `TEXT_INPUT`, `FILE_INPUT`, `EMAIL_INPUT`, `SEARCH_INPUT`, `PASSWORD_INPUT`, `CHECKBOX_INPUT`, `HIDDEN_INPUT`, `RADIO_INPUT`, `DATALIST`, `TEXTAREA`, `SELECT`, `OPTGROUP`, `OPTION`
- **Table:** `TABLE`, `THEAD`, `TBODY`, `TFOOT`, `TR`, `TH`, `TD`, `CAPTION`, `COLGROUP`, `COL`
- **Lists:** `OL`, `UL`, `LI`
- **Details:** `DETAILS`, `SUMMARY`
- **Layers:** `DIALOG`

### View conventions

Every component resolves its template through `getViewPath()`:

| Component | `getViewPath()` |
|---|---|
| `ComponentBuilder::make(ComponentEnum::DIV)` | `backend-component::components.div` |
| `ComponentBuilder::make(ComponentEnum::DIV)->useLocal()` | `components.div` |
| `ComponentBuilder::make('img')->setPath('inline')` | `backend-component::components.inline.img` |

Templates are plain views that receive `$attributes`, `$slot` and `$settings`:

```blade
<element @{{ $attributes }}>@{{ $slot }}</element>
```

Self-closing tags (input, img, col) use `/>` instead. Use `@{{ $attributes->merge([...]) }}` when the element needs default attributes.

- Never declare props in these templates; props belong to custom `BackendComponent` implementations.
- `getComponentPath()` is the `x-dynamic-component` form and does not include the `components/` directory. Do not pass it to `view()` or `@@include`.
- `DivComponent` does not use `HasPath`, so it implements `getViewPath()` itself.

### Local resolution

For apps consuming the package, three builders control which `resources/views/` directory resolves components and themes:

- **`ComponentBuilder`**, package views for both components and themes
- **`LocalComponentBuilder`**, app views for both components and themes
- **`LocalThemeComponentBuilder`**, package views for components, app views for themes

```php
use Juaniquillo\BackendComponents\Builders\ComponentBuilder;
use Juaniquillo\BackendComponents\Builders\LocalComponentBuilder;
use Juaniquillo\BackendComponents\Builders\LocalThemeComponentBuilder;

$package     = ComponentBuilder::make(ComponentEnum::BUTTON);                  // package both
$local       = LocalComponentBuilder::make(ComponentEnum::BUTTON);              // app both
$localTheme  = LocalThemeComponentBuilder::make(ComponentEnum::BUTTON);         // package comp + app theme

// ComponentBuilder also supports ->useLocal() as a shorthand for LocalComponentBuilder
$component   = ComponentBuilder::make(ComponentEnum::BUTTON)->useLocal();
```

### Serialization

```php
$array = $component->toArray();
$restored = ComponentFactory::fromArray($array);
```

### Cached components

`CachedBackendComponent` caches its rendered HTML output to disk via PSR-16:

```php
use Juaniquillo\BackendComponents\Components\CachedBackendComponent;

$button = new CachedBackendComponent(ComponentEnum::BUTTON);
$html = $button->getCachedHtml();   // renders + caches
$html = $button->getCachedHtml();   // served from cache
$button->clearCache();              // invalidates

$button->setCacheDirectory('/custom/path'); // override default
$button->disableCache();                    // bypass cache
```

Default cache directory: `cache/backend-components/`. Livewire components bypass caching. Cache key: `md5(json_encode($toArray()))`. Best suited for static content like documentation, navigation, or footer blocks, avoid caching dynamic or user-specific content unless you handle invalidation.
