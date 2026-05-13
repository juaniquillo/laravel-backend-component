@php
    // Laravel Boost — AI Guidelines for juaniquillo/laravel-backend-component
    // Auto-loaded when the user runs `php artisan boost:install`
@endphp
## Laravel Backend Component

This package lets you build dynamic, class-based HTML components in PHP. Instead of writing Blade HTML directly, you compose component trees via PHP objects and render them with:

```php
{{ $component }}
```

Components implement Laravel's `Htmlable`.

### Creating components

Use the `ComponentBuilder` with a `ComponentEnum`:

```php
use Juaniquillo\BackendComponents\Builders\ComponentBuilder;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;

$button = ComponentBuilder::make(ComponentEnum::BUTTON);
$div    = ComponentBuilder::make(ComponentEnum::DIV);
```

### Content management

Use **`setContent()`** for a single item and **`setContents()`** for multiple items at once:

```php
$div = ComponentBuilder::make(ComponentEnum::DIV)
    ->setContent('Hello');                // single (no key)
    ->setContent('World', 'key_1');       // single with key
    ->setContents([...]);                 // batch
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

### Individual components

For simple use cases without the enum, a concrete `DivComponent` is available:

```php
use Juaniquillo\BackendComponents\Components\Individual\DivComponent;

$div = new DivComponent;
$div->setAttribute('class', 'my-class');
$div->setContent('Hello');
```

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

### Settings

```php
$component = ComponentBuilder::make(ComponentEnum::MODAL)
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
- **Custom:** `MODAL`

### View conventions

Each component Blade template follows:

```blade
@props(['attrs' => null])
@php
    $serverAttrs = [];
    $content = null;
    $slot = $slot ?? null;
    if ($attrs) {
        $serverAttrs = $attrs->getAttributes();
        $content = $attrs->content;
    }
@endphp
<element {{ $attributes->merge($serverAttrs) }}>{{ $content }}{{ $slot }}</element>
```

Self-closing tags (input, img, col) use `/>` instead.

### Local resolution

For apps consuming the package, three builders control which `resources/views/` directory resolves components and themes:

- **`ComponentBuilder`** — package views for both components and themes
- **`LocalComponentBuilder`** — app views for both components and themes
- **`LocalThemeComponentBuilder`** — package views for components, app views for themes

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
