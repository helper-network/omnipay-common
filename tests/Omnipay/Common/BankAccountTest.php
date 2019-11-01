<?php

namespace Omnipay\Common;

use Omnipay\Common\Exception\InvalidBankAccountException;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Tests\TestCase;

class BankAccountTest extends TestCase
{
    /** @var BankAccount */
    private $bankAccount;

    public function setUp() : void
    {
        $this->bankAccount = new BankAccount();
        $this->bankAccount->setAccountNumber('112233');
        $this->bankAccount->setRoutingNumber('445566');
        $this->bankAccount->setType('checking');
    }

    public function testConstructWithParams(): void {
        $account = new BankAccount(['accountNumber' => '112233']);
        $this->assertSame('112233', $account->getAccountNumber());
    }

    public function testInitializeWithParams(): void {
		$account = new BankAccount;
		$account->initialize(['accountNumber' => '112233']);
        $this->assertSame('112233', $account->getAccountNumber());
    }

    public function testGetParameters(): void {
        $account = new BankAccount([
            'accountNumber'        => '112233',
            'routingNumber'      => '445566',
            'type' => 'checking',
        ]);

        $parameters = $account->getParameters();
        $this->assertSame('112233', $parameters['accountNumber']);
        $this->assertSame('445566', $parameters['routingNumber']);
        $this->assertSame('checking', $parameters['type']);
    }

	/**
	 * @throws Exception\InvalidBankAccountException
	 */
    public function testValidateFixture(): void {
    	$this->expectNotToPerformAssertions();
        $this->bankAccount->validate();
    }


    public function testValidateAccountNumberRequired(): void {
		$this->expectException(InvalidBankAccountException::class);
		$this->expectExceptionMessage('The bank account number is required');
        $this->bankAccount->setAccountNumber(null);
        $this->bankAccount->validate();
    }


    public function testValidateRoutingNumberRequired(): void {
		$this->expectException(InvalidBankAccountException::class);
		$this->expectExceptionMessage('The bank routing number is required');
		$this->bankAccount->setRoutingNumber(null);
		$this->bankAccount->validate();
    }


    public function testValidateExpiryYearRequired(): void {
		$this->expectException(InvalidBankAccountException::class);
		$this->expectExceptionMessage('The bank account type is required');
		$this->bankAccount->setType(null);
		$this->bankAccount->validate();
    }

    public function testValidateType(): void {
		$this->expectException(InvalidBankAccountException::class);
		$this->expectExceptionMessage('Account type must be savings or checking, provided type: surfings');
		$this->bankAccount->setType('surfings');
		$this->bankAccount->validate();
    }
}
