<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\Role;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        $roleId = $this->data['role_id'] ?? null;

        if (! $roleId) {
            return;
        }

        $role = Role::find($roleId);

        if ($role) {
            $this->record->syncRoles([$role]);
        }
    }
}
