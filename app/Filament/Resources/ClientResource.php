<?php

namespace App\Filament\Resources;

use App\Filament\Exports\ClientExporter;
use App\Filament\Resources\ClientResource\Pages;
use App\Filament\Resources\ClientResource\RelationManagers;
use App\Models\Client;
// use App\Models\Contact;
// use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Filters\MultiSelectFilter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
// use Illuminate\Database\Eloquent\Builder;
// use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Select;
use App\Models\ContractStatus;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Actions\ExportBulkAction;
use Illuminate\Database\Eloquent\Collection;
use Filament\Forms\Components\TextArea;
use Filament\Tables\Columns\BooleanColumn;

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
                TextColumn::make('contacts.name')
                    ->label('Contacts')
                    ->listWithLineBreaks()
                    ->bulleted()
                    // ->limitList(3)
                    ->expandableLimitedList()
                    ->placeholder('No contacts.')
                    ->searchable(),
                TextColumn::make('contacts.phone')
                    ->label('Phone')
                    ->listWithLineBreaks()
                    // ->bulleted()
                    // ->limitList(3)
                    ->expandableLimitedList()
                    ->placeholder('No phone.'),
                TextColumn::make('contacts.email')
                    ->label('Email')
                    ->listWithLineBreaks()
                    // ->bulleted()
                    // ->limitList(3)
                    ->expandableLimitedList()
                    ->placeholder('No email.'),
                BooleanColumn::make('contacts.is_primary_contact')
                    ->label('is Primary Contact')
                    ->listWithLineBreaks()
                    ->icon(fn(string $state): string => match ($state) {
                        '1' => 'heroicon-o-check-circle',
                        '0' => 'heroicon-o-x-circle',
                    }),
                TextColumn::make('platforms.name')
                    ->listWithLineBreaks()
                    ->limitList(2)
                    ->expandableLimitedList()
                    ->placeholder('No platforms.')
                    ->toggleable()
                    ->searchable(),
                TextColumn::make('sites.name')
                    ->listWithLineBreaks()
                    ->limitList(2)
                    ->expandableLimitedList()
                    ->placeholder('No sites.')
                    ->toggleable(isToggledHiddenByDefault: true)
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
                    ExportBulkAction::make()
                        ->exporter(ClientExporter::class),
                    BulkAction::make('Send Email')
                        ->icon('heroicon-o-envelope')
                        ->action(function (Collection $records, array $data): void {
                            // there is a possibility that there is no primary contact, or more than one primary contact, make sure to cover that scenario when sending email 
                            // Scenarios: 0/No primary contact, 1 primary contact, 2 or more primary contacts. 
                
                            // 0/No primary contact
                            // if there are no primary contacts, choose a random as primary, then cc the rest (cc only if the contact count in greater than 1)
                
                            // 1 primary contact (Ideal situation)
                            // if theres one primary contact, send to that contact, cc the rest (cc only if the contact count in greater than 1)
                            // if there is only one contact in the collection, make that contact the primary contact regardless of whether the contact is set as primary or not 
                
                            // 2+/more/multiple primary contacts. 
                            // if there are more than one primary contacts, send each the message directly, and cc the other contacts that are not primary (cc only if the contact count in greater than 1)
                
                            foreach ($records as $record) {
                                // obtain
                                dump($record->toArray());
                            }
                        })
                        ->form([
                            TextInput::make('subject')
                                ->required()
                                ->string()
                                ->minLength(2)
                                ->maxLength(255),
                            TextArea::make('message')
                                ->required()
                                ->string()
                                ->minLength(3)
                                ->maxLength(255),
                        ])
                        ->deselectRecordsAfterCompletion()
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
