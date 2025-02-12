<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FacultyResource\Pages;
use App\Filament\Resources\FacultyResource\RelationManagers;
use App\Filament\Resources\FacultyResource\RelationManagers\StudyProgramsRelationManager;
use App\Models\Campus;
use App\Models\Faculty;
use App\Models\MasterFaculty;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FacultyResource extends Resource
{
    protected static ?string $model = Faculty::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';

    protected static ?string $navigationGroup = "Data Kampus";

    protected static ?string $navigationLabel = 'Fakultas';

    public static ?string $modelLabel = 'Fakultas';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nama Fakultas')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('description')
                    ->label('Deskripsi')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('campus_id')
                    ->label('Kampus')
                    ->options(Campus::all()->pluck('name', 'id'))
                    ->searchable()
                    ->default(null)
                    ->native(false),
                Forms\Components\Select::make('master_faculty_id')
                    ->label('Nama Master Fakultas')
                    ->options(MasterFaculty::all()->pluck('name', 'id'))
                    ->searchable()
                    ->default(null)
                    ->native(false)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Fakultas')
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
                    ->label('Deskripsi')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('campus.name')
                    ->label('Kampus')
                    ->numeric()
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('master_faculty.name')
                    ->label('Master Fakultas')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
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
            StudyProgramsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFaculties::route('/'),
            'create' => Pages\CreateFaculty::route('/create'),
            'view' => Pages\ViewFaculty::route('/{record}'),
            'edit' => Pages\EditFaculty::route('/{record}/edit'),
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
