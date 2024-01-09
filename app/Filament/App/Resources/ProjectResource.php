<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\ProjectResource\Pages;
use App\Filament\App\Resources\ProjectResource\RelationManagers;
use App\Models\Area;
use App\Models\Project;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static ?string $navigationIcon = 'heroicon-o-rocket-launch';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('')
                    ->description('You can create projects for your area. In this project, notes, You can store resources')
                    ->aside()
                    ->schema([
                        Forms\Components\Hidden::make('user_id')->default(Auth::id()),
                        Grid::make([
                            'default' => 1,
                            'sm' => 2,
                            'md' => 2,

                        ])
                            ->schema([
                            TextInput::make('name')
                                ->live(onBlur: true),
                            Select::make('status')
                                ->options([
                                    'prepare' => 'Prepare',
                                    'in_progress' => 'In Progress',
                                    'completed' => 'Completed',

                                ])
                                ->default('prepare')
                                ->native(false),
                        ]),
//                        TextInput::make('process'),

                        Select::make('area_id')
                            ->label('Area')
                            ->createOptionAction(
                                fn (Action $action) => $action->modalWidth('md'),
                            )
                            ->options(function () {
//                                return Area::where('user_id', Auth::id())->pluck('name', 'id');

                            })
                            ->searchable(['name'])
                            ->searchPrompt('Search areas by their name')
                            ->loadingMessage('Loading areas...')
                            ->noSearchResultsMessage('No areas found.')
                            ->relationship(name: 'area', titleAttribute: 'name')

//                            ->createOptionForm([
//                                Forms\Components\Hidden::make('user_id')->default(Auth::id()),
//                                TextInput::make('name')
//                                    ->required(),
//                            ])
                            ->preload(),
                        Textarea::make('remark')
                            ->autosize()
                            ->minLength(2)
                            ->maxLength(1024)

                    ]),
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user_id'),
                TextColumn::make('name'),
                TextColumn::make('process'),
                TextColumn::make('status')
                    ->color(fn (string $state): string => match ($state) {
                        'prepare' => 'gray',
                        'in_progress' => 'warning',
                        'completed' => 'success',

                    })

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
//                Tables\Actions\DeleteAction::make(),
//                Tables\Actions\ForceDeleteAction::make(),
//                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
//                    Tables\Actions\ForceDeleteBulkAction::make(),
//                    Tables\Actions\RestoreBulkAction::make(),
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
            'index' => Pages\ListProjects::route('/'),
            'create' => Pages\CreateProject::route('/create'),
            'edit' => Pages\EditProject::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $userId = Auth::id();

        return parent::getEloquentQuery()
            ->where('user_id', $userId)
            ->whereNull('deleted_at')
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
