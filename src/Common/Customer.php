<?php
/**
 * Credit Card class
 */

namespace Omnipay\Common;

use DateTime;
use DateTimeZone;
use Omnipay\Common\Exception\InvalidBankAccountException;
use Omnipay\Common\Exception\InvalidCreditCardException;
use Omnipay\Common\Exception\InvalidCustomerException;
use Symfony\Component\HttpFoundation\ParameterBag;

/**
 * Customer class
 *
 * This class defines and abstracts customer accounts used
 * throughout the Omnipay system.
 *
 * Example:
 *
 * <code>
 *   // Define bank account parameters, which should look like this
 *   $parameters = [
 *       'firstName' => 'Bobby',
 *       'lastName' => 'Tables',
 *       'email' => 'bobbytables@test.com',
 *   ];
 *
 *   // Create a bank object
 *   $customer = new Customer($parameters);
 * </code>
 *
 * The full list of card attributes that may be set via the parameter to
 * *new* is as follows:
 *
 * * firstName
 * * lastName
 * * email
 * * gatewayId
 *
 * If any unknown parameters are passed in, they will be ignored.  No error is thrown.
 */
class Customer
{
    use ParametersTrait;

    /**
     * Create a new Customer object using the specified parameters
     *
     * @param array $parameters An array of parameters to set on the new object
     */
    public function __construct($parameters = null)
    {
        $this->initialize($parameters);
    }

    /**
     * Initialize the object with parameters.
     *
     * If any unknown parameters passed, they will be ignored.
     *
     * @param array $parameters An associative array of parameters
     * @return $this
     */
    public function initialize(array $parameters = null)
    {
        $this->parameters = new ParameterBag;

        Helper::initialize($this, $parameters);

        return $this;
    }

	/**
	 * Validate this customer account. If the customer is invalid, InvalidCustomerException is thrown.
	 *
	 * This method is called internally by gateways to avoid wasting time with an API call
	 * when the bank account is clearly invalid.
	 *
	 * Generally if you want to validate the customer account yourself with custom error
	 * messages, you should use your framework's validation library, not this method.
	 *
	 * @return void
	 * @throws InvalidCustomerException
	 */
    public function validate()
    {
        $requiredParameters = array(
            'firstName' => 'customer first name',
            'lastName' => 'customer last name',
        );

        foreach ($requiredParameters as $key => $val) {
            if (!$this->getParameter($key)) {
                throw new InvalidCustomerException("The $val is required");
            }
        }
    }

    /**
     * Get First Name.
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->getParameter('firstName');
    }

    /**
     * Set First Name
     *
     * @param string $value Parameter value
     * @return $this
     */
    public function setFirstName($value)
    {
        return $this->setParameter('firstName', $value);
    }

	/**
	 * Get Last Name.
	 *
	 * @return string
	 */
	public function getLastName()
	{
		return $this->getParameter('lastName');
	}

	/**
	 * Set Last Name
	 *
	 * @param string $value Parameter value
	 * @return $this
	 */
	public function setLastName($value)
	{
		return $this->setParameter('lastName', $value);
	}

    /**
     * Get the Email.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->getParameter('email');
    }

    /**
     * Sets the Email.
     *
     * @param string $value
     * @return $this
     */
    public function setEmail($value)
    {
        return $this->setParameter('email', $value);
    }

	/**
	 * Get the GatewayId.
	 *
	 * @return string
	 */
	public function getGatewayId()
	{
		return $this->getParameter('gatewayId');
	}

	/**
	 * Sets the GatewayId.
	 *
	 * @param string $value
	 * @return $this
	 */
	public function setGatewayId($value)
	{
		return $this->setParameter('gatewayId', $value);
	}
}
