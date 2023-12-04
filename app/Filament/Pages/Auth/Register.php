<?php

namespace App\Filament\Pages\Auth;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Pages\Auth\Register as BaseRegister;
use Illuminate\Http\Request;

class Register extends BaseRegister
{
    public function form(Form $form): Form
    {
        return $form->schema([
            $this->getNameFormComponent(),
            $this->getEmailFormComponent(),
            $this->getPasswordFormComponent(),
            $this->getPasswordConfirmationFormComponent(),
            TextInput::make('invite_code')
                ->label('Invite Code')
                ->disabled()
                ->formatStateUsing(function (Request $request) {
                    return $request->get('invite_code');
                })
                ->required(),
            TextInput::make('account_id')
                ->label('Account Name')
                ->formatStateUsing(function(Get $get){
                     $inviteCode = $get('invite_code');
                })
                ->hidden()
                ->required(),
        ]);
    }
}
