<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClientResource\Pages;
use App\Filament\Resources\ClientResource\RelationManagers;
use App\Models\Client;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Select;
use App\Models\ContractStatus;

class ClientResource extends Resource
{
    protected static ?string $model = Client::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->required(),
                Select::make('contract_status_id')
                ->label('contract_status')
                ->relationship('ContractStatus', 'name')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('contractStatus.name')
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'Pending' => 'gray',
                    'Active' => 'success',
                    'Terminated' => 'danger',
                }),
                TextColumn::make('contacts.name')
                ->listWithLineBreaks()
                ->bulleted()
                ->limitList(3)
                ->expandableLimitedList()
                ->placeholder('No contacts.'),
                TextColumn::make('platforms.name')
                ->listWithLineBreaks()
                ->limitList(5)
                ->expandableLimitedList()
                ->placeholder('No platforms.'),
                TextColumn::make('sites.name')
                ->listWithLineBreaks()
                ->limitList(5)
                ->expandableLimitedList()
                ->placeholder('No sites.'),
            ])
            
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ContactsRelationManager::class,
            RelationManagers\PlatformsRelationManager::class,
            RelationManagers\SitesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListClients::route('/'),
            'create' => Pages\CreateClient::route('/create'),
            'edit' => Pages\EditClient::route('/{record}/edit'),
        ];
    }
}
