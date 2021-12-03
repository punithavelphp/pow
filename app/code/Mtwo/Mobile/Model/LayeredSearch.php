<?php

namespace Mtwo\Mobile\Model;
use Mtwo\Mobile\Api\LayeredSearchInterface;

//Magento\LayeredNavigation\Block\Navigation\Category
//   Magento\Catalog\Block\Navigation
class LayeredSearch implements LayeredSearchInterface {

    public function __construct(\Magento\Framework\Webapi\Rest\Request
    $request) {

        $this->_request = $request;
    }

    /**
     * Retrieve filterlist
     *
     * @api
     * @return array
     */
    public function searchLayeredFilter($category) {

       // $category = $this->_request->getParam('categoryId');

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

        $filterableAttributes = $objectManager->getInstance()->get(\Magento\Catalog\Model\Layer\Category\FilterableAttributeList::class);

        $appState = $objectManager->getInstance()->get(\Magento\Framework\App\State::class);
        $layerResolver = $objectManager->getInstance()->get(\Magento\Catalog\Model\Layer\Resolver::class);
        $filterList = $objectManager->getInstance()->create(
                \Magento\Catalog\Model\Layer\FilterList::class, [
            'filterableAttributes' => $filterableAttributes
                ]
        );

        $layer = $layerResolver->get();
        $layer->setCurrentCategory($category);
        $filters = $filterList->getFilters($layer);
        $maxPrice = $layer->getProductCollection()->getMaxPrice();
        $minPrice = $layer->getProductCollection()->getMinPrice();

        $i = 0;
        foreach ($filters as $filter) {
            //$availablefilter = $filter->getRequestVar(); //Gives the request param name such as 'cat' for Category, 'price' for Price
            $availablefilter = (string) $filter->getName(); //Gives Display Name of the filter such as Category,Price etc.
            $items = $filter->getItems(); //Gives all available filter options in that particular filter
            $filterValues = array();
            $j = 0;
            foreach ($items as $item) {


                $filterValues[$j]['display'] = strip_tags($item->getLabel());
                $filterValues[$j]['value'] = $item->getValue();
                $filterValues[$j]['count'] = $item->getCount(); //Gives no. of products in each filter options
                $j++;
            }
            if (!empty($filterValues) && count($filterValues) > 1) {
                $filterArray['availablefilter'][$availablefilter] = $filterValues;
            }
            $i++;
        }


        echo json_encode($filterArray);
        exit;
    }

}
