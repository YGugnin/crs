<?php

declare(strict_types=1);

use App\core\ProjectContainerBuilder;
use App\exceptions\RequestException;
use App\interfaces\FileStorageInterface;
use App\interfaces\LoggerInterface;
use App\services\Api\ExchangeApi;
use App\services\Request\Simple;
use DI\DependencyException;
use DI\NotFoundException;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit\Framework\TestCase;

final class ExchangeApiTest extends TestCase {
    /**
     * @var vfsStreamDirectory
     */
    private vfsStreamDirectory $virtualDirectory;
    /**
     * @return void
     */
    public function setUp(): void {
        $this->virtualDirectory = vfsStream::setup('virtual');
    }
        /**
     * @return void
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function testCanGetRates(): void {
        $mockJSON = '{"success":true,"timestamp":1719554704,"base":"EUR","date":"2024-06-28","rates":{"AED":3.926708,"AFN":75.959607,"ALL":100.13357,"AMD":415.428283,"ANG":1.927497,"AOA":912.581266,"ARS":973.914398,"AUD":1.613859,"AWG":1.926982,"AZN":1.817855,"BAM":1.954503,"BBD":2.159767,"BDT":125.686617,"BGN":1.95512,"BHD":0.402927,"BIF":3076.858757,"BMD":1.069061,"BND":1.451237,"BOB":7.391097,"BRL":5.882189,"BSD":1.06969,"BTC":1.739366e-5,"BTN":89.286801,"BWP":14.593319,"BYN":3.500578,"BYR":20953.591017,"BZD":2.15607,"CAD":1.467355,"CDF":3057.513755,"CHF":0.961835,"CLF":0.036986,"CLP":1020.557529,"CNY":7.76983,"CNH":7.802225,"COP":4453.343671,"CRC":559.228998,"CUC":1.069061,"CUP":28.33011,"CVE":110.192927,"CZK":25.077385,"DJF":190.45365,"DKK":7.458569,"DOP":63.178087,"DZD":143.769434,"EGP":51.348167,"ERN":16.035911,"ETB":61.747236,"EUR":1,"FJD":2.400844,"FKP":0.838787,"GBP":0.846215,"GEL":2.995224,"GGP":0.838787,"GHS":16.312178,"GIP":0.838787,"GMD":72.455638,"GNF":9206.891985,"GTQ":8.311564,"GYD":223.791533,"HKD":8.345735,"HNL":26.475436,"HRK":7.503137,"HTG":141.785937,"HUF":396.35,"IDR":17524.311344,"ILS":4.016456,"IMP":0.838787,"INR":89.219161,"IQD":1401.283471,"IRR":45007.458165,"ISK":148.887892,"JEP":0.838787,"JMD":166.859303,"JOD":0.757641,"JPY":172.096314,"KES":138.518554,"KGS":92.420376,"KHR":4394.084886,"KMF":491.821314,"KPW":962.15483,"KRW":1474.459583,"KWD":0.327924,"KYD":0.891417,"KZT":499.053587,"LAK":23605.56046,"LBP":95666.511123,"LKR":327.225973,"LRD":207.943991,"LSL":19.628961,"LTL":3.156659,"LVL":0.646664,"LYD":5.210543,"MAD":10.62655,"MDL":19.184452,"MGA":4786.824336,"MKD":61.537347,"MMK":3472.267667,"MNT":3688.259748,"MOP":8.603692,"MRU":42.188012,"MUR":50.454661,"MVR":16.46887,"MWK":1854.357564,"MXN":19.746573,"MYR":5.047059,"MZN":68.093868,"NAD":19.628961,"NGN":1646.856364,"NIO":39.373879,"NOK":11.411753,"NPR":142.857226,"NZD":1.763704,"OMR":0.411541,"PAB":1.06969,"PEN":4.087788,"PGK":4.117269,"PHP":62.707369,"PKR":297.764658,"PLN":4.312677,"PYG":8061.651758,"QAR":3.896727,"RON":4.977124,"RSD":117.119866,"RUB":93.014002,"RWF":1396.473555,"SAR":4.010565,"SBD":9.022252,"SCR":15.740557,"SDG":642.505552,"SEK":11.372642,"SGD":1.45194,"SHP":1.350705,"SLE":24.425155,"SLL":22417.671786,"SOS":607.2266,"SRD":32.737848,"STD":22127.399435,"SVC":9.359878,"SYP":2686.047702,"SZL":19.621982,"THB":39.392748,"TJS":11.391949,"TMT":3.752403,"TND":3.354306,"TOP":2.52694,"TRY":35.250569,"TTD":7.268246,"TWD":34.690489,"TZS":2807.937273,"UAH":43.318062,"UGX":3968.367315,"USD":1.069061,"UYU":42.042895,"UZS":13456.073003,"VEF":3872728.787962,"VES":38.874189,"VND":27202.251195,"VUV":126.921006,"WST":2.994028,"XAF":655.522115,"XAG":0.03668,"XAU":0.00046,"XCD":2.88919,"XDR":0.812261,"XOF":655.522115,"XPF":119.331742,"YER":267.736215,"ZAR":19.746087,"ZMK":9622.830163,"ZMW":27.516745,"ZWL":344.237131}}';
        
        $mock = $this->getMockBuilder(Simple::class)
            ->setConstructorArgs([ProjectContainerBuilder::get(LoggerInterface::class)])
            ->getMock();
        $mock->method('get')->willReturn($mockJSON);
        $api = new ExchangeApi(
                'unused-url',
                ProjectContainerBuilder::get('api_exchange_rates_cache_enabled'),
                ProjectContainerBuilder::get('api_exchange_rates_cache_ttl'),
                ProjectContainerBuilder::get('api_exchange_rates_key'),
                $this->virtualDirectory->url(),
                $mock,
                ProjectContainerBuilder::get(FileStorageInterface::class)
            );
        $this->assertEquals($api->getRates(), $mockJSON);
    }
    /**
     * @return void
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function testThrowRatesBadRequest(): void {
        $this->expectException(RequestException::class);
        $api = new ExchangeApi(
            'WrongUrl',
            ProjectContainerBuilder::get('api_exchange_rates_cache_enabled'),
            ProjectContainerBuilder::get('api_exchange_rates_cache_ttl'),
            ProjectContainerBuilder::get('api_exchange_rates_key'),
            ProjectContainerBuilder::get('api_cache_path'),
            ProjectContainerBuilder::get(Simple::class),
            ProjectContainerBuilder::get(FileStorageInterface::class)
        );
        $api->getRates();
    }
    /**
     * @return void
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function testCanRemoveCache(): void {
        $this->assertNull(ProjectContainerBuilder::get(ExchangeApi::class)->removeCache());
    }
    /**
     * @return void
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function testThrowExceptionOnUnknownCall(): void {
        $this->expectException(Error::class);
        ProjectContainerBuilder::get(ExchangeApi::class)->unknownCall(1);
    }
}