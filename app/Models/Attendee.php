<?php

namespace App\Models;

use Filament\Forms\Components\Group;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendee extends Model
{
    protected $guarded = [];

    public static function getForm(): array
    {
        return [
            Group::make()->columns(2)->schema([
                TextInput::make('name')
                    ->label('Name')
                    ->placeholder('John Doe')
                    ->required(),
                TextInput::make('email')
                    ->label('Email')
                    ->placeholder('')
                    ->required(),
            ]),
        ];
    }

    public function conference(): BelongsTo
    {
        return $this->belongsTo(Conference::class);
    }

}
