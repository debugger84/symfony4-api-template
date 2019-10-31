<?php


namespace App\Infra\Response;


use League\Fractal\Pagination\PaginatorInterface;

class FractalPaginator implements PaginatorInterface
{
    /**
     * @var int
     */
    private $page;

    /**
     * @var int
     */
    private $countPerPage;

    /**
     * @var int
     */
    private $wholeCount;

    /**
     * FractalPaginator constructor.
     * @param int $page
     * @param int $countPerPage
     * @param int $wholeCount
     */
    public function __construct(int $page, int $countPerPage, int $wholeCount)
    {
        $this->page = $page;
        $this->countPerPage = $countPerPage;
        $this->wholeCount = $wholeCount;
    }


    /**
     * Get the current page.
     * @return int
     */
    public function getCurrentPage()
    {
        return $this->page;
    }

    /**
     * Get the last page.
     * @return int
     */
    public function getLastPage()
    {
        return (int) ceil($this->wholeCount / $this->countPerPage);
    }

    /**
     * Get the total.
     * @return int
     */
    public function getTotal()
    {
        return $this->wholeCount;
    }

    /**
     * Get the count.
     * @return int
     */
    public function getCount()
    {
        return $this->countPerPage;
    }

    /**
     * Get the number per page.
     * @return int
     */
    public function getPerPage()
    {
        return $this->countPerPage;
    }

    /**
     * Get the url for the given page.
     * @param int $page
     * @return string
     */
    public function getUrl($page)
    {
        return (string) $page;
    }
}
