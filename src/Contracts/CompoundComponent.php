<?php

declare(strict_types=1);

namespace Juaniquillo\BackendComponents\Contracts;

interface CompoundComponent extends BackendComponent, ContentComponent, LivewireComponent, PathComponent, SettingsComponent, SlotsComponent, ThemeComponent {}
