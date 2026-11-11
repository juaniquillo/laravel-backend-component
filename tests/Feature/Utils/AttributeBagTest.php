<?php

declare(strict_types=1);

namespace Feature\Utils;

use Juaniquillo\BackendComponents\Components\DefaultAttributeBag;
use Juaniquillo\BaseBackendComponent\Contracts\AttributeBag;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AttributeBagTest extends TestCase
{
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
