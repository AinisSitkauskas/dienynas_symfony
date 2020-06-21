<?php namespace App\Tests;

use App\Tests\AcceptanceTester;
use Codeception\Step\Argument\PasswordArgument;

class UserCest
{

    public function tryToTestUserRegistration(AcceptanceTester $I)
    {
        $I->amOnPage('/login');
        $I->fillField('username', 'petras.pe');
        $I->fillField('password', new PasswordArgument('admin'));
        $I->click('Prisijungti');
        $I->see('Sveikas, sugrįžęs Petras Petraitis!');
        $I->amOnPage('/register');
        $I->see('Registracija');
        $I->fillField('registration_form[username]', 'vartotojas');
        $I->fillField('registration_form[name]', 'vardenis');
        $I->fillField('registration_form[surname]', 'pavardenis');
        $I->fillField('registration_form[plainPassword]', new PasswordArgument('slaptazodis'));
        $I->click('Registruotis');
        $I->see('Naujas vartotojas sėkmingai užregistruotas!');
    }

    public function tryToTestUserDeletion(AcceptanceTester $I)
    {
        $I->amOnPage('/login');
        $I->fillField('username', 'vartotojas');
        $I->fillField('password', new PasswordArgument('slaptazodis'));
        $I->click('Prisijungti');
        $I->see('Sveikas, sugrįžęs vardenis pavardenis!');
        $I->amOnPage('/delete');
        $I->see('Jūsų vartotojas ištrintas!');
    }
}
