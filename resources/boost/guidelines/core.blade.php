@php
    // Laravel Boost — AI Guidelines for juaniquillo/laravel-backend-component
    // Auto-loaded when the user runs `php artisan boost:install`
@endphp
## Laravel Backend Component

This package lets you build dynamic, class-based HTML components in PHP. Instead of writing Blade HTML directly, you compose component trees via PHP objects and render them with `{{ $component }}` (components implement Laravel's `Htmlable`).

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

Theme files are PHP arrays in `resources/views/_themes/tailwind/`:

```php
$button = ComponentBuilder::make(ComponentEnum::BUTTON)
    ->setTheme('action', 'success')
    ->setThemes(['action' => 'success', 'size' => 'lg']);
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

- **Block:** DIV, PARAGRAPH
- **Inline:** BUTTON, LINK, IMG, SPAN, BOLD, EM, ITALIC, STRONG, SMALL
- **Headers:** H1–H6
- **Form:** FORM, LABEL, LEGEND, FIELDSET, TEXT_INPUT, FILE_INPUT, EMAIL_INPUT, SEARCH_INPUT, PASSWORD_INPUT, CHECKBOX_INPUT, HIDDEN_INPUT, RADIO_INPUT, DATALIST, TEXTAREA, SELECT, OPTGROUP, OPTION
- **Table:** TABLE, THEAD, TBODY, TFOOT, TR, TH, TD, CAPTION, COLGROUP, COL
- **Lists:** OL, UL, LI
- **Details:** DETAILS, SUMMARY
- **Layers:** DIALOG
- **Custom:** MODAL, COLLECTION, TEMPLATE

### View conventions

Each component Blade template follows:
1. `@props(['attrs' => null])`
2. `@php` block extracting `$serverAttrs`, `$content`, `$slot`
3. Renders with `{{ $attributes->merge($serverAttrs) }}>{{ $content }}{{ $slot }}`
4. Self-closing tags (input, img, col) use `/>`

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
