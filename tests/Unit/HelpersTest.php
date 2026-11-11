<?php

declare(strict_types=1);

namespace Tests\Unit;

use Juaniquillo\BackendComponents\MainBackendComponent;
use Juaniquillo\BackendComponents\Themes\DefaultThemeManager;
use Juaniquillo\BackendComponents\Themes\LocalThemeManager;
use Juaniquillo\BackendComponents\Utils\CellBag;
use Juaniquillo\BaseBackendComponent\Exceptions\IncorrectThemePathException;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

use function Juaniquillo\BackendComponents\backendComponentNamespace;
use function Juaniquillo\BackendComponents\isCellBag;
use function Juaniquillo\BackendComponents\makeBackendComponent;
use function Juaniquillo\BackendComponents\processLocalThemes;
use function Juaniquillo\BackendComponents\processThemes;

class HelpersTest extends TestCase
{
    #[Test]
    public function backend_component_namespace_returns_the_view_namespace()
    {
        $this->assertSame('backend-component::', backendComponentNamespace());
    }

    #[Test]
    public function make_backend_component_creates_a_component_with_the_default_manager()
    {
        $component = makeBackendComponent('div');

        $this->assertInstanceOf(MainBackendComponent::class, $component);
        $this->assertSame('div', $component->getName());
        $this->assertInstanceOf(DefaultThemeManager::class, $component->getThemeManager());
    }

    #[Test]
    public function make_backend_component_accepts_a_custom_theme_manager()
    {
        $component = makeBackendComponent('div', new LocalThemeManager);

        $this->assertInstanceOf(LocalThemeManager::class, $component->getThemeManager());
    }

    #[Test]
    public function process_themes_resolves_package_themes()
    {
        $this->assertSame('flex', processThemes(['display' => 'flex']));
    }

    #[Test]
    public function process_themes_returns_null_for_an_empty_array()
    {
        $this->assertNull(processThemes([]));
    }

    #[Test]
    public function process_local_themes_resolves_from_the_app_path()
    {
        $this->expectException(IncorrectThemePathException::class);

        processLocalThemes(['display' => 'flex']);
    }

    #[Test]
    public function is_cell_bag_identifies_cell_bags()
    {
        $this->assertTrue(isCellBag(new CellBag(content: 'Bob')));
        $this->assertFalse(isCellBag('Bob'));
        $this->assertFalse(isCellBag(makeBackendComponent('div')));
    }
}
