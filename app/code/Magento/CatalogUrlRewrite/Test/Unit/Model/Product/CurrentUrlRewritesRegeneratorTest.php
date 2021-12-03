<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\CatalogUrlRewrite\Test\Unit\Model\Product;

use Magento\CatalogUrlRewrite\Model\ProductUrlRewriteGenerator;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\UrlRewrite\Model\OptionProvider;
use Magento\UrlRewrite\Service\V1\Data\UrlRewrite;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class CurrentUrlRewritesRegeneratorTest extends \PHPUnit\Framework\TestCase
{
    /** @var \Magento\CatalogUrlRewrite\Model\Product\CurrentUrlRewritesRegenerator */
    private $currentUrlRewritesRegenerator;

    /** @var \Magento\CatalogUrlRewrite\Model\ProductUrlPathGenerator|\PHPUnit\Framework\MockObject\MockObject */
    private $productUrlPathGenerator;

    /** @var \Magento\Catalog\Model\Product|\PHPUnit\Framework\MockObject\MockObject */
    private $product;

    /** @var \Magento\Catalog\Model\Category|\PHPUnit\Framework\MockObject\MockObject */
    private $category;

    /** @var \Magento\CatalogUrlRewrite\Model\ObjectRegistry|\PHPUnit\Framework\MockObject\MockObject */
    private $objectRegistry;

    /** @var \Magento\UrlRewrite\Service\V1\Data\UrlRewriteFactory|\PHPUnit\Framework\MockObject\MockObject */
    private $urlRewriteFactory;

    /** @var \Magento\UrlRewrite\Service\V1\Data\UrlRewrite|\PHPUnit\Framework\MockObject\MockObject */
    private $urlRewrite;

    /** @var \PHPUnit\Framework\MockObject\MockObject */
    private $mergeDataProvider;

    /** @var \PHPUnit\Framework\MockObject\MockObject */
    private $urlRewriteFinder;

    protected function setUp(): void
    {
        $this->urlRewriteFactory = $this->getMockBuilder(\Magento\UrlRewrite\Service\V1\Data\UrlRewriteFactory::class)
            ->setMethods(['create'])
            ->disableOriginalConstructor()->getMock();
        $this->urlRewrite = $this->getMockBuilder(\Magento\UrlRewrite\Service\V1\Data\UrlRewrite::class)
            ->disableOriginalConstructor()->getMock();
        $this->product = $this->getMockBuilder(\Magento\Catalog\Model\Product::class)
            ->disableOriginalConstructor()->getMock();
        $this->category = $this->getMockBuilder(\Magento\Catalog\Model\Category::class)
            ->disableOriginalConstructor()->getMock();
        $this->objectRegistry = $this->getMockBuilder(\Magento\CatalogUrlRewrite\Model\ObjectRegistry::class)
            ->disableOriginalConstructor()->getMock();
        $this->urlRewriteFinder = $this->getMockBuilder(\Magento\CatalogUrlRewrite\Model\Map\UrlRewriteFinder::class)
            ->disableOriginalConstructor()->getMock();
        $this->urlRewriteFactory->expects($this->once())->method('create')
            ->willReturn($this->urlRewrite);
        $this->productUrlPathGenerator = $this->getMockBuilder(
            \Magento\CatalogUrlRewrite\Model\ProductUrlPathGenerator::class
        )->disableOriginalConstructor()->getMock();
        $mergeDataProviderFactory = $this->createPartialMock(
            \Magento\UrlRewrite\Model\MergeDataProviderFactory::class,
            ['create']
        );
        $this->mergeDataProvider = new \Magento\UrlRewrite\Model\MergeDataProvider();
        $mergeDataProviderFactory->expects($this->once())->method('create')->willReturn($this->mergeDataProvider);
        $this->currentUrlRewritesRegenerator = (new ObjectManager($this))->getObject(
            \Magento\CatalogUrlRewrite\Model\Product\CurrentUrlRewritesRegenerator::class,
            [
                'productUrlPathGenerator' => $this->productUrlPathGenerator,
                'urlRewriteFactory' => $this->urlRewriteFactory,
                'mergeDataProviderFactory' => $mergeDataProviderFactory,
                'urlRewriteFinder' => $this->urlRewriteFinder
            ]
        );
    }

    public function testIsAutogeneratedWithoutSaveRewriteHistory()
    {
        $this->urlRewriteFinder->expects($this->once())->method('findAllByData')
            ->willReturn($this->getCurrentRewritesMocks([[UrlRewrite::IS_AUTOGENERATED => 1]]));
        $this->product->expects($this->once())->method('getData')->with('save_rewrites_history')
            ->willReturn(false);

        $this->assertEquals(
            [],
            $this->currentUrlRewritesRegenerator->generate('store_id', $this->product, $this->objectRegistry)
        );
    }

    public function testSkipGenerationForAutogenerated()
    {
        $this->urlRewriteFinder->expects($this->once())->method('findAllByData')
            ->willReturn($this->getCurrentRewritesMocks([
                [UrlRewrite::IS_AUTOGENERATED => 1, UrlRewrite::REQUEST_PATH => 'same-path'],
            ]));
        $this->product->expects($this->once())->method('getData')->with('save_rewrites_history')
            ->willReturn(true);
        $this->productUrlPathGenerator->expects($this->once())->method('getUrlPathWithSuffix')
            ->willReturn('same-path');

        $this->assertEquals(
            [],
            $this->currentUrlRewritesRegenerator->generate('store_id', $this->product, $this->objectRegistry, 1)
        );
    }

    public function testIsAutogeneratedWithoutCategory()
    {
        $requestPath = 'autogenerated.html';
        $targetPath = 'some-path.html';
        $autoGenerated = 1;
        $storeId = 2;
        $productId = 12;
        $description = 'description';
        $this->urlRewriteFinder->expects($this->once())->method('findAllByData')
            ->willReturn($this->getCurrentRewritesMocks([
                [
                    UrlRewrite::REQUEST_PATH => $requestPath,
                    UrlRewrite::TARGET_PATH => 'custom-target-path',
                    UrlRewrite::STORE_ID => $storeId,
                    UrlRewrite::IS_AUTOGENERATED => $autoGenerated,
                    UrlRewrite::METADATA => [],
                    UrlRewrite::DESCRIPTION => $description,
                ],
            ]));
        $this->product->expects($this->any())->method('getEntityId')->willReturn($productId);
        $this->product->expects($this->once())->method('getData')->with('save_rewrites_history')
            ->willReturn(true);
        $this->productUrlPathGenerator->expects($this->once())->method('getUrlPathWithSuffix')
            ->willReturn($targetPath);

        $this->prepareUrlRewriteMock(
            $storeId,
            $productId,
            $requestPath,
            $targetPath,
            0,
            OptionProvider::PERMANENT,
            [],
            $description
        );

        $this->assertEquals(
            [$this->urlRewrite],
            $this->currentUrlRewritesRegenerator->generate($storeId, $this->product, $this->objectRegistry, 1)
        );
    }

    public function testIsAutogeneratedWithCategory()
    {
        $productId = 12;
        $requestPath = 'autogenerated.html';
        $targetPath = 'simple-product.html';
        $autoGenerated = 1;
        $storeId = 2;
        $metadata = ['category_id' => 2, 'some_another_data' => 1];
        $description = 'description';
        $this->urlRewriteFinder->expects($this->once())->method('findAllByData')
            ->willReturn($this->getCurrentRewritesMocks([
                [
                    UrlRewrite::REQUEST_PATH => $requestPath,
                    UrlRewrite::TARGET_PATH => 'some-path.html',
                    UrlRewrite::STORE_ID => $storeId,
                    UrlRewrite::IS_AUTOGENERATED => $autoGenerated,
                    UrlRewrite::METADATA => $metadata,
                    UrlRewrite::DESCRIPTION => $description,
                ],
            ]));
        $this->product->expects($this->any())->method('getEntityId')->willReturn($productId);
        $this->product->expects($this->once())->method('getData')->with('save_rewrites_history')
            ->willReturn(true);
        $this->productUrlPathGenerator->expects($this->once())->method('getUrlPathWithSuffix')
            ->willReturn($targetPath);
        $this->objectRegistry->expects($this->once())->method('get')->willReturn($this->category);
        $this->prepareUrlRewriteMock(
            $storeId,
            $productId,
            $requestPath,
            $targetPath,
            0,
            OptionProvider::PERMANENT,
            $metadata,
            $description
        );

        $this->assertEquals(
            [$this->urlRewrite],
            $this->currentUrlRewritesRegenerator->generate($storeId, $this->product, $this->objectRegistry, 2)
        );
    }

    public function testSkipGenerationForCustom()
    {
        $this->urlRewriteFinder->expects($this->once())->method('findAllByData')
            ->willReturn($this->getCurrentRewritesMocks([
                [
                    UrlRewrite::IS_AUTOGENERATED => 0,
                    UrlRewrite::REQUEST_PATH => 'same-path',
                    UrlRewrite::REDIRECT_TYPE => 1,
                ],
            ]));
        $this->productUrlPathGenerator->expects($this->once())->method('getUrlPathWithSuffix')
            ->willReturn('same-path');

        $this->assertEquals(
            [],
            $this->currentUrlRewritesRegenerator->generate('store_id', $this->product, $this->objectRegistry)
        );
    }

    public function testGenerationForCustomWithoutTargetPathGeneration()
    {
        $storeId = 12;
        $productId = 123;
        $requestPath = 'generate-for-custom-without-redirect-type.html';
        $targetPath = 'custom-target-path.html';
        $autoGenerated = 0;
        $description = 'description';
        $this->urlRewriteFinder->expects($this->once())->method('findAllByData')
            ->willReturn($this->getCurrentRewritesMocks([
                [
                    UrlRewrite::REQUEST_PATH => $requestPath,
                    UrlRewrite::TARGET_PATH => $targetPath,
                    UrlRewrite::REDIRECT_TYPE => 0,
                    UrlRewrite::IS_AUTOGENERATED => $autoGenerated,
                    UrlRewrite::DESCRIPTION => $description,
                    UrlRewrite::METADATA => [],
                ],
            ]));
        $this->productUrlPathGenerator->expects($this->never())->method('getUrlPathWithSuffix');
        $this->product->expects($this->any())->method('getEntityId')->willReturn($productId);
        $this->prepareUrlRewriteMock(
            $storeId,
            $productId,
            $requestPath,
            $targetPath,
            $autoGenerated,
            0,
            [],
            $description
        );

        $this->assertEquals(
            [$this->urlRewrite],
            $this->currentUrlRewritesRegenerator->generate($storeId, $this->product, $this->objectRegistry)
        );
    }

    public function testGenerationForCustomWithTargetPathGeneration()
    {
        $storeId = 12;
        $productId = 123;
        $requestPath = 'generate-for-custom-without-redirect-type.html';
        $targetPath = 'generated-target-path.html';
        $autoGenerated = 0;
        $description = 'description';
        $this->urlRewriteFinder->expects($this->once())->method('findAllByData')
            ->willReturn($this->getCurrentRewritesMocks([
                [
                    UrlRewrite::REQUEST_PATH => $requestPath,
                    UrlRewrite::TARGET_PATH => 'custom-target-path.html',
                    UrlRewrite::REDIRECT_TYPE => 'code',
                    UrlRewrite::IS_AUTOGENERATED => $autoGenerated,
                    UrlRewrite::DESCRIPTION => $description,
                    UrlRewrite::METADATA => [],
                ],
            ]));
        $this->productUrlPathGenerator->expects($this->any())->method('getUrlPathWithSuffix')
            ->willReturn($targetPath);
        $this->product->expects($this->any())->method('getEntityId')->willReturn($productId);
        $this->prepareUrlRewriteMock($storeId, $productId, $requestPath, $targetPath, 0, 'code', [], $description);

        $this->assertEquals(
            [$this->urlRewrite],
            $this->currentUrlRewritesRegenerator->generate($storeId, $this->product, $this->objectRegistry)
        );
    }

    /**
     * @param array $currentRewrites
     * @return array
     */
    protected function getCurrentRewritesMocks($currentRewrites)
    {
        $rewrites = [];
        foreach ($currentRewrites as $urlRewrite) {
            /** @var \PHPUnit\Framework\MockObject\MockObject */
            $url = $this->getMockBuilder(\Magento\UrlRewrite\Service\V1\Data\UrlRewrite::class)
                ->disableOriginalConstructor()->getMock();
            foreach ($urlRewrite as $key => $value) {
                $url->expects($this->any())
                    ->method('get' . str_replace('_', '', ucwords($key, '_')))
                    ->willReturn($value);
            }
            $rewrites[] = $url;
        }
        return $rewrites;
    }

    /**
     * @param mixed $storeId
     * @param mixed $productId
     * @param mixed $requestPath
     * @param mixed $targetPath
     * @param mixed $autoGenerated
     * @param mixed $redirectType
     * @param mixed $metadata
     * @param mixed $description
     */
    protected function prepareUrlRewriteMock(
        $storeId,
        $productId,
        $requestPath,
        $targetPath,
        $autoGenerated,
        $redirectType,
        $metadata,
        $description
    ) {
        $this->urlRewrite->expects($this->any())->method('setStoreId')->with($storeId)
            ->willReturnSelf();
        $this->urlRewrite->expects($this->any())->method('setEntityId')->with($productId)
            ->willReturnSelf();
        $this->urlRewrite->expects($this->any())->method('setEntityType')
            ->with(ProductUrlRewriteGenerator::ENTITY_TYPE)->willReturnSelf();
        $this->urlRewrite->expects($this->any())->method('setRequestPath')->with($requestPath)
            ->willReturnSelf();
        $this->urlRewrite->expects($this->any())->method('setTargetPath')->with($targetPath)
            ->willReturnSelf();
        $this->urlRewrite->expects($this->any())->method('setIsAutogenerated')->with($autoGenerated)
            ->willReturnSelf();
        $this->urlRewrite->expects($this->any())->method('setRedirectType')->with($redirectType)
            ->willReturnSelf();
        $this->urlRewrite->expects($this->any())->method('setMetadata')->with($metadata)
            ->willReturnSelf();
        $this->urlRewriteFactory->expects($this->any())->method('create')->willReturn($this->urlRewrite);
        $this->urlRewrite->expects($this->once())->method('setDescription')->with($description)
            ->willReturnSelf();
    }
}