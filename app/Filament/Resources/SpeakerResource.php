<?php

namespace App\Filament\Resources;

use App\Enums\TalkStatus;
use App\Filament\Resources\SpeakerResource\Pages;
use App\Filament\Resources\SpeakerResource\RelationManagers;
use App\Models\Speaker;
use Filament\Forms\Form;
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SpeakerResource extends Resource
{
    protected static ?string $model = Speaker::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $recordTitleAttribute = 'name'; // This will be used to add a global search for attendee names

    public static function form(Form $form): Form
    {
        return $form
            ->schema(Speaker::getForm());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('website')
                    ->searchable(),
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
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            RelationManagers\TalksRelationManager::class
        ];
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Group::make([
                    Section::make('Avatar')
                        ->columns(1)
                        ->columnSpan(1)
                        ->schema([
                            ImageEntry::make('avatar')
                                ->defaultImageUrl(function (Speaker $speaker) {
                                    return $speaker->getDefaultAvatar();
                                })
                                ->circular()
                                ->hiddenLabel()
                        ]),
                    Section::make('Personal Information')
                        ->columns(2)
                        ->columnSpan(2)
                        ->schema([
                            TextEntry::make('name'),
                            TextEntry::make('email'),
                            TextEntry::make('website')
                                ->url(function (Speaker $speaker) {
                                    return $speaker->website;
                                })
                                ->columnSpanFull(),
                            TextEntry::make('has_spoken')
                                ->getStateUsing(function (Speaker $speaker) {
                                    return $speaker->talks()->where('status', TalkStatus::APPROVED)->count(
                                    ) > 0 ? 'Previous Speaker' : 'Has Not Spoken';
                                })
                                ->badge()
                                ->color(function ($state) {
                                    return $state === 'Previous Speaker' ? 'success' : 'danger';
                                })
                        ])
                ])
                    ->columns(3)
                    ->columnSpanFull(),
                Section::make('Bio')
                    ->columns(1)
                    ->columnSpanFull()
                    ->schema([
                        TextEntry::make('bio')
                            // The 'prose' class adds extra styling attributes like headers
                            ->extraAttributes(['class' => 'prose dark:prose-invert'])
                            ->hiddenLabel()
                            ->columnSpanFull()
                            ->html(),
                    ]),
                Section::make('Qualifications')
                    ->columns(1)
                    ->columnSpanFull()
                    ->schema([
                        TextEntry::make('qualifications')
                            ->hiddenLabel()
                            ->columnSpanFull(),
                    ]),


            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSpeakers::route('/'),
            'create' => Pages\CreateSpeaker::route('/create'),
            'view' => Pages\ViewSpeaker::route('/{record}'),
        ];
    }
}
