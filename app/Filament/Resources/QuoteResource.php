<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QuoteResource\Pages;
use App\Filament\Resources\QuoteResource\RelationManagers;
use App\Models\Quote;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class QuoteResource extends Resource
    {
        protected static ?string $model = Quote::class;

        protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-bottom-center-text';

        public static function form(Form $form): Form
        {
            return $form
                ->schema([
                    Forms\Components\Textarea::make('text')
                        ->required()
                        ->columnSpanFull(),
                    Forms\Components\TextInput::make('author')
                        ->required()
                        ->maxLength(255),
                ]);
        }

        public static function table(Table $table): Table
        {
            return $table
                ->columns([
                    Tables\Columns\TextColumn::make('text')
                        ->limit(70)
                        ->searchable(),
                    Tables\Columns\TextColumn::make('author')
                        ->searchable(),
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
                'index' => Pages\ListQuotes::route('/'),
                'create' => Pages\CreateQuote::route('/create'),
                'edit' => Pages\EditQuote::route('/{record}/edit'),
            ];
        }    
    }
