<?php

namespace Tests\Lib\Service\Paginator;

use PHPUnit\Framework\TestCase;
use Todo\Lib\Factory\Paginator\PagerfantaPaginatorServiceFactory;
use Todo\Lib\Service\Ordering\OrderingService;
use Todo\Lib\Service\Paginator\PaginatorAdapter;
use Todo\Lib\Service\Paginator\PaginatorServiceInterface;

class PagerfantaPaginatorServiceTest extends TestCase
{
    private PaginatorServiceInterface $paginator;
    private int $prevPage;
    private int $currentPage;
    private int $nextPage;

    protected function setUp()
    {
        $this->prevPage = 1;
        $this->currentPage = 2;
        $this->nextPage = 3;

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

        $this->paginator = (new PagerfantaPaginatorServiceFactory(new PaginatorAdapter(), 3))->create($rows, count($rows), $this->currentPage);
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
        $prevUrl = sprintf(
            '<a href="%s" rel="prev">Previous</a><a href="%s">%s</a>',
            $this->getUrl($this->prevPage),
            $this->getUrl($this->prevPage),
            $this->prevPage
        );
        $currentUrl = sprintf(
            '<span class="current">%s</span>',
            $this->currentPage
        );
        $nextUrl = sprintf(
            '<a href="%s">%s</a><a href="%s" rel="next">Next</a>',
            $this->getUrl($this->nextPage),
            $this->nextPage,
            $this->getUrl($this->nextPage)
        );

        $html1 = sprintf('<nav>%s%s%s</nav>', $prevUrl, $currentUrl, $nextUrl);
        $html2 = $this->paginator->getHtml(OrderingService::ALLOWED_ORDER_BY_FIELDS[0], OrderingService::ASC);

        $this->assertEquals($html1, $html2);
    }

    private function getUrl(int $page): string
    {
        return sprintf('/entity/list?page=%s&orderBy=user_name&order=ASC', $page);
    }
}
