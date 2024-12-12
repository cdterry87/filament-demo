<?php

namespace App\Models;

use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Speaker extends Model
{
    use HasFactory;

    const QUALIFICATIONS = [
        'community-leader' => [
            'name' => 'Community Leader',
            'description' => 'Must be a leader in the community.',
        ],
        'business-leader' => [
            'name' => 'Business Leader',
            'description' => 'Must be an owner or high level employee of an organization.',
        ],
        'charisma' => [
            'name' => 'Charisma',
            'description' => 'Must be an excellent and engaging public speaker.',
        ],
        'first-time' => [
            'name' => 'First Time Speaker',
            'description' => 'Has never spoken at a conference before.',
        ],
        'expert' => [
            'name' => 'Expert',
            'description' => 'Must have at least 5 years experience in the field.',
        ],
        'humanitarian' => [
            'name' => 'Humanitarian',
            'description' => 'Works with an organization that benefits humanity.',
        ],
        'influencer' => [
            'name' => 'Influencer',
            'description' => 'Influencer on any social media platform with over 100k followers.',
        ],
        'open-source' => [
            'name' => 'Open Source Contributor',
            'description' => 'Contributor to multiple open source projects.',
        ],
        'unique-perspective' => [
            'name' => 'Unique Perspective',
            'description' => 'Speaker outside of the normal talks of our convention.',
        ],
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'bio',
        'qualifications',
        'website',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'qualifications' => 'array'
    ];

    public static function getForm(): array
    {
        return [
            Group::make([
                    TextInput::make('name')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('email')
                        ->email()
                        ->required()
                        ->maxLength(255),
                    TextInput::make('website')
                        ->url()
                        ->prefixIcon('heroicon-o-globe-alt')
                        ->required()
                        ->maxLength(255),
                ]
            )
                ->columns(3)
                ->columnSpanFull(),
            RichEditor::make('bio')
                ->required()
                ->columnSpanFull(),
            CheckboxList::make('qualifications')
                ->columns(3)
                ->columnSpanFull()
                ->bulkToggleable()
                ->searchable()
                ->options(
                    collect(self::QUALIFICATIONS)
                        ->map(fn($qualification) => $qualification['name'])
                        ->toArray()
                )
                ->descriptions(
                    collect(self::QUALIFICATIONS)
                        ->map(fn($qualification) => $qualification['description'])
                        ->toArray()
                ),
        ];
    }

    public function conferences(): BelongsToMany
    {
        return $this->belongsToMany(Conference::class);
    }

    public function talks(): HasMany
    {
        return $this->hasMany(Talk::class);
    }

}
