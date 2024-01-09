<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ResourceResource\Pages;
use App\Filament\Resources\ResourceResource\RelationManagers;
use App\Models\Area;
use App\Models\Project;
use App\Models\Resource as ResourceModel;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Placeholder;
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

class ResourceResource extends Resource
{
    protected static ?string $model = ResourceModel::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Placeholder::make('created')
                    ->content(fn ($record): string => $record ? $record->created_at->toFormattedDateString() : ''),
                Section::make()
                    ->schema([
                        TextInput::make('user_id')
                            ->default(auth()->id())
                            ,
                        TextInput::make('name'),
                        TextInput::make('urls')->type('url'),
                MarkdownEditor::make('remark')
                    ->label('Content')
                    ->toolbarButtons([
                        'blockquote',
                        'bold',
                        'bulletList',
                        'codeBlock',
                        'heading',
                        'italic',
                        'link',
                        'orderedList',
                        'redo',
                        'strike',
                        'table',
                        'undo',
                    ]),
                ])
                   ,
                Section::make('Optional')
                    ->description('You can link your resources to areas and projects for better use')
//                    ->aside()
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
