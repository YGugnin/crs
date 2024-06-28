<?php
declare(strict_types=1);

namespace App\controllers\Cli;

use App\exceptions\ControllerException;
use App\exceptions\ModelException;
use App\interfaces\FileStorageInterface;
use App\interfaces\JsonParserInterface;
use App\interfaces\LoggerInterface;
use App\models\BinModel;
use App\models\ExchangeModel;
use App\models\InputRecordModel;
use App\services\Api\BinListApi;
use App\services\Api\ExchangeApi;
use App\services\EUIdentifier\EUIdentifier;
use NumberFormatter;

readonly class IndexController extends CliController
{
    public function __construct(
        private array $fixedCurrency,
        private float $euRatePercent,
        private float $outsideEuRatePercent,
        private string $moneyLocale,
        private string $currencyCode,
        private LoggerInterface $logger,
        private FileStorageInterface $fileStorage,
        private JsonParserInterface $jsonParser,
        private BinListApi $binListApi,
        private ExchangeApi $exchangeApi,
        private EUIdentifier $identifier
    )
    {

    }
    
    /**
     * @param string $filepath
     * @param bool $pretty
     * @return void
     * @throws ControllerException
     * @throws ModelException
     */
    public function indexAction(string $filepath, mixed $pretty = false): void {
        $rates = new ExchangeModel($this->jsonParser->parse($this->exchangeApi->getRates()));
        if (!$rates->getSuccess()) {
            $this->exchangeApi->removeCache();
            throw new ControllerException('Incorrect exchange rates');
        }
        
        $recordModel = new InputRecordModel();
        $data = $recordModel->toArray($this->jsonParser->parseArray($this->fileStorage->getArrayContent($filepath)));
        $result = [];
        
        $formatter = new NumberFormatter($this->moneyLocale, $pretty ? NumberFormatter::CURRENCY : NumberFormatter::DECIMAL);
        if ($pretty){
            $formatter->setTextAttribute(NumberFormatter::CURRENCY_CODE, $this->currencyCode);
        }
        $formatter->setAttribute(NumberFormatter::ROUNDING_MODE, NumberFormatter::ROUND_CEILING);
        
        foreach ($data as $record) {
            $binModel = new BinModel($this->jsonParser->parse($this->binListApi->getBin($record->getBin())));
            $rate = array_key_exists($record->getCurrency(), $rates->getRates()) ? $rates->getRates()[$record->getCurrency()] : 0;
            $amountFixed = (array_key_exists($record->getCurrency(), $this->fixedCurrency) || !$rate ? $record->getAmount() : $record->getAmount() / $rate)
                * ($this->identifier->isEU($binModel->getAlpha2()) ? $this->euRatePercent : $this->outsideEuRatePercent);
            
            $amountFixed = $formatter->format($amountFixed);
            
            $result[] = $pretty ? $this->colorized((string)$record->getAmount(), 32) . ' ' .
                                  $this->colorized($record->getCurrency(), 31) . ' ' .
                                  $this->colorized('(in country ' . $binModel->getCountryName() . ')', 34) . ' = ' .
                                  $amountFixed
                                : $amountFixed;
        }
        $this->logger->log('Success', 'Commissions calculated');
        $this->stdout(implode(PHP_EOL, $result));
    }
    
    /**
     * @return void
     */
    public function helpAction(): void {
        $this->stdout($this->getUsageContent(self::class));
    }
}