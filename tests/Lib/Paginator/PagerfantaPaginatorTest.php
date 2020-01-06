<?php

namespace BeeJeeMVC\Tests\Lib;

use BeeJeeMVC\Lib\Ordering;
use BeeJeeMVC\Lib\Paginator\PagerfantaPaginator;
use BeeJeeMVC\Lib\Paginator\PaginatorAdapter;
use PHPUnit\Framework\TestCase;

class PagerfantaPaginatorTest extends TestCase
{
    /**
     * @var PagerfantaPaginator
     */
    protected $paginator;

    protected function setUp()
    {
        $currentPage = 2;
        $rows = [
            'a' => 0,
            'b' => 111,
            'c' => 222,
            'd' => 3123,
            'e' => 2134,
            'f' => 9123,
            'g' => 81273,
            'h' => 91238,
            'i' => 92,
        ];

        $adapter = new PaginatorAdapter();
        $adapter->setData($rows);
        $adapter->setCountRows(count($rows));
        $this->paginator = new PagerfantaPaginator($adapter, $currentPage);
    }

    /**
     * @test
     */
    public function shouldBeGettingCurrentPageResults(): void
    {
        $rows = [
            'a' => 0,
            'b' => 111,
            'c' => 222,
        ];

        $this->assertEquals($rows, $this->paginator->getCurrentPageResults());
    }

    /**
     * @test
     */
    public function shouldBeGettingHtmlContent(): void
    {
        $html1 = '<nav><a href="/task/list?page=1&orderBy=user_name&order=ASC" rel="prev">Previous</a><a href="/task/list?page=1&orderBy=user_name&order=ASC">1</a><span class="current">2</span><a href="/task/list?page=3&orderBy=user_name&order=ASC">3</a><a href="/task/list?page=3&orderBy=user_name&order=ASC" rel="next">Next</a></nav>';
        $html2 = $this->paginator->getHtml(Ordering::ALLOWED_ORDER_BY_FIELDS[0], Ordering::ASC);

        $this->assertEquals($html1, $html2);
    }
}
