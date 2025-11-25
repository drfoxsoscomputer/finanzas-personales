<?php

namespace App\Filament\Resources\Bugets;

use App\Filament\Resources\Bugets\Pages\ManageBugets;
use App\Models\Buget;
use BackedEnum;
use Dom\Text;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
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
                TextInput::make('title')
                    ->label('Descripción')
                    ->required()
                    ->maxLength(255),
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
                    ->label('Usuario'),
                TextEntry::make('category.name')
                    ->label('Categoría'),
                Section::make('Montos')
                    ->schema([
                        TextEntry::make('assined_amount')
                            ->label('Asignado')
                            ->numeric(),
                        TextEntry::make('spend_amount')
                            ->label('Gastado')
                            ->numeric(),
                        TextEntry::make('available')
                            ->label('Disponible')
                            ->color(fn($record) => $record->available >= 0 ? 'success' : 'danger')
                            ->numeric(),
                    ])
                    ->columns(3)
                    ->columnSpanFull(),
                TextEntry::make('month')
                    ->label('Mes'),
                TextEntry::make('year')
                    ->label('Año')
                    ->numeric(),
                TextEntry::make('created_at')
                    ->label('Creado el')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->label('Actualizado el')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->defaultSort('id', 'asc')
            ->columns([
                TextColumn::make('user.name')
                    ->label('Usuario')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('category.name')
                    ->label('Categoría')
                    ->searchable(),
                TextColumn::make('category.type')
                    ->label('Tipo')
                    ->badge()
                    ->colors([
                        'success' => 'ingreso',
                        'danger' => 'egreso',
                    ])
                    ->sortable()
                    ->searchable(),
                TextColumn::make('assined_amount')
                    ->label('Monto Asignado')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('spend_amount')
                    ->label('Gastado')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('available')
                    ->label('Disponible')
                    ->numeric()
                    ->color(fn($record) => $record->available >= 0 ? 'success' : 'danger')
                    ->sortable(),
                TextColumn::make('month')
                    ->label('Mes')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('year')
                    ->label('Año')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Creado el')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Actualizado el')
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
