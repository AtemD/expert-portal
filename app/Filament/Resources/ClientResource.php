<?php

namespace App\Filament\Resources;


use App\Enums\ContractStatus;
// use App\Events\EmailClient;
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
// use Filament\Tables\Columns\SelectColumn;
use Filament\Forms\Components\Select;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Actions\ExportBulkAction;
use Illuminate\Database\Eloquent\Collection;
use Filament\Forms\Components\TextArea;
use Filament\Tables\Columns\BooleanColumn;
// use App\Models\Contact;

use Illuminate\Support\Facades\Mail;
use App\Mail\EmailClient; // as EmailClientMail;

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
                Select::make('contract_status')
                    ->options(ContractStatus::class)
                    ->required(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('contract_status')
                    ->badge()
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
                    ->options(ContractStatus::class)
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
                    BulkAction::make('Email Clients')
                        ->icon('heroicon-o-envelope')
                        ->action(function (Collection $records, array $data): void {
                            // $data['subject'] = "test subject 2";
                            // $data['message'] = "test message 2";

                            foreach ($records as $record) {
                                // EmailClient::dispatch($record);
                
                                // if client has no contacts, no need to proceed further
                                if ($record->contacts->count() < 1) {
                                    return;
                                }

                                // if the client contract is not active, no need to proceed further 
                                if (!$record->contract_status->isActive()) {
                                    return;
                                }

                                // At this point the client has at least 1 contact and the clients contract status is active
                
                                // Obtain primary and secondary contacts 
                                $primaryContacts = collect();
                                $secondaryContacts = collect();
                                foreach ($record->contacts as $contact) {
                                    if ($contact->is_primary_contact) {
                                        $primaryContacts->push($contact->email);
                                    } else {
                                        $secondaryContacts->push($contact->email);
                                    }
                                }

                                // get the count of primary and secondary contacts to be used later to determine how to send the mail 
                                $primaryContactsCount = $primaryContacts->count();

                                // Set the primary and secondary contacts 
                                $primaryContact = "";
                                $updatedSecondaryContacts = collect();

                                // CONDITION 1: No primary contact with 0 or more secondary contacts 
                                if ($primaryContactsCount < 1) {
                                    // get/pluck a random secondary contact from the secondaryContacts collection and cc the rest if the remaining count is greater than or equal to 1
                                    $primaryContact = $secondaryContacts->random();
                                    
                                    // filter the secondary contacts to exclude the primary contact we just randomly took.
                                    $updatedSecondaryContacts = $secondaryContacts->reject(function ($value, $key) use ($primaryContact): bool {
                                        return $value === $primaryContact;
                                    });                                    
                                }

                                // CONDITION 2: One or more primary contacts, with 0 or more secondary contacts 
                                if ($primaryContactsCount >= 1) {

                                        $primaryContact = $primaryContacts->first();
                                        $updatedPrimaryContacts = $primaryContacts->reject(function ($value, $key) use ($primaryContact): bool {
                                            return $value === $primaryContact;
                                        });

                                        // merge the updatedSecondaryContact with the secondary contacts, incase we have multiple primary contacts
                                        $updatedSecondaryContacts = $secondaryContacts->merge($updatedPrimaryContacts);

                                }

                                Mail::to($primaryContact)
                                    ->cc($updatedSecondaryContacts->all())
                                    ->send(new EmailClient($data['subject'], $data['message']));
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
