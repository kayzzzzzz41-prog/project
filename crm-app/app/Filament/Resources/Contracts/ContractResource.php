<?php

namespace App\Filament\Resources\Contracts;

use App\Filament\Resources\Contracts\Pages;
use App\Models\Contract;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Table;
use Filament\Tables;

class ContractResource extends Resource
{
    protected static ?string $model = Contract::class;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Договоры';
    protected static ?string $modelLabel = 'Договор';
    protected static ?string $pluralModelLabel = 'Договоры';

    public static function form(Schema $form): Schema
    {
        return $form->components([
            TextInput::make('name')
                ->label('Наименование')
                ->required(),

            TextInput::make('address')
                ->label('Адрес')
                ->required(),

            TextInput::make('contract_number')
                ->label('Номер договора')
                ->required()
                ->unique(ignoreRecord: true),

            TextInput::make('tenant_phone')
                ->label('Телефон арендатора')
                ->required(),

            TextInput::make('tenant_iin')
                ->label('ИИН арендатора')
                ->required()
                ->maxLength(12),

                        TextInput::make('amount')
                ->label('Сумма (тенге)')
                ->numeric()
                ->required()
                ->minValue(0)
                ->rules(['min:0'])
                ->default(0),

            Toggle::make('is_paid')
                ->label('Оплачен')
                ->default(false),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('contract_number')
                ->label('№ Договора')
                ->searchable(),

            Tables\Columns\TextColumn::make('name')
                ->label('Наименование')
                ->searchable(),

            Tables\Columns\TextColumn::make('address')
                ->label('Адрес'),

            Tables\Columns\TextColumn::make('tenant_phone')
                ->label('Телефон'),

            Tables\Columns\TextColumn::make('tenant_iin')
                ->label('ИИН'),

            Tables\Columns\TextColumn::make('amount')
                ->label('Сумма'),

            Tables\Columns\IconColumn::make('is_paid')
                ->label('Оплачен')
                ->boolean(),
        ]);
    }

            public static function getPages(): array
            {
                return [
                    'index'  => Pages\ListContracts::route('/'),
                    'create' => Pages\CreateContract::route('/create'),
                    'edit'   => Pages\EditContract::route('/{record}/edit'),
                ];
            }       
}