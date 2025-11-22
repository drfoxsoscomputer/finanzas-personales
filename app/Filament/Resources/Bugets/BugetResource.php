<?php

namespace App\Filament\Resources\Bugets;

use App\Filament\Resources\Bugets\Pages\ManageBugets;
use App\Models\Buget;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BugetResource extends Resource
{
    protected static ?string $model = Buget::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'title';

    public static function getModelLabel(): string
    {
        return __('Buget');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Bugets');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->label('Usuario')
                    ->placeholder('Seleccione un usuario')
                    ->relationship('user', 'name')
                    ->required(),
                Select::make('category_id')
                    ->label('Categoría')
                    ->placeholder('Seleccione una categoría')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->createOptionForm([
                        TextInput::make('name')
                            ->label('Nombre')

                            ->required(),
                        Select::make('type')
                            ->label('Tipo')
                            ->options(['ingreso' => 'Ingreso', 'egreso' => 'Egreso'])
                            ->required(),
                    ]),
                TextInput::make('assined_amount')
                    ->label('Monto Asignado')
                    ->required()
                    ->numeric(),
                TextInput::make('spend_amount')
                    ->label('Monto Gastado')
                    ->required()
                    ->numeric()
                    ->disabled()
                    ->default(0.0),
                // TextInput::make('month')
                //     ->required(),
                Select::make('month')
                ->label('Mes')
                ->placeholder('Seleccione un mes')
                    ->required()
                    ->options([
                        'January' => 'Enero',
                        'February' => 'Febrero',
                        'March' => 'Marzo',
                        'April' => 'Abril',
                        'May' => 'Mayo',
                        'June' => 'Junio',
                        'July' => 'Julio',
                        'August' => 'Agosto',
                        'September' => 'Septiembre',
                        'October' => 'Octubre',
                        'November' => 'Noviembre',
                        'December' => 'Diciembre',
                    ]),
                TextInput::make('year')
                    ->required()
                    ->numeric()
                    ->default(2025),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('user.name')
                    ->label('User'),
                TextEntry::make('category.name')
                    ->label('Category'),
                TextEntry::make('assined_amount')
                    ->numeric(),
                TextEntry::make('spend_amount')
                    ->numeric(),
                TextEntry::make('month'),
                TextEntry::make('year')
                    ->numeric(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                TextColumn::make('user.name')
                    ->searchable(),
                TextColumn::make('category.name')
                    ->searchable(),
                TextColumn::make('assined_amount')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('spend_amount')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('month')
                    ->searchable(),
                TextColumn::make('year')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageBugets::route('/'),
        ];
    }
}
