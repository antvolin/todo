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

        $paginator = (new PagerfantaPaginatorFactory(new PaginatorAdapter(), 3))->create($rows, $countRows, 3);

        $this->assertTrue(method_exists($paginator, 'getCurrentPageResults'));
        $this->assertTrue(method_exists($paginator, 'getHtml'));
    }
}
