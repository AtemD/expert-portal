<?php

namespace App\Filament\Resources\ClientResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;
use Ysfkaya\FilamentPhoneInput\Tables\PhoneColumn;
use Ysfkaya\FilamentPhoneInput\PhoneInputNumberType;
// use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Components\Radio;
use Filament\Tables\Columns\IconColumn;

class ContactsRelationManager extends RelationManager
{
    protected static string $relationship = 'contacts';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->string()
                    ->minLength(2)
                    ->maxLength(255),
                TextInput::make('email')
                    ->required()
                    ->email()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                PhoneInput::make('phone')
                    ->label('Phone Number')
                    ->required()
                    ->validateFor(
                        lenient: false
                    )
                    ->validationMessages([
                        '*' => 'The :attribute is invalid.',
                    ])
                    ->defaultCountry('SS')
                    ->initialCountry('ss')
                    ->onlyCountries(['ss', 'ke', 'ug', 'us']),
                Radio::make('is_primary_contact')
                    ->label('Is this a primary contact?')
                    ->boolean()
                    ->inline()
                    ->inlineLabel(false),
                // ToggleButtons::make('is_primary_contact')
                //     ->label('Is this a primary contact?')
                //     ->boolean()
                //     ->inline()
                //     ->inlineLabel(false)
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('email'),
                PhoneColumn::make('phone')->displayFormat(PhoneInputNumberType::INTERNATIONAL),
                TextColumn::make('client.name'),
                Tables\Columns\BooleanColumn::make('is_primary_contact')
                    ->label('Is Primary Contact')
                    ->action(function ($record, $column) {
                        $name = $column->getName();
                        $record->update([
                            $name => !$record->$name
                        ]);
                    }),
                // IconColumn::make('is_primary_contact')
                //     ->label('Is Primary Contact')
                //     ->color(fn(string $state): string => match ($state) {
                //         '1' => 'info',
                //         '0' => 'warning',
                //     })
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
