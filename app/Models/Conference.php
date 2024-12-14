<?php

namespace App\Models;

use App\Enums\Region;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Get;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Conference extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'start_date',
        'end_date',
        'status',
        'region',
        'venue_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'start_date' => 'date',
        'end_date' => 'date',
        'venue_id' => 'integer',
        'region' => Region::class
    ];

    public static function getForm(): array
    {
        return [
            Section::make('Conference Details')
                ->description('Provide some basic information about the conference.')
                ->columns(2)
                ->schema([
                    TextInput::make('name')
                        ->columnSpanFull()
                        ->label('Conference Name')
                        ->required()
                        ->maxLength(60)
                        ->placeholder('Enter the name of the conference')
                        ->helperText('The name of the conference.')
                        ->columnSpanFull(),
                    RichEditor::make('description')
                        ->required()
                        ->maxLength(255)
                        ->columnSpanFull(),
                    DateTimePicker::make('start_date')
                        ->required(),
                    DateTimePicker::make('end_date')
                        ->required(),
                    Fieldset::make('Status')
                        ->schema([
                            Select::make('status')
                                ->required()
                                ->options([
                                    'draft' => 'Draft',
                                    'published' => 'Published',
                                    'archived' => 'Archived',
                                ]),
                            Toggle::make('is_published')
                                ->default(false),
                        ])
                ]),
            Section::make('Location')
                ->description('Specify a region and venue for the conference.')
                ->columns(2)
                ->schema([
                    Select::make('region')
                        ->live()
                        ->required()
                        ->enum(Region::class)
                        ->options(Region::class),
                    Select::make('venue_id')
                        ->searchable()
                        ->preload() // use for moderate amount of data; don't use for large amounts of data
                        ->createOptionForm(Venue::getForm())
                        ->editOptionForm(Venue::getForm())
                        ->relationship('venue', 'name', modifyQueryUsing: function (Builder $query, Get $get) {
                            return $query->where('region', $get('region'));
                        }),

                ]),
            Section::make('Select Conference Speakers')
                ->description('Select the primary speakers for the conference.')
                ->schema([
                    CheckboxList::make('speakers')
                        ->hiddenLabel()
                        ->relationship('speakers', 'name')
                        ->options(Speaker::all()->pluck('name', 'id')->toArray())
                        ->columns(3)
                        ->columnSpanFull(),
                ]),
            Actions::make([
                Actions\Action::make('star')
                    ->visible(function ($operation) {
                        if ($operation === 'create' && app()->environment('local')) {
                            return true;
                        }
                        return false;
                    })
                    ->label('Fill with Factory Data')
                    ->icon('heroicon-o-star')
                    ->action(function ($livewire) {
                        $data = Conference::factory()->make()->toArray();
                        $livewire->form->fill($data);
                    })
            ])
        ];
    }

    public function attendees(): BelongsToMany
    {
        return $this->belongsToMany(Attendee::class);
    }

    public function venue(): BelongsTo
    {
        return $this->belongsTo(Venue::class);
    }

    public function speakers(): BelongsToMany
    {
        return $this->belongsToMany(Speaker::class);
    }

    public function talks(): BelongsToMany
    {
        return $this->belongsToMany(Talk::class);
    }
}
