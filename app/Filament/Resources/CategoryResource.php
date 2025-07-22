<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';
    protected static ?string $navigationGroup = 'Manajemen Aplikasi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->label('Nama Kategori'),
                Forms\Components\Select::make('type')
                    ->required()
                    ->options([
                        'Needs' => 'Needs',
                        'Wants' => 'Wants',
                        'Savings' => 'Savings',
                    ])
                    ->label('Tipe Kategori'),
                // --- INPUT IKON BARU ---
                Forms\Components\TextInput::make('icon')
                    ->nullable()
                    ->maxLength(255)
                    ->label('Nama Ikon Material')
                    ->helperText('Contoh: fast_food, commute, movie. Lihat nama ikon di Google Fonts.'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // --- KOLOM IKON BARU ---
                Tables\Columns\TextColumn::make('icon')
                    ->label('Ikon')
                    ->fontFamily('Material Icons'), // Trik agar ikonnya tampil
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->label('Nama Kategori'),
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Needs' => 'warning',
                        'Wants' => 'info',
                        'Savings' => 'success',
                    })
                    ->sortable()
                    ->label('Tipe'),
            ])
            ->filters([
                //
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
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }    
}
