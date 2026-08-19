# AGENTS.md — Laravel Backend Component

## Commands

- `composer test` — Run PHPUnit (Orchestra Testbench)
- `composer qa` — Run format (Pint) + typecheck (PHPStan) + tests in sequence
- `vendor/bin/pint` — Fix code style (enforces `declare_strict_types`)
- `vendor/bin/phpstan` — Static analysis at level 8 (memory limit: 1G)

**Order matters**: `qa` runs `format → analyse → test`. Run them in this order before committing.

## Testing

- Feature tests extend `Tests\TestCase` (Orchestra Testbench-based)
- Use `#[Test]` attribute (not `@test` docblock)
- Render components via `$this->blade('{{ $component }}', ['component' => $component])` with `assertSee`/`assertDontSee`
- Standard test method names: `empty_{component}`, `{component}_accepts_content`, `{component}_accepts_contents_array`, `{component}_accepts_attributes`, `{component}_accepts_theme`
- Nested content tests use `ComponentBuilder::make(ComponentEnum::OPTION)`

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

## Code style

- `declare(strict_types=1)` is enforced by Pint (`pint.json` rule)
- PSR-4: `Juaniquillo\BackendComponents` → `src/`
- Prefix global PHP functions with `\` (e.g., `\is_array()`, `\count()`, `\trim()`)
- Fluent return types (`: static`) on all setters
- Namespaced helpers in `src/helpers.php`; import with `use function Juaniquillo\BackendComponents\processThemes;`
- Trait-per-concern pattern: 8 traits in `src/Concerns/`