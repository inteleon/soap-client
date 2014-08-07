<?php
namespace Inteleon;

use SoapClient;
use RuntimeException;
use Inteleon\Exception\InteleonSoapClientException;

class InteleonSoapClient extends SoapClient
{
	/** @var array cURL options */
	protected $curl_options = array();
	
	/** @var int Number of connect attempts to be made if connection error occurs */
	protected $connect_attempts;

	/**
	 * Constructor
	 *
	 * @param string $wsdl WSDL
	 * @param array $options SoapClient options
	 * 
	 * @todo Timeouts on the WSDL fetching??
	 */
	public function __construct($wsdl, $options)
	{
		//Default options
		$this->setTimeout(30000);
		$this->setConnectTimeout(30000);
		$this->setConnectAttempts(1);
		$this->setVerifyCertificate(true);
		$this->setUserAgent(null);
		
		//Set HTTP authentication username/password from SoapClient options
		if (isset($options['authentication']) && $options['authentication'] === SOAP_AUTHENTICATION_BASIC) {
			$this->setCurlOption(CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
			$this->setCurlOption(CURLOPT_USERPWD, ($options['login'] . ':' . $options['password']));
		}

		//Call parent constructor (SoapClient)
		parent::__construct($wsdl, $options);
	}

	/**
	 * The maximum number of milliseconds to allow cURL functions to execute
	 *
	 * @param int $value
	 */
	public function setTimeout($value)
	{
		$this->setCurlOption(CURLOPT_TIMEOUT_MS, $value);
	}

	/**
	 * The number of milliseconds to wait while trying to connect.
	 *
	 * @param int $value
	 */
	public function setConnectTimeout($value)
	{
		$this->setCurlOption(CURLOPT_CONNECTTIMEOUT_MS, $value);
	}

	/**
	 * Number of connection attempts to be made if error occurs
	 *
	 * @param int $value
	 */
	public function setConnectAttempts($value)
	{
		if ($value < 1) {
			throw new RuntimeException('Connect attempts must be at least 1');
		}
		$this->connect_attempts = $value;
	}

	/**
	 * FALSE to stop cURL from verifying the peer's certificate
	 * 
	 * WARNING: Turning off CURLOPT_SSL_VERIFYPEER allows man in the middle
	 * (MITM) attacks, which you don't want!
	 *
	 * @param boolean $value
	 */
	public function setVerifyCertificate($value)
	{
		$this->setCurlOption(CURLOPT_SSL_VERIFYPEER, $value);
	}

	/**
	 * Set user agent
	 *
	 * @param string $value
	 */
	public function setUserAgent($value)
	{
		$this->setCurlOption(CURLOPT_USERAGENT, $value);
	}

	/**
	 * Set a cURL option
	 *
	 * @param string $option
	 * @param string $value
	 */
	public function setCurlOption($option, $value)
	{
		$this->curl_options[$option] = $value;
	}

	/**
	 * Performs a SOAP request
	 * 
	 * @see SoapClient class
	 */
	public function __doRequest($request, $location, $action, $version, $one_way = 0)
	{
		echo "Tjena!";
		for ($attempt = 0; $attempt < $this->connect_attempts; $attempt++) {

			$ch = curl_init($location);

			if ($ch === false) {
				throw new RuntimeException('cURL initialisation failed');
			}

			$curl_options = $this->curl_options;

			$curl_options[CURLOPT_RETURNTRANSFER] = true;
			$curl_options[CURLOPT_POST] = true;
			$curl_options[CURLOPT_POSTFIELDS] = $request;
			$curl_options[CURLOPT_HTTPHEADER] = array(
				sprintf('Content-Type: %s', $version == 2 ? 'application/soap+xml' : 'text/xml'),
				sprintf('SOAPAction: %s', $action)
			);
			$curl_options[CURLOPT_HEADER] = false; //Don't include the header in the body output
			$curl_options[CURLOPT_NOSIGNAL] = true; //http://www.php.net/manual/en/function.curl-setopt.php#104597

			if (curl_setopt_array($ch, $curl_options) === false) {
				throw new RuntimeException('Failed setting curl options');
			}

			$response = curl_exec($ch);

			if (curl_errno($ch) !== 0 && $attempt >= $this->connect_attempts) {
				throw new InteleonSoapClientException("cURL connection error: " . curl_error($ch));
			}
			
			curl_close($ch);

			if (!$one_way) {
				return ($response);
			} else {
				return;
			}
		}
	}
}