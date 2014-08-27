<?php

use Inteleon\InteleonSoapClient;
use Inteleon\Exception\InteleonSoapClientException;

class InteleonSoapClientTest extends PHPUnit_Framework_TestCase
{
    public function testCall()
    {
        $client = new Inteleon\InteleonSoapClient('http://wsf.cdyne.com/WeatherWS/Weather.asmx?WSDL', array());
        $result = $client->__soapCall('GetCityWeatherByZIP', array('GetCityWeatherByZIP' => array('ZIP' => '11001')));
        $this->assertEquals(1, $result->GetCityWeatherByZIPResult->Success);
    }

    //Connect 3 times to non-routable address with a connect timeout of 1000 ms. Should take >= 3 seconds.
    public function testReconnect()
    {
        $start = microtime(true);
        $client = new Inteleon\InteleonSoapClient(null, array('uri' => '', 'location' => ''));
        $client->setConnectTimeout(1000);
        $client->setConnectAttempts(3);
        try {
            $client->__soapCall('', array(), array('location' => '10.255.255.1', 'uri' => ''));
        } catch (InteleonSoapClientException $e) {
        }
        $this->assertGreaterThanOrEqual(3, round((microtime(true)-$start)));
    }
}
