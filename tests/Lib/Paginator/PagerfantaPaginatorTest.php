<?php

namespace BeeJeeMVC\Tests\Lib\Paginator;

use BeeJeeMVC\Lib\Factory\Paginator\PagerfantaPaginatorFactory;
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

    /**
     * @var int
     */
    protected $prevPage;

    /**
     * @var int
     */
    protected $currentPage;

    /**
     * @var int
     */
    protected $nextPage;

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

        $this->paginator = (new PagerfantaPaginatorFactory(new PaginatorAdapter()))->create($rows, count($rows), $this->currentPage);
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
        $html2 = $this->paginator->getHtml(Ordering::ALLOWED_ORDER_BY_FIELDS[0], Ordering::ASC);

        $this->assertEquals($html1, $html2);
    }

    /**
     * @param int $page
     *
     * @return string
     */
    private function getUrl(int $page): string
    {
        return sprintf('/task/list?page=%s&orderBy=user_name&order=ASC', $page);
    }
}
