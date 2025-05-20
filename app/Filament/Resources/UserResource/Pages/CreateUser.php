<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\User;
use Filament\Actions;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Builder;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function getCreatedNotification(): ?Notification
    {
        $user = $this->record;
        $recipients = User::query()->whereHas('roles', function (Builder $query) {
            $query->where('name', '=', 'super_admin');
        })->get();
        return Notification::make()
            ->success()
            ->title('Nuevo Usuario')
            ->icon('heroicon-o-users')
            ->body("**Se creo el usuario: {$user->name}**")
            ->actions([
                Action::make('view')
                    ->label('Ver')
                    ->url(UserResource::getUrl('edit', ['record' => $user]))
                    ->markAsRead(),
            ])
            ->sendToDatabase($recipients);
    }
}
