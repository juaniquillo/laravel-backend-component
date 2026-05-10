---
name: backend-component-development
description: Build and compose dynamic HTML components in PHP using the juaniquillo/laravel-backend-component package — create component trees, apply Tailwind themes, manage settings, integrate Livewire, serialize/deserialize component structures, and resolve components locally.
---

# Backend Component Development

## When to Use This Skill
Use this skill when the user needs to:
- Create a new HTML component (button, div, table, form element, modal, etc.)
- Compose multiple components into a tree structure
- Apply or create Tailwind CSS theme classes to components
- Add Livewire integration to a component
- Work with named slots (title, footer, button, overlay)
- Serialize a component tree to an array or restore it from one
- Build custom Blade templates for new component types
- Resolve components or themes from the consuming app's local views

## Creating Components

Components are created via the builder, always starting with `ComponentBuilder::make()`:

```php
use Juaniquillo\BackendComponents\Builders\ComponentBuilder;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;

// Simple components
$button = ComponentBuilder::make(ComponentEnum::BUTTON);
$div = ComponentBuilder::make(ComponentEnum::DIV);
$input = ComponentBuilder::make(ComponentEnum::TEXT_INPUT);
$table = ComponentBuilder::make(ComponentEnum::TABLE);

// By string name (path)
$custom = ComponentBuilder::make('inline.button');
```

## Setting Content

Use **`setContent()`** for a single item and **`setContents()`** for multiple items at once:

```php
$div = ComponentBuilder::make(ComponentEnum::DIV)
    ->setContent('Hello')                               // single (no key)
    ->setContent('World', 'key_1')                      // single with key
    ->setContents([                                      // batch
        'item_1' => ComponentBuilder::make(ComponentEnum::SPAN)->setContent('A'),
        'item_2' => ComponentBuilder::make(ComponentEnum::SPAN)->setContent('B'),
    ]);
```

## Setting Attributes

Use **`setAttribute()`** for a single attribute and **`setAttributes()`** for multiple at once:

```php
$div = ComponentBuilder::make(ComponentEnum::DIV)
    ->setAttribute('id', 'my-id')
    ->setAttribute('class', 'custom-class')
    ->setAttributes(['data-foo' => 'bar', 'style' => 'display:none']);
```

## Composition (Nested Components)

Components can be nested by passing them as content:

```php
$card = ComponentBuilder::make(ComponentEnum::DIV)
    ->setAttribute('class', 'card')
    ->setContent(
        ComponentBuilder::make(ComponentEnum::H2)->setContent('Card Title')
    )
    ->setContent(
        ComponentBuilder::make(ComponentEnum::PARAGRAPH)->setContent('Card body text')
    );
```

## Applying Themes

Themes are predefined in `resources/views/_themes/tailwind/`. Each file returns a PHP array of Tailwind classes keyed by name:

```php
// Apply a single theme
$button = ComponentBuilder::make(ComponentEnum::BUTTON)
    ->setTheme('action', 'success');

// Apply multiple themes
$button = ComponentBuilder::make(ComponentEnum::BUTTON)
    ->setThemes([
        'action' => 'success',
        'size' => 'lg',
    ]);

// Theme classes merge into the HTML class attribute automatically
```

## Livewire Components

Wrap any component as a Livewire wrapper:

```php
$livewire = ComponentBuilder::make(ComponentEnum::DIV)
    ->setLivewire()
    ->setLivewireKey('unique-key')
    ->setLivewireParams(['userId' => 1, 'team' => 'engineering']);
```

## Local Resolution

For apps consuming the package, three builders control whether components/themes resolve from the package or the app's `resources/views/`:

```php
use Juaniquillo\BackendComponents\Builders\LocalComponentBuilder;
use Juaniquillo\BackendComponents\Builders\LocalThemeComponentBuilder;

// Resolves BOTH components and themes from the app's views
$local = LocalComponentBuilder::make(ComponentEnum::BUTTON);

// Resolves THEMES from the app's views, components from the package
$localTheme = LocalThemeComponentBuilder::make(ComponentEnum::BUTTON);

// ComponentBuilder::make()->useLocal() is equivalent to LocalComponentBuilder
$component = ComponentBuilder::make(ComponentEnum::BUTTON)->useLocal();
```

| Builder | Component path | Theme path |
|---|---|---|
| `ComponentBuilder` | package `views/components/` | package `views/_themes/tailwind/` |
| `LocalComponentBuilder` | app `views/components/` | app `views/_themes/tailwind/` |
| `LocalThemeComponentBuilder` | package `views/components/` | app `views/_themes/tailwind/` |

## Serialization

Persist and restore component trees:

```php
use Juaniquillo\BackendComponents\Factories\ComponentFactory;

// Serialize
$array = $component->toArray();

// Restore
$restored = ComponentFactory::fromArray($array);
```

This works recursively for nested content, themes, settings, and Livewire state.

## Guardrails
- Do NOT build HTML strings manually — always use the component builder and enum to ensure proper rendering.
- Do NOT apply CSS classes directly in Blade templates — use the theme system instead for maintainability.
- Always use `ComponentEnum` cases rather than raw strings when possible, to benefit from IDE autocompletion and type safety.
- Do NOT use `echo` or `{!! !!}` for component output — components implement `Htmlable` so `{{ $component }}` is safe and correct.
- For self-closing HTML elements (input, img, col), ensure the Blade template uses `/>` not `></tag>`.
