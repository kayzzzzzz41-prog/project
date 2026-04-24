<?php

namespace App\Filament\Resources\Contracts\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ContractForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
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
}