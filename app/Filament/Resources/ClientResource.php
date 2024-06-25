<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClientResource\Pages;
use App\Filament\Resources\ClientResource\RelationManagers;
use App\Models\Client;
use App\Models\Contact;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\MultiSelectFilter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Select;
use App\Models\ContractStatus;
use Filament\Tables\Enums\FiltersLayout;
use Ysfkaya\FilamentPhoneInput\PhoneInputNumberType;


class ClientResource extends Resource
{
    protected static ?string $model = Client::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->string()
                    ->minLength(2)
                    ->maxLength(255),
                Select::make('contract_status_id')
                    ->label('Contract Status')
                    ->required()
                    ->exists(table: ContractStatus::class, column: 'id')
                    ->relationship('ContractStatus', 'name'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('contractStatus.name')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Pending' => 'gray',
                        'Active' => 'success',
                        'Terminated' => 'danger',
                    })
                    ->searchable(),
                TextColumn::make('contacts')
                    ->listWithLineBreaks()
                    ->bulleted()
                    ->limitList(3)
                    ->expandableLimitedList()
                    ->formatStateUsing(function ($state, Client $client) {
                        return $state->name . ' ' . '[ ' . $state->phone . ' ]';
                    })
                    ->placeholder('No contacts.')
                    ->searchable(),
                TextColumn::make('platforms.name')
                    ->listWithLineBreaks()
                    ->limitList(5)
                    ->expandableLimitedList()
                    ->placeholder('No platforms.')
                    ->searchable(),
                TextColumn::make('sites.name')
                    ->listWithLineBreaks()
                    ->limitList(5)
                    ->expandableLimitedList()
                    ->placeholder('No sites.')
                    ->searchable(),
            ])

            ->filters([
                SelectFilter::make('contract_status')
                    ->label('Contract Status')
                    ->relationship('contractStatus', 'name')
                    ->preload(),
                MultiSelectFilter::make('platforms')
                    ->label('Platform')
                    ->relationship('platforms', 'name')
                    ->preload()
                    ->searchable(),
            ], layout: FiltersLayout::AboveContentCollapsible)

            ->actions([
                // Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
