<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjectResource\Pages;
use App\Filament\Resources\ProjectResource\RelationManagers;
use App\Models\Area;
use App\Models\Project;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
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

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Project creating')
                    ->description('You can create projects for your area. In this project, notes, You can store resources')
                    ->aside()
            ->schema([
                TextInput::make('name'),
                TextInput::make('process'),
                Select::make('status')
                    ->options([
                        'uncompleted' => 'Uncompleted',
                        'completed' => 'Completed',

                    ])
                    ->default('uncompleted')
                    ->native(false),
                Select::make('area_id')
                    ->label('Area')
                    ->createOptionAction(
                        fn (Action $action) => $action->modalWidth('xl'),
                    )
                    ->options(Area::all()->pluck('name', 'id'))
                    ->searchable(['name'])
                    ->searchPrompt('Search areas by their name')
                    ->loadingMessage('Loading areas...')
                    ->noSearchResultsMessage('No areas found.')
                    ->relationship(name: 'area', titleAttribute: 'name')
                    ->createOptionForm([
                        TextInput::make('name')
                            ->required(),
                    ])->preload(),
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
                TextColumn::make('name'),
                TextColumn::make('process'),

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
}
