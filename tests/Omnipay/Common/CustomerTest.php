<?php

namespace Omnipay\Common;

use Omnipay\Common\Exception\InvalidBankAccountException;
use Omnipay\Common\Exception\InvalidCustomerException;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Tests\TestCase;

class CustomerTest extends TestCase
{
    /** @var Customer */
    private $customer;

    public function setUp() : void
    {
        $this->customer = new Customer();
		$this->customer->setFirstName('Bobby');
		$this->customer->setLastName('Tables');
		$this->customer->setEmail('bobbytables@test.com');
		$this->customer->setGatewayId('123444ffgg');
    }

    public function testConstructWithParams(): void {
        $customer = new Customer(['firstName' => 'First']);
        $this->assertSame('First', $customer->getFirstName());
    }

    public function testInitializeWithParams(): void {
		$customer = new Customer;
		$customer->initialize(['firstName' => 'First']);
		$this->assertSame('First', $customer->getFirstName());
    }

    public function testGetParameters(): void {
		$customer = new Customer([
            'firstName'        => 'Bobby',
            'lastName'      => 'Tables',
            'email' => 'bobbytables@test.com',
            'gatewayId' => '123444ffgg',
        ]);

        $parameters = $customer->getParameters();
        $this->assertSame('Bobby', $parameters['firstName']);
        $this->assertSame('Tables', $parameters['lastName']);
        $this->assertSame('bobbytables@test.com', $parameters['email']);
        $this->assertSame('123444ffgg', $parameters['gatewayId']);
    }

	/**
	 * @throws InvalidCustomerException
	 */
    public function testValidateFixture(): void {
    	$this->expectNotToPerformAssertions();
        $this->customer->validate();
    }


	/**
	 * @throws InvalidCustomerException
	 */
	public function testValidateCustomerFirstNameRequired(): void {
		$this->expectException(InvalidCustomerException::class);
		$this->expectExceptionMessage('The customer first name is required');
        $this->customer->setFirstName(null);
        $this->customer->validate();
    }


	/**
	 * @throws InvalidCustomerException
	 */
	public function testValidateCustomerLastNameRequired(): void {
		$this->expectException(InvalidCustomerException::class);
		$this->expectExceptionMessage('The customer last name is required');
		$this->customer->setLastName(null);
		$this->customer->validate();
    }
}
