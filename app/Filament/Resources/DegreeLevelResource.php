<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DegreeLevelResource\Pages;
use App\Filament\Resources\DegreeLevelResource\RelationManagers;
use App\Models\DegreeLevel;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DegreeLevelResource extends Resource
{
    protected static ?string $model = DegreeLevel::class;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $navigationLabel = 'Jenjang Studi';

    public static ?string $modelLabel = 'Jenjang Studi';

    protected static ?string $navigationGroup = "Master Data";

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Jenjang Studi')
                    ->unique()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('duration')
                    ->label('Durasi')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Jenjang Studi')
                    ->searchable(),
                Tables\Columns\TextColumn::make('duration')
                    ->label('Durasi')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                ->iconButton(),
                Tables\Actions\EditAction::make()
                ->iconButton(),
                Tables\Actions\DeleteAction::make()
                ->iconButton(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
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
            'index' => Pages\ListDegreeLevels::route('/'),
            'create' => Pages\CreateDegreeLevel::route('/create'),
            'view' => Pages\ViewDegreeLevel::route('/{record}'),
            'edit' => Pages\EditDegreeLevel::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
