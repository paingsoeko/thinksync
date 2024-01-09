<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\ResourceResource\Pages;
use App\Filament\App\Resources\ResourceResource\RelationManagers;
use App\Models\Area;
use App\Models\Project;
use App\Models\Resource as ResourceModel;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class ResourceResource extends Resource
{
    protected static ?string $model = ResourceModel::class;

    protected static ?string $navigationIcon = 'heroicon-o-inbox-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        Forms\Components\Hidden::make('user_id')->default(Auth::id()),
                        Grid::make([
                            'default' => 1,
                            'sm' => 2,
                        ])
                            ->schema([
                                TextInput::make('name')->label('')->placeholder('Name'),
                                TextInput::make('urls')->type('url')->label('')->placeholder('links'),
                            ])
                            ->columns(2),
                        Forms\Components\RichEditor::make('content')
                            ->label('')
                            ->toolbarButtons([
                                'attachFiles',
                                'blockquote',
                                'bold',
                                'bulletList',
                                'codeBlock',
                                'h2',
                                'h3',
                                'italic',
                                'link',
                                'orderedList',
                                'redo',
                                'strike',
                                'underline',
                                'undo',
                            ]),
                    ])
                ,
                Section::make('Optional')
                    ->description('You can link your resources to areas and projects for better use')
                    ->collapsed()
                    ->schema([

                        Select::make('status')
                            ->options([
                                'not_start' => 'Not Start',
                                'in_progress' => 'In Progress',
                                'done' => 'Done',

                            ])
                            ->default('not_start')
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
                        Select::make('project_id')
                            ->label('Project')
                            ->createOptionAction(
                                fn (Action $action) => $action->modalWidth('xl'),
                            )
                            ->options(Project::all()->pluck('name', 'id'))
                            ->searchable(['name'])
                            ->searchPrompt('Search projects by their name')
                            ->loadingMessage('Loading projects...')
                            ->noSearchResultsMessage('No projects found.')
                            ->relationship(name: 'project', titleAttribute: 'name')
                            ->createOptionForm([
                                TextInput::make('name')
                                    ->required(),
                            ])->preload(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('urls'),
                TextColumn::make('status'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListResources::route('/'),
            'create' => Pages\CreateResource::route('/create'),
            'edit' => Pages\EditResource::route('/{record}/edit'),
        ];
    }
}
