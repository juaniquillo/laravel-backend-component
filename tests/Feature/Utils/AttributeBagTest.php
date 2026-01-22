<?php

declare(strict_types=1);

namespace Feature\Utils;

use Juaniquillo\BackendComponents\Builders\ComponentBuilder;
use Juaniquillo\BackendComponents\Components\DefaultAttributeBag;
use Juaniquillo\BackendComponents\Contracts\AttributeBag;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AttributeBagTest extends TestCase
{
    #[Test]
    public function a_bag_can_be_created()
    {
        $bag = new DefaultAttributeBag(
            attributes: [],
        );

        $this->assertInstanceOf(AttributeBag::class, $bag);
        $this->assertEmpty($bag->getAttributes());
        $this->assertEmpty($bag->themes);
        $this->assertEmpty($bag->slots);
    }

    #[Test]
    public function a_bag_can_be_created_with_attributes()
    {
        $attributes = [
            'class' => 'test-class',
            'id' => 'test-id',
        ];

        $bag = new DefaultAttributeBag(
            attributes: $attributes,
        );

        $this->assertInstanceOf(AttributeBag::class, $bag);
        $this->assertEquals($attributes, $bag->getAttributes());
    }

    #[Test]
    public function a_bag_can_merge_classes()
    {
        $attributes = [
            'class' => 'test-class',
        ];

        $themes = 'theme-class';

        $bag = new DefaultAttributeBag(
            attributes: $attributes,
            themes: $themes,
        );

        $this->assertInstanceOf(AttributeBag::class, $bag);
        $this->assertEquals('test-class theme-class', $bag->getAttributes()['class']);
    }

    #[Test]
    public function a_bag_can_handle_empty_classes()
    {
        $attributes = [
            'class' => '',
        ];

        $themes = 'theme-class';

        $bag = new DefaultAttributeBag(
            attributes: $attributes,
            themes: $themes,
        );

        $this->assertInstanceOf(AttributeBag::class, $bag);
        $this->assertEquals('theme-class', $bag->getAttributes()['class']);
    }

    #[Test]
    public function a_bag_can_handle_no_classes()
    {
        $attributes = [];
        $themes = 'theme-class';

        $bag = new DefaultAttributeBag(
            attributes: $attributes,
            themes: $themes,
        );

        $this->assertInstanceOf(AttributeBag::class, $bag);
        $this->assertEquals('theme-class', $bag->getAttributes()['class']);
    }

    #[Test]
    public function a_bag_can_handle_no_themes()
    {
        $attributes = [
            'class' => 'test-class',
        ];

        $bag = new DefaultAttributeBag(
            attributes: $attributes,
        );

        $this->assertInstanceOf(AttributeBag::class, $bag);
        $this->assertEquals('test-class', $bag->getAttributes()['class']);
    }

    #[Test]
    public function a_bag_can_handle_slots()
    {
        $slots = [
            'slot1' => ComponentBuilder::make(ComponentEnum::SPAN),
            'slot2' => ComponentBuilder::make(ComponentEnum::BOLD),
        ];

        $bag = new DefaultAttributeBag(
            attributes: [],
            slots: $slots,
        );

        $this->assertInstanceOf(AttributeBag::class, $bag);
        $this->assertArrayHasKey('slot1', $bag->slots);
        $this->assertArrayHasKey('slot2', $bag->slots);

        $this->assertInstanceOf(BackendComponent::class, $bag->slots['slot1']);
        $this->assertInstanceOf(BackendComponent::class, $bag->slots['slot2']);

    }

    #[Test]
    public function a_bag_can_handle_settings()
    {
        $settings = [
            'setting1' => true,
            'setting2' => 'value',
        ];

        $bag = new DefaultAttributeBag(
            attributes: [],
            settings: $settings,
        );

        $this->assertInstanceOf(AttributeBag::class, $bag);
        $this->assertArrayHasKey('setting1', $bag->settings);
        $this->assertArrayHasKey('setting2', $bag->settings);

        $this->assertTrue($bag->settings['setting1']);
        $this->assertEquals('value', $bag->settings['setting2']);
    }
}
