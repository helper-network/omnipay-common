<?php
/**
 * Credit Card class
 */

namespace Omnipay\Common;

use DateTime;
use DateTimeZone;
use Omnipay\Common\Exception\InvalidBankAccountException;
use Omnipay\Common\Exception\InvalidCreditCardException;
use Symfony\Component\HttpFoundation\ParameterBag;

/**
 * Credit Card class
 *
 * This class defines and abstracts bank accounts used
 * throughout the Omnipay system.
 *
 * Example:
 *
 * <code>
 *   // Define bank account parameters, which should look like this
 *   $parameters = [
 *       'accountNumber' => '112233',
 *       'routingNumber' => '44556666',
 *       'type' => 'checking',
 *   ];
 *
 *   // Create a bank object
 *   $bank = new BankAccount($parameters);
 * </code>
 *
 * The full list of card attributes that may be set via the parameter to
 * *new* is as follows:
 *
 * * accountNumber
 * * routingNumber
 * * type
 *
 * If any unknown parameters are passed in, they will be ignored.  No error is thrown.
 */
class BankAccount
{
    use ParametersTrait;

    /**
     * Create a new BankAccount object using the specified parameters
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
     * Validate this bank account. If the bank is invalid, InvalidBankAccountException is thrown.
     *
     * This method is called internally by gateways to avoid wasting time with an API call
     * when the bank account is clearly invalid.
     *
     * Generally if you want to validate the bank account yourself with custom error
     * messages, you should use your framework's validation library, not this method.
     *
     * @return void
     * @throws InvalidBankAccountException
     */
    public function validate()
    {
        $requiredParameters = array(
            'accountNumber' => 'bank account number',
            'routingNumber' => 'bank routing number',
            'type' => 'bank account type'
        );

        foreach ($requiredParameters as $key => $val) {
            if (!$this->getParameter($key)) {
                throw new InvalidBankAccountException("The $val is required");
            }
        }

		$type = $this->getParameter('type');
		if ($type !== 'checking' && $type !== 'savings') {
			throw new InvalidBankAccountException("Account type must be savings or checking, provided type: $type");
		}
    }

    /**
     * Get Card Number.
     *
     * @return string
     */
    public function getAccountNumber()
    {
        return $this->getParameter('accountNumber');
    }

    /**
     * Set Card Number
     *
     * Non-numeric characters are stripped out of the account number, so
     * it's safe to pass in strings such as "4444-3333 2222 1111" etc.
     *
     * @param string $value Parameter value
     * @return $this
     */
    public function setAccountNumber($value)
    {
        // strip non-numeric characters
        return $this->setParameter('accountNumber', preg_replace('/\D/', '', $value));
    }

    /**
     * Get the last 4 digits of the bank account number.
     *
     * @return string
     */
    public function getAccountNumberLastFour()
    {
        return substr($this->getAccountNumber(), -4, 4) ?: null;
    }

    /**
     * Returns a masked bank account number with only the last 4 chars visible
     *
     * @param string $mask Character to use in place of numbers
     * @return string
     */
    public function getAccountNumberMasked($mask = 'X')
    {
        $maskLength = strlen($this->getAccountNumber()) - 4;

        return str_repeat($mask, $maskLength) . $this->getAccountNumberLastFour();
    }

    /**
     * Get the account type.
     *
     * @return string
     */
    public function getType()
    {
        return $this->getParameter('type');
    }

    /**
     * Sets the account type.
     *
     * @param string $value
     * @return $this
     */
    public function setType($value)
    {
        return $this->setParameter('type', $value);
    }

    /**
     * Get the account routing number.
     *
     * @return string
     */
    public function getRoutingNumber()
    {
        return $this->getParameter('routingNumber');
    }

    /**
     * Sets the account routing number.
     *
     * @param string $value
     * @return $this
     */
    public function setRoutingNumber($value)
    {
        return $this->setParameter('routingNumber', $value);
    }
}
