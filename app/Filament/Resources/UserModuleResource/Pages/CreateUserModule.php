<?php

namespace App\Filament\Resources\UserModuleResource\Pages;

use App\Filament\Resources\UserModuleResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateUserModule extends CreateRecord
{
    protected static string $resource = UserModuleResource::class;
}
