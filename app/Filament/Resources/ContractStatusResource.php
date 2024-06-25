<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContractStatusResource\Pages;
use App\Filament\Resources\ContractStatusResource\RelationManagers;
use App\Models\ContractStatus;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TextArea;
use Filament\Tables\Columns\TextColumn;

class ContractStatusResource extends Resource
{
    protected static ?string $model = ContractStatus::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = "Settings";

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->string()
                    ->minLength(2)
                    ->maxLength(255),
                TextInput::make('color')
                    ->required()
                    ->string()
                    ->minLength(2)
                    ->maxLength(255),
                TextArea::make('description')
                    ->required()
                    ->string()
                    ->minLength(3)
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('color'),
                TextColumn::make('description')->wrap(),
            ])
            ->filters([
                //
            ])
            ->actions([
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListContractStatuses::route('/'),
            'create' => Pages\CreateContractStatus::route('/create'),
            'edit' => Pages\EditContractStatus::route('/{record}/edit'),
        ];
    }
}
