<?php

declare(strict_types=1);

namespace Tests\Unit\Components;

use Juaniquillo\BackendComponents\Contracts\LivewireComponent;
use Juaniquillo\BackendComponents\Deserializers\ComponentDeserializer;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;
use Juaniquillo\BackendComponents\Factories\ComponentFactory;
use Juaniquillo\BackendComponents\MainBackendComponent;
use Juaniquillo\BackendComponents\Themes\DefaultThemeManager;
use Juaniquillo\BackendComponents\Themes\LocalThemeManager;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

use function Juaniquillo\BackendComponents\backendComponentNamespace;

class SimpleComponentTest extends TestCase
{
    #[Test]
    public function a_component_can_be_created_using_main_class()
    {
        $component = new MainBackendComponent('div');

        $this->assertEquals('div', $component->getName());
    }

    #[Test]
    public function a_component_can_be_created_using_a_builder()
    {
        $component = ComponentFactory::make('div');

        $this->assertEquals('div', $component->getName());
    }

    #[Test]
    public function a_component_can_be_created_using_a_builder_and_enum()
    {
        $component = ComponentFactory::make(ComponentEnum::DIV);

        $this->assertEquals('div', $component->getName());
    }

    #[Test]
    public function a_local_component_component_can_be_created()
    {
        $component = ComponentFactory::make(ComponentEnum::DIV);

        $this->assertStringStartsWith(backendComponentNamespace(), $component->getComponentPath());

        $component->useLocal();

        $this->assertStringStartsNotWith(backendComponentNamespace(), $component->getComponentPath());
    }

    #[Test]
    public function the_namespace_can_be_set()
    {
        $component = ComponentFactory::make(ComponentEnum::DIV);

        $this->assertEquals(backendComponentNamespace(), $component->getNamespace());

        $component->setNamespace('other-namespace::');

        $this->assertEquals('other-namespace::', $component->getNamespace());
    }

    #[Test]
    public function the_path_can_be_set()
    {
        $component = ComponentFactory::make(ComponentEnum::DIV);

        $this->assertEquals(backendComponentNamespace(), $component->getPath());

        $component->setPath('other.path');

        $this->assertEquals(backendComponentNamespace().'other.path', $component->getPath());
    }

    #[Test]
    public function a_component_string_representation_is_a_json_object()
    {
        $component = ComponentFactory::make(ComponentEnum::DIV);

        $this->assertJson($component->__toString());
    }

    #[Test]
    public function the_theme_manager_can_be_overwritten_after_an_instance_is_created()
    {
        $component = new MainBackendComponent('div');

        $this->assertInstanceOf(DefaultThemeManager::class, $component->getThemeManager());

        $component->setThemeManager(new LocalThemeManager);

        $this->assertInstanceOf(LocalThemeManager::class, $component->getThemeManager());
    }

    #[Test]
    public function a_component_can_be_recreated_from_an_array()
    {
        $component = ComponentFactory::make(ComponentEnum::DIV)
            ->setContents([
                'span_1' => ComponentFactory::make(ComponentEnum::SPAN)
                    ->setContent('inside a span'),
                'span_2' => ComponentFactory::make(ComponentEnum::SPAN)
                    ->setContent(
                        ComponentFactory::make(ComponentEnum::LINK)
                            ->setAttribute('href', 'https://google.com')
                            ->setContent('this is a link')
                            ->setTheme('action', 'success')
                    ),
            ])
            ->setAttribute('id', 'div_id')
            ->setTheme('display', 'block');

        $this->blade('{{ $component }}', [
            'component' => $component,
        ])
            ->assertSee('<div', false);

        $componentArray = $component->toArray();

        $recreatedComponent = ComponentDeserializer::fromArray($componentArray);

        $this->blade('{{ $recreatedComponent }}', [
            'recreatedComponent' => $recreatedComponent,
        ])
            ->assertSee('<div', false);

        $this->assertEquals($componentArray, $recreatedComponent->toArray());
    }

    #[Test]
    public function livewire_state_is_not_serialized()
    {
        $component = ComponentFactory::make(LivewireComponent::class)
            ->setLivewire()
            ->setLivewireKey('livewire-key')
            ->setLivewireParams([
                'callback' => static fn (): string => 'not serializable',
            ]);

        $componentArray = $component->toArray();

        $this->assertArrayNotHasKey('isLivewire', $componentArray);
        $this->assertArrayNotHasKey('livewireKey', $componentArray);
        $this->assertArrayNotHasKey('livewireParams', $componentArray);
        $this->assertNotFalse(\json_encode($componentArray));

        $recreatedComponent = ComponentDeserializer::fromArray($componentArray);

        $this->assertEquals($componentArray, $recreatedComponent->toArray());

    }

    #[Test]
    public function a_component_with_a_different_path_can_be_recreated_from_an_array()
    {
        $component = ComponentFactory::make('img')
            ->setPath('inline')
            ->setContent('Custom path content');

        $this->blade('{{ $component }}', [
            'component' => $component,
        ])
            ->assertSee('<img', false);

        $componentArray = $component->toArray();

        $recreatedComponent = ComponentDeserializer::fromArray($componentArray);

        $this->blade('{{ $recreatedComponent }}', [
            'recreatedComponent' => $recreatedComponent,
        ])
            ->assertSee('<img', false);

        $this->assertEquals($componentArray, $recreatedComponent->toArray());
    }
}
