<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\AreaResource\Pages;
use App\Filament\App\Resources\AreaResource\RelationManagers;
use App\Models\Area;
use Filament\Actions\ViewAction;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class AreaResource extends Resource
{
    protected static ?string $model = Area::class;

    protected static ?string $navigationIcon = 'heroicon-o-squares-plus';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('')
                    ->description('You can create area for your personalized.')
                    ->aside()
                    ->schema([
                Section::make()
                    ->schema([
                        Forms\Components\Hidden::make('user_id')->default(Auth::id()),
                        TextInput::make('name')->required(),
//                        TextInput::make('process'),
                        Textarea::make('description')
                            ->autosize()
                            ->maxLength(1024)
                    ]),
                ])
            ]);
    }
    public static function indexQuery(): Builder
    {
        $userId = Auth::id();

        return parent::indexQuery()->where('user_id', $userId);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->paginated(false)
            ->columns([
                Stack::make([
                    TextColumn::make('name'),
//                    TextColumn::make('process'),
                    TextColumn::make('description'),
                ]),
            ])
            ->contentGrid([
                'sm' => 2,
                'md' => 3,
                'xl' => 4,
            ])

            ->filters([
                //
            ])
            ->actions([

//                ActionGroup::make([
//                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\ForceDeleteAction::make(),
                    Tables\Actions\RestoreAction::make(),
//                    Tables\Actions\ViewAction::make(),
//                ])
//                ->tooltip('Actions')
//                ->icon('clarity-ellipsis-horizontal-outline-badged')
//                ->color('info'),

            ])
            ->bulkActions([
//                Tables\Actions\BulkActionGroup::make([
//                    Tables\Actions\DeleteBulkAction::make(),
//                    Tables\Actions\ForceDeleteBulkAction::make(),
//                    Tables\Actions\RestoreBulkAction::make(),
//                ]),
            ]);

    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
//               Section::make('Area')
//                   ->schema([
                       TextEntry::make('name'),
                       TextEntry::make('description')
//                   ])->columns(2),
            ]);
    }

    public static function getRelations(): array
    {
        return [
           RelationManagers\ProjectsRelationManager::class,
            RelationManagers\ResourcesRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAreas::route('/'),
            'create' => Pages\CreateArea::route('/create'),
            'edit' => Pages\EditArea::route('/{record}/edit'),

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
