<?php

namespace App\Filament\Resources\Users\Tables;

use App\Models\Airport;
use App\Models\Enums\UserState;
use Filament\Notifications\Notification;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('ident')
                    ->label('ID')
                    ->searchable(['pilot_id'])
                    ->sortable(),

                TextColumn::make('callsign')
                    ->toggleable()
                    ->label(__('flights.callsign'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('name')
                    ->label(__('common.name'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email')
                    ->toggleable()
                    ->label(__('common.email'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->toggleable()
                    ->label(__('common.registered_on'))
                    ->dateTime('d/m/Y')
                    ->sortable(),

                TextColumn::make('home_airport_id')
                    ->label(__('airports.home'))
                    ->toggleable()
                    ->sortable()
                    ->searchable(),

                TextColumn::make('state')
                    ->label(__('common.state'))
                    ->badge()
                    ->color(fn (int $state): string => match ($state) {
                        UserState::PENDING => 'warning',
                        UserState::ACTIVE  => 'success',
                        default            => 'info',
                    })
                    ->formatStateUsing(fn (int $state): string => UserState::label($state))
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                TrashedFilter::make(),

                SelectFilter::make('home_airport_id')
                    ->label(__('airports.home'))
                    ->relationship('home_airport', 'icao')
                    ->getOptionLabelFromRecordUsing(fn (Airport $record): string => $record->icao.' - '.$record->name)
                    ->searchable()
                    ->preload(),

                SelectFilter::make('curr_airport_id')
                    ->label(__('airports.current'))
                    ->relationship('current_airport', 'icao')
                    ->getOptionLabelFromRecordUsing(fn (Airport $record): string => $record->icao.' - '.$record->name)
                    ->searchable()
                    ->preload(),

                SelectFilter::make('state')
                    ->label(__('common.state'))
                    ->options(UserState::labels()),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
                ForceDeleteAction::make(),
                RestoreAction::make(),
                // Add custom approve/reject actions directly in the table configuration
                Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->hidden(fn ($record) => $record->state !== UserState::PENDING)
                    ->requiresConfirmation()
                    ->modalHeading('Approve user')
                    ->modalDescription('Are you sure you want to approve this user? They will receive an email notification and will be able to access the dashboard.')
                    ->modalSubmitActionLabel('Yes, approve')
                    ->action(function ($record) {
                        $oldState = $record->state;
                        $record->state = UserState::ACTIVE;
                        $record->save();
                        
                        // Fire event which will handle sending the appropriate notification
                        event(new \App\Events\UserStateChanged($record, $oldState));
                        
                        \Illuminate\Support\Facades\Log::info('User ' . $record->ident . ' was approved by ' . auth()->user()->ident);
                        
                        \Filament\Notifications\Notification::make()
                            ->success()
                            ->title('User approved successfully')
                            ->send();
                    }),
                Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->hidden(fn ($record) => $record->state !== UserState::PENDING)
                    ->requiresConfirmation()
                    ->modalHeading('Reject user')
                    ->modalDescription('Are you sure you want to reject this user? They will receive an email notification.')
                    ->modalSubmitActionLabel('Yes, reject')
                    ->action(function ($record) {
                        $oldState = $record->state;
                        $record->state = UserState::REJECTED;
                        $record->save();
                        
                        // Send rejection notification
                        $record->notify(new \App\Notifications\Messages\UserRejected($record));
                        
                        event(new \App\Events\UserStateChanged($record, $oldState));
                        
                        \Illuminate\Support\Facades\Log::info('User ' . $record->ident . ' was rejected by ' . auth()->user()->ident);
                        
                        \Filament\Notifications\Notification::make()
                            ->success()
                            ->title('User rejected successfully')
                            ->send();
                    }),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    // Add custom bulk approve/reject actions
                    
                    BulkAction::make('approve')
                        ->label('Approve')
                        ->color('success')
                        ->icon('heroicon-o-check')
                        ->requiresConfirmation()
                        ->action(function (\Illuminate\Database\Eloquent\Collection $records) {
                            foreach ($records as $user) {
                                if ($user->state === \App\Models\Enums\UserState::PENDING) {
                                    $oldState = $user->state;
                                    $user->state = \App\Models\Enums\UserState::ACTIVE;
                                    $user->save();
                                    
                                    // Fire event which will handle sending the appropriate notification
                                    event(new \App\Events\UserStateChanged($user, $oldState));
                                }
                            }
                            Notification::make()
                                ->success()
                                ->title('Users approved successfully')
                                ->send();
                        }),
                    BulkAction::make('reject')
                        ->label('Reject')
                        ->color('danger')
                        ->icon('heroicon-o-x-mark')
                        ->requiresConfirmation()
                        ->action(function (\Illuminate\Database\Eloquent\Collection $records) {
                            foreach ($records as $user) {
                                if ($user->state === \App\Models\Enums\UserState::PENDING) {
                                    $user->state = \App\Models\Enums\UserState::REJECTED;
                                    $user->save();
                                    
                                    // Send rejection notification
                                    $user->notify(new \App\Notifications\Messages\UserRejected($user));
                                }
                            }
                            \Filament\Notifications\Notification::make()
                                ->success()
                                ->title('Users rejected successfully')
                                ->send();
                        }),
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
