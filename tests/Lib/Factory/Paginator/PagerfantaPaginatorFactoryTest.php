<?php

namespace Tests\Lib\Factory\Paginator;

use Todo\Lib\Factory\Paginator\PagerfantaPaginatorFactory;
use PHPUnit\Framework\TestCase;
use Todo\Lib\Service\Paginator\PaginatorAdapter;

class PagerfantaPaginatorFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function shouldBeCreatedPaginator(): void
    {
        $rows = ['a', 'b', 'c', 'd', 'e', 'f', 'g'];
        $countRows = count($rows);
        $entityPerPage = 3;
        $currentPage = 3;

        $adapter = $this->getMockBuilder(PaginatorAdapter::class)
            ->setMethods(['setData', 'setCountRows', 'getNbResults'])
            ->getMock();
        $adapter->expects($this->once())
            ->method('setData')
            ->with($this->equalTo($rows));
        $adapter->expects($this->once())
            ->method('setCountRows')
            ->with($this->equalTo($countRows));
        $adapter->method('getNbResults')->willReturn($countRows);

        $factory = new PagerfantaPaginatorFactory($adapter, $entityPerPage);

        $paginator = $factory->create($rows, $countRows, $currentPage);

        $this->assertTrue(method_exists($paginator, 'getCurrentPageResults'));
        $this->assertTrue(method_exists($paginator, 'getHtml'));
    }
}
