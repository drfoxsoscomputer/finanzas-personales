<?php

namespace App\Filament\Resources\Transactions;

use App\Filament\Resources\Transactions\Pages\ManageTransactions;
use App\Models\Transaction;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'description';

    public static function getModelLabel(): string
    {
        return __('Transaction');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Transactions');
    }


    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->label('Usuario')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('category_id')
                    ->label('Categoría')
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
                Section::make()
                    ->Schema([
                        TextInput::make('amount')
                            ->label('Monto')
                            ->placeholder('0.00')
                            ->numeric()
                            ->minValue(0)
                            ->required(),
                        DatePicker::make('transaction_date')
                            ->label('Fecha de la transacción')
                            ->required()
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
                Textarea::make('description')
                    ->label('Descripción')
                    ->required()
                    ->columnSpanFull(),
                FileUpload::make('image_path')
                    ->image()
                    ->label('Imagen')
                    ->columnSpanFull(),
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
                TextEntry::make('category.type')
                    ->label('Tipo')
                    ->badge()
                    ->colors([
                        'success' => 'ingreso',
                        'danger' => 'egreso',
                    ]),
                TextEntry::make('amount')
                    ->label('Monto')
                    ->numeric(),
                TextEntry::make('description')
                    ->label('Descripción')
                    ->html()
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('transaction_date')
                    ->label('Fecha de la transacción')
                    ->date(),
                TextEntry::make('created_at')
                    ->label('Creado el')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->label('Actualizado el')
                    ->dateTime()
                    ->placeholder('-'),
                ImageEntry::make('image_path')
                    ->label('Imagen de la transacción')
                    ->imageHeight(400)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('description')
            ->columns([
                TextColumn::make('user.name')
                    ->label('Usuario')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('description')
                    ->label('Descripción')
                    ->sortable()
                    ->html()
                    ->limit(20)
                    ->searchable(),
                TextColumn::make('category.name')
                    ->sortable()
                    ->label('Categoría')
                    ->searchable(),
                TextColumn::make('category.type')
                    ->label('Tipo')
                    ->badge()
                    ->colors([
                        'success' => 'ingreso',
                        'danger' => 'egreso',
                    ])
                    ->sortable(),
                TextColumn::make('amount')
                    ->label('Monto')
                    ->numeric()
                    ->sortable(),
                ImageColumn::make('image_path')
                    ->label('Imagen'),
                TextColumn::make('transaction_date')
                    ->label('Fecha')
                    ->date()
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
            'index' => ManageTransactions::route('/'),
        ];
    }
}
