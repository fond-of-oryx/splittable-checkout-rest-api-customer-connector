<?php

namespace FondOfOryx\Zed\SplittableCheckoutRestApiCustomerConnector;

use Codeception\Test\Unit;
use FondOfOryx\Zed\SplittableCheckoutRestApiCustomerConnector\Dependency\QueryContainer\SplittableCheckoutRestApiCustomerConnectorToCustomerQueryContainerInterface;
use Spryker\Shared\Kernel\BundleProxy;
use Spryker\Zed\Customer\Persistence\CustomerQueryContainerInterface;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Kernel\Locator;

class SplittableCheckoutRestApiCustomerConnectorDependencyProviderTest extends Unit
{
    /**
     * @var \Spryker\Zed\Kernel\Container|\PHPUnit\Framework\MockObject\MockObject|null
     */
    protected $containerMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Kernel\Locator
     */
    protected $locatorMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Shared\Kernel\BundleProxy
     */
    protected $bundleProxyMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Customer\Persistence\CustomerQueryContainerInterface
     */
    protected $customerQueryContainerMock;

    /**
     * @var \FondOfOryx\Zed\SplittableCheckoutRestApiCustomerConnector\SplittableCheckoutRestApiCustomerConnectorDependencyProvider
     */
    protected $splittableCheckoutRestApiCustomerConnectorDependencyProvider;

    /**
     * @return void
     */
    protected function _before(): void
    {
        parent::_before();

        $this->containerMock = $this->getMockBuilder(Container::class)
            ->setMethodsExcept(['factory', 'set', 'offsetSet', 'get', 'offsetGet'])
            ->getMock();

        $this->locatorMock = $this->getMockBuilder(Locator::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->bundleProxyMock = $this->getMockBuilder(BundleProxy::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->customerQueryContainerMock = $this->getMockBuilder(CustomerQueryContainerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->splittableCheckoutRestApiCustomerConnectorDependencyProvider = new SplittableCheckoutRestApiCustomerConnectorDependencyProvider();
    }

    /**
     * @return void
     */
    public function testProvidePersistenceLayerDependencies(): void
    {
        $this->containerMock->expects(static::atLeastOnce())
            ->method('getLocator')
            ->willReturn($this->locatorMock);

        $this->locatorMock->expects(static::atLeastOnce())
            ->method('__call')
            ->with('customer')
            ->willReturn($this->bundleProxyMock);

        $this->bundleProxyMock->expects(static::atLeastOnce())
            ->method('__call')
            ->with('queryContainer')
            ->willReturn($this->customerQueryContainerMock);

        $container = $this->splittableCheckoutRestApiCustomerConnectorDependencyProvider
            ->providePersistenceLayerDependencies($this->containerMock);

        static::assertEquals($this->containerMock, $container);

        static::assertInstanceOf(
            SplittableCheckoutRestApiCustomerConnectorToCustomerQueryContainerInterface::class,
            $this->containerMock[SplittableCheckoutRestApiCustomerConnectorDependencyProvider::QUERY_CONTAINER_CUSTOMER]
        );
    }
}
