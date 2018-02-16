<?php
namespace Inchoo\Shipping\Model\Carrier;
 
use Magento\Quote\Model\Quote\Address\RateRequest;
use Magento\Shipping\Model\Rate\Result;
 
class Example extends \Magento\Shipping\Model\Carrier\AbstractCarrier implements
    \Magento\Shipping\Model\Carrier\CarrierInterface
{
    /**
     * @var string
     * Esse codigo deve bater com o ID configurado etc/adminhtml/system.xml
     * o group definido na section carriers deve ser igual a esse codigo.
     * 
     * Nao eh recomendado a utilizacao do caractere "_" nesse codigo
     */
    protected $_code = 'inchoo';
 
    /**
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory $rateErrorFactory
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Shipping\Model\Rate\ResultFactory $rateResultFactory
     * @param \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory $rateMethodFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory $rateErrorFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Shipping\Model\Rate\ResultFactory $rateResultFactory,
        \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory $rateMethodFactory,
        array $data = []
    ) {
        $this->_rateResultFactory = $rateResultFactory;
        $this->_rateMethodFactory = $rateMethodFactory;
        parent::__construct($scopeConfig, $rateErrorFactory, $logger, $data);
    }
 
    /**
     * @return array
     */
    public function getAllowedMethods()
    {
        return [$this->getCarrierCode() => $this->getConfigData('name')];
    }
 
    /**
     * @param RateRequest $request
     * @return bool|Result
     */
    public function collectRates(RateRequest $request)
    {
        if (!$this->getConfigFlag('active')) {
            return false;
        }
 
        /** @var \Magento\Shipping\Model\Rate\Result $result */
        $result = $this->_rateResultFactory->create();
 
        /** @var \Magento\Quote\Model\Quote\Address\RateResult\Method $method */
        $method = $this->_rateMethodFactory->create();
        //Carrier eh transportador, por exemplo, correios
        $method->setCarrier($this->getCarrierCode());
        $method->setCarrierTitle($this->getConfigData('title'));
 
        //o nome do metodo pode ser especificado pela API por exemplo PAC ou Sedex
        //primeiro o codigo exemplo 04014 - Código do sedex
        //nao eh recomendado utilizar codigos com o caractere "_"
        $method->setMethod(strtolower('0001'));
        //aqui o nome pra aparecer no frontend
        $method->setMethodTitle('Método 1');
        /*you can fetch shipping price from different sources over some APIs, we used price from config.xml - xml node price*/
        //$amount = $this->getConfigData('price');
        //um valor randomico entre 4 e 50
        $amount = rand(4,50);
 
        $method->setPrice($amount);
        $method->setCost($amount);
 
        $result->append($method);


        /* ################ OUTRO METODO DO MESMO TRANSPORTADOR #######*/
        /** @var \Magento\Quote\Model\Quote\Address\RateResult\Method $method */
        $method2 = $this->_rateMethodFactory->create();
 
        $method2->setCarrier($this->getCarrierCode());
        $method2->setCarrierTitle($this->getConfigData('title'));
 
        //o nome do metodo pode ser especificado pela API por exemplo PAC ou Sedex
        //primeiro o codigo exemplo 04014 - Código do sedex
        $method2->setMethod(strtolower('0002'));
        //aqui o nome pra aparecer no frontend
        $method2->setMethodTitle('Método 2');
 
        /*you can fetch shipping price from different sources over some APIs, we used price from config.xml - xml node price*/
        //$amount = $this->getConfigData('price');
        //um valor randomico entre 4 e 50
        $amount = rand(4,50);
 
        $method2->setPrice($amount);
        $method2->setCost($amount);

        $result->append($method2);
 
        return $result;
    }
}