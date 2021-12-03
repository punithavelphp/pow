<?php

namespace Mtwo\Mobile\Model;

use Mtwo\Mobile\Api\ConfigInterface;
use \Magento\Store\Model\StoreRepository;
use \Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Directory\Model\AllowedCountries;
use Magento\Directory\Helper\Data as DirHelper;
use Magento\Directory\Model\CountryFactory;
use Magento\Directory\Model\ResourceModel\Region\CollectionFactory as RegCollectionFactory;

class Config implements ConfigInterface {

    /**
     * @var ObjectManagerInterface
     */
    protected $_objectManager;
    protected $_storeRepository;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;
    protected $logger;
    protected $_regionCollection;

    public function __construct(\Magento\Framework\ObjectManagerInterface $objectManager, \Magento\Store\Model\StoreManagerInterface $storeManager
    , \Magento\Directory\Model\Currency $currency
    , \Magento\Framework\Locale\CurrencyInterface $localeCurrency
    , \Magento\Framework\App\ResourceConnection $resource
    , \Magento\Framework\Locale\FormatInterface $localeFormat
    , \Psr\Log\LoggerInterface $logger
    , StoreRepository $storeRepository
    , \Magento\Framework\Filesystem $filesystem
    , \Magento\Framework\File\Csv $csvProcessor
    , \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfigInterface
    , CountryFactory $countryFactory
    , RegCollectionFactory $regCollectionFactory
,\Magento\Store\Model\Information $storeInformation
								,\Magento\Store\Model\Store $store
    ) {
        $this->_objectManager = $objectManager;
        $this->_resource = $resource;
        $this->_localeFormat = $localeFormat;
        $this->_currency = $currency;
        $this->_localeCurrency = $localeCurrency;
        $this->_storeManager = $storeManager;
        $this->logger = $logger;
        $this->_storeRepository = $storeRepository;
        $this->_filesystem = $filesystem;
        $this->csvProcessor = $csvProcessor;
        $this->scopeConfig = $scopeConfigInterface;
        $this->countryFactory = $countryFactory;
		        $this->storeInformation = $storeInformation;
		        $this->store = $store;

    }

    public function getConfig($lang = 0) {

 		

		 

        $configArray = array();
        if ($lang == 0) {
            $lang = 1;
        } else if ($lang == 1) {
            $lang = 3;
        }
        $stores = $this->getAllStores();
        $websiteIds = array();
        $storeList = array();
		$day = array('','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday');
        foreach ($stores as $store) {
			$storeInfo = $this->storeInformation->getStoreInformationObject($store);
            $storeId = $store["store_id"];
            if ($lang == $storeId) {
                //print_r($store->getData());
                $websiteId = $store["website_id"];
                $storeName = $store["name"];
                $rootCat = $this->getRootCategoryIdByStoreId($storeId);
                $baseCurrencyCode = $this->getBaseCurrencyCode($storeId);
                $google_apikey = $this->getStoreConfig('mobile_tabs/store_config/google_apikey');
              /*  if ($lang == 1) {
                    $langFilename = $this->getStoreConfig('mobile_tabs/store_config/lang_filename_en');
                } else {
                    $langFilename = $this->getStoreConfig('mobile_tabs/store_config/lang_filename_ar');
                } */
				// 
				for($i=1;$i<=7;$i++){
 					$delivery_hours[] = array('day'=>$day[$i],'hours'=>$this->getStoreConfig('hours/delivery_hours/day'.$i));
				}
				for($i=1;$i<=7;$i++){
 					$opening_hours[] = array('day'=>$day[$i],'hours'=>$this->getStoreConfig('hours/opening_hours/day'.$i));
				}
            //    $lang_content = $this->getLangContent($langFilename);

                $allowedCountries = $this->scopeConfig->getValue(
                        AllowedCountries::ALLOWED_COUNTRIES_PATH, \Magento\Store\Model\ScopeInterface::SCOPE_STORE
                );
                $countryIds = explode(',', $allowedCountries);

                //$countries = $this->_objectManager->get('\Magento\Directory\Helper\Data')->getCountryCollection();

                $countries = $this->getSelectedCountriesName($countryIds);
                foreach ($countries as $countr) {
                    $countryArray[$countr->getCountryId()] = $countr->getName();
                    $regionData[$countr->getCountryId()] = $this->getRegionsOfCountry($countr->getCountryId());
                }


                $media_url = $this->getImageUrl();
			    $store->setData('opening_hours', $opening_hours);
 				$store->setData('delivery_hours', $delivery_hours);
                $store->setData('base_currency_code', $baseCurrencyCode);
                $store->setData('root_cat', $rootCat);
                $store->setData('google_apikey', $google_apikey);
                $store->setData('media_url', $media_url);
                $store->setData('countries', $countryArray);
                $store->setData('region', $regionData);
				 $store->setData('info', $storeInfo->getData());
                // $store->setData('lang_content', $lang_content);
                $storeList[] = $store->getData();
            }
        }

        $configArray['config']['store'] = $storeList;
   //     $configArray['config']['language'] = $lang_content;
        return ($configArray);
    }

    public function getInvoice($invoiceId) { // will move this seperate model. For testing created here
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $fileFactory = $objectManager->get('Magento\Framework\App\Response\Http\FileFactory');
        $invoice = $objectManager->get('Magento\Sales\Api\InvoiceRepositoryInterface')->get($invoiceId);
        $pdf = $objectManager->create('Magento\Sales\Model\Order\Pdf\Invoice')->getPdf([$invoice]);
        $date = $objectManager->create('Magento\Framework\Stdlib\DateTime\DateTime')->date('Y-m-d_H-i-s');
        /* $fileFactory->create(
          'invoice' . $date . '.pdf',
          $pdf->render(),
          \Magento\Framework\App\Filesystem\DirectoryList::VAR_DIR,
          'application/pdf',
          null
          ); */
        return base64_encode($pdf->render());
    }

    public function getRegionsOfCountry($countryCode) {
        $regionCollection = $this->countryFactory->create()->loadByCode($countryCode)->getRegions();
        $regions = $regionCollection->loadData()->toOptionArray(false);
        return $regions;
    }

    public function getSelectedCountriesName($country_ids) {

        return $this->countryFactory->create()->getCollection()
                        ->addFieldToFilter('country_id', array($country_ids))->load();
    }

    public function getLangContent($filename) {
        $filenameArr = explode('.', $filename);
        $filename = $filenameArr[0];
        $mediaPath = $this->_filesystem->getDirectoryRead(DirectoryList::MEDIA)->getAbsolutePath()
                . 'test/default/' . $filename . '.csv';
        if (!file_exists($mediaPath)) {
            $mediaPath = $this->_filesystem->getDirectoryRead(DirectoryList::MEDIA)->getAbsolutePath()
                    . 'test/default/default.csv';
        }
        $importProductRawData = $this->csvProcessor->getData($mediaPath);

        $count = 0;
        $lan_text = array();
        foreach ($importProductRawData as $rowIndex => $dataRow) {
            if ($rowIndex > 0) {
                $lan_text[$dataRow[0]] = ($dataRow[1]);
                ;
            }
        }
        return ($lan_text);
        ;
    }

    public function getRootCategoryIdByStoreId($storeId = '') {
        return $this->_storeManager->getStore($storeId)->getRootCategoryId();
    }

    public function getAllStores() {
        return $this->_storeRepository->getList();
        ;
    }

    public function getCurrentStoreId($storeId) {
        // give the current store id
        return $this->_storeManager->getStore($storeId)->getStoreId();
    }

    public function getWebsiteId() {
        // give the current store id
        return $this->_storeManager->getStore(true)->getWebsite()->getId();
    }

    public function getAllWebsites() {
        // give the current store id
        return $this->_storeManager->getWebsites();
    }

    public function getCurrentCurrencyCode($storeId) {
        return $this->_storeManager->getStore($storeId)->getCurrentCurrencyCode();
        // give the currency code
    }

    public function getBaseCurrencyCode($storeId) {
        return $this->_storeManager->getStore($storeId)->getBaseCurrencyCode();
    }

    public function getConfigAllowCurrencies() {
        return $this->_currency->getConfigAllowCurrencies();
    }

    /**
     * Retrieve currency rates to other currencies.
     *
     * @param string     $currency
     * @param array|null $toCurrencies
     *
     * @return array
     */
    public function getCurrencyRates($currency, $toCurrencies = null) {
        // give the currency rate
        return $this->_currency->getCurrencyRates($currency, $toCurrencies);
    }

    /**
     * Retrieve currency Symbol.
     *
     * @return string
     */
    public function getCurrencySymbol() {
        return $this->_localeCurrency->getCurrency(
                        $this->getBaseCurrencyCode()
                )->getSymbol();
    }

    /**
     * Retrieve price format.
     *
     * @return string
     */
    public function getPriceFormat() {
        return $this->_localeFormat->getPriceFormat('', $this->getBaseCurrencyCode());
    }

    public function getStoreConfig($key) {
        return $this->scopeConfig->getValue(
                        $key, \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function getImageUrl() {

        return $this->_storeManager->getStore()->getBaseUrl(
                        \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
        );
    }

    public function getHomePageSliderValues($key) {
        return $this->scopeConfig->getValue($key, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    public function getSliderProductType($sliderId) {
        $productType = 'custom';
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $sliderFactory = $objectManager->create('Mageplaza\Productslider\Model\Slider');
        $sliderCollection = $sliderFactory->getCollection()->addFieldToFilter('slider_id', $sliderId);
        foreach ($sliderCollection as $sliderDetails) {
            $productType = $sliderDetails->getProductType();
        }
        return $productType;
    }

}
