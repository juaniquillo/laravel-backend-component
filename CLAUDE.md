# Laravel Backend Component — AI Guide

## Overview

A Laravel package for building dynamic, class-based HTML components in PHP. Instead of blade HTML, you compose component trees via PHP objects and render them as HTML through Laravel's `x-dynamic-component`.

Components implement `Htmlable` so they render in Blade via `{{ $component }}`.

@AGENTS.md

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
