<?php namespace App\Tests;
use App\Tests\AcceptanceTester;
use Codeception\Step\Argument\PasswordArgument;

class MarkCest
{
    public function _before(AcceptanceTester $I)
    {
        $I->amOnPage('/login');
        $I->fillField('username','petras.pe');
        $I->fillField('password', new PasswordArgument('admin'));
        $I->click('Prisijungti');
        $I->see('Sveikas, sugrįžęs Petras Petraitis!');
    }

    public function tryToTestMarkInsert(AcceptanceTester $I)
    {
        $I->amOnPage('/mark/insert');
        $I->see('Pažymio įvedimas');
        $I->selectOption('mark_insert_form[student]','Petras Petraitis');
        $I->selectOption('mark_insert_form[teachingSubject]','Matematika');
        $I->fillField('mark_insert_form[mark]', '10');
        $I->fillField('mark_insert_form[date]', '2020-04-22');
        $I->click('Įrašyti pažymį');
        $I->see('Pažymys įrašytas !');
    }

    public function tryToTestMarkMy(AcceptanceTester $I)
    {
        $I->amOnPage('/mark/my');
        $I->see('Mano pažymių vidurkiai');
        $I->seeElement('.table');
        $I->see('Pažymio vidurkio skaičiuoklė');
        $I->fillField('average_mark_calculator_form[marks]', '10, 9, 8, 7');
        $I->selectOption('average_mark_calculator_form[teachingSubject]','Matematika');
        $I->click('Skaičiuoti');
        $I->see('Gavus papildomus pažymius: 10, 9, 8, 7 jūsų vidurkis iš matematikos yra');
    }

    public function tryToTestMarkAll(AcceptanceTester $I)
    {
        $I->amOnPage('/mark/all');
        $I->see('Mokinių pažymių vidurkiai');
        $I->seeElement('.table');
    }

    public function tryToTestMarkTop(AcceptanceTester $I)
    {
        $I->amOnPage('/mark/top');
        $I->see('Pirmūnų sąrašas');
        $I->seeElement('.table');
    }
}
