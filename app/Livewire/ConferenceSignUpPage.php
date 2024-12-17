<?php

namespace App\Livewire;

use App\Models\Attendee;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Illuminate\Support\HtmlString;
use Livewire\Component;

class ConferenceSignUpPage extends Component implements HasForms, HasActions
{
    use InteractsWithActions, InteractsWithForms;

    public int $conferenceId;

    public int $price = 500;

    public function mount(): void
    {
        $this->conferenceId = 1;
    }


    public function signUpAction(): Action
    {
        return Action::make('signUp')
            ->slideOver()
            ->form([
                Placeholder::make('total_price')
                    ->content(function (Get $get) {
                        $totalPrice = count($get('attendees') ?? []) * $this->price;
                        return "Total Price: $totalPrice";
                    }),
                Repeater::make('attendees')
                    ->schema(
                        Attendee::getForm(),
                    )
            ])
            ->action(function (array $data) {
                collect($data['attendees'])->each(function ($attendee) {
                    Attendee::create([
                        'name' => $attendee['name'],
                        'email' => $attendee['email'],
                        'ticket_cost' => $this->price,
                        'is_paid' => true,
                        'conference_id' => $this->conferenceId,
                    ]);
                });
            })
            ->after(function () {
                Notification::make('success')
                    ->success()
                    ->title('Success!')
                    ->body(new HtmlString('You have successfully signed up for the conference.'))
                    ->send();
            });
    }

    public function render()
    {
        return view('livewire.conference-sign-up-page');
    }
}
