<?php

declare(strict_types=1);

namespace Tests\Unit\Concerns;

use Juaniquillo\BackendComponents\Components\Individual\DivComponent;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;
use Juaniquillo\BackendComponents\Factories\ComponentFactory;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

use function Juaniquillo\BackendComponents\backendComponentNamespace;

class HasPathTest extends TestCase
{
    #[Test]
    public function view_path_points_to_the_package_view(): void
    {
        $component = ComponentFactory::make(ComponentEnum::DIV);

        $this->assertEquals(
            backendComponentNamespace().'components.div',
            $component->getViewPath(),
        );

        $this->assertTrue(view()->exists($component->getViewPath()));
    }

    #[Test]
    public function view_path_omits_the_namespace_for_local_components(): void
    {
        $component = ComponentFactory::make(ComponentEnum::DIV)
            ->useLocal();

        $this->assertEquals('components.div', $component->getViewPath());
    }

    #[Test]
    public function view_path_includes_a_custom_path(): void
    {
        $component = ComponentFactory::make('img')
            ->setPath('inline');

        $this->assertEquals(
            backendComponentNamespace().'components.inline.img',
            $component->getViewPath(),
        );

        $this->assertTrue(view()->exists($component->getViewPath()));
    }

    #[Test]
    public function individual_component_view_path_is_the_component_path(): void
    {
        $component = DivComponent::make();

        $this->assertEquals(
            backendComponentNamespace().'components.div',
            $component->getViewPath(),
        );

        $this->assertEquals($component->getComponentPath(), $component->getViewPath());
    }
}
