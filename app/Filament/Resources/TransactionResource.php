<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionResource\Pages;
use App\Filament\Resources\TransactionResource\RelationManagers;
use App\Models\Transaction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model; // Tambahkan ini di atas
class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Kita tidak perlu input user_id karena akan diisi otomatis
                TextInput::make('amount')
                    ->required()
                    ->numeric()
                    ->prefix('Rp'),
                Select::make('type')
                    ->options([
                        'income' => 'Pemasukan',
                        'expense' => 'Pengeluaran',
                    ])
                    ->required(),
                TextInput::make('category')
                    ->required()
                    ->maxLength(255),
                Textarea::make('note')
                    ->columnSpanFull(),
            ]);
    }
   public static function table(Table $table): Table
{
    return $table
        ->columns([
            TextColumn::make('user.name')->searchable(),
            TextColumn::make('type')->badge()->color(fn (string $state): string => match ($state) {
                'income' => 'success',
                'expense' => 'danger',
            }),
            TextColumn::make('amount')->money('IDR')->sortable(),
            TextColumn::make('category')->searchable(),
            TextColumn::make('created_at')->dateTime()->sortable(),
        ])
        ->filters([
            // Filter akan kita tambahkan nanti
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
    
    public static function mutateFormDataBeforeCreate(array $data): array
    {
    $user = auth()->user();
    if ($user) {
        $data['user_id'] = $user->id;
    }
        return $data;
    }

    // Method ini memastikan tabel hanya menampilkan data milik user yang login
    public static function getEloquentQuery(): Builder
    {
        // Ganti baris ini:
        // return parent::getEloquentQuery()->where('user_id', auth()->id());

        // Menjadi seperti ini:
        $user = auth()->user();
        if ($user) {
            return parent::getEloquentQuery()->where('user_id', $user->id);
        }

        // Jika karena alasan tertentu user tidak ditemukan, kembalikan query kosong
        // untuk mencegah error dan kebocoran data.
        return parent::getEloquentQuery()->whereNull('user_id');
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
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }
}
