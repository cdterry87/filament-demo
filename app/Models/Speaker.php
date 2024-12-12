<?php

namespace App\Models;

use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Speaker extends Model
{
    use HasFactory;

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

    public function conferences(): BelongsToMany
    {
        return $this->belongsToMany(Conference::class);
    }

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
                ->descriptions([
                    'community-leader' => 'Leader in the community.',
                    'business-leader' => 'Must be an owner or high level employee of an organization.',
                    'charisma' => 'Must be an excellent and engaging public speaker.',
                    'first-time' => 'Has never spoken at a conference before.',
                    'expert' => 'Must have at least 5 years experience in the field.',
                    'humanitarian' => 'Works with an organization that benefits humanity.',
                    'influencer' => 'Influencer on any social media platform with over 100k followers.',
                    'open-source' => 'Contributor to multiple open source projects.',
                    'unique-perspective' => 'Speaker outside of the normal talks of our convention.'
                ])
                ->options([
                    'community-leader' => 'Community Leader',
                    'business-leader' => 'Business Leader',
                    'charisma' => 'Charisma',
                    'first-time' => 'First Time',
                    'expert' => 'Expert',
                    'humanitarian' => 'Works in Humanitarian Field',
                    'influencer' => 'Influencer',
                    'open-source' => 'Open Source Contributor',
                    'unique-perspective' => 'Unique Perspective',
                ]),
        ];
    }

}
