<?php

class MBLPageCollection implements MBLPaginationInterface
{
    private $categoryId;

    /**
     * @var MBLPage[]
     */
    private $pages = array();
    /**
     * @var bool
     */
    private $paginate;
    private $perPage;

    private $totalCount;

    /**
     * MBLCategoryCollection constructor.
     * @param int $categoryId
     * @param bool $paginate
     */
    public function __construct($categoryId = 0, $paginate = false)
    {
        $this->categoryId = $categoryId;
        $this->paginate = $paginate;
        $this->perPage = intval(wpm_get_option('main.posts_per_page', -1));

        $this->_initPages();
    }

    /**
     * @return mixed
     */
    public function getTotalCount()
    {
        return $this->totalCount;
    }

    public function getCurrentPage()
    {
        global $wp;

        preg_match('/\&page_nb=(\d+)/', $wp->matched_query, $matches);

        return intval(wpm_array_get($matches, 1, max(1, intval(wpm_array_get($wp->query_vars, 'page', 1)))));
    }

    public function getTotalPages()
    {
        return $this->perPage > 0
            ? ceil($this->totalCount / $this->perPage)
            : 1;
    }

    public function hasToPaginate()
    {
        return $this->getTotalPages() > 1;
    }

    public function getFirstPageLink()
    {
        return $this->getPageLink(1);
    }

    public function getLastPageLink()
    {
        return $this->getPageLink($this->getTotalPages());
    }

    public function getPageLink($pageNumber)
    {
        global $wp;

        $currentUrl = home_url($wp->request);
        $pos = strpos($currentUrl, '/page');
        $url = $pos === false
            ? $currentUrl
            : substr($currentUrl, 0, $pos);

        return $pageNumber > 1
            ? ($url . '/page/' . $pageNumber)
            : $url;
    }

    public function getPrevPageLink()
    {
        return $this->getPageLink($this->getCurrentPage() - 1);
    }

    public function getNextPageLink()
    {
        return $this->getPageLink($this->getCurrentPage() + 1);
    }

    private function _initPages()
    {
        $args = array(
            'post_type' => 'wpm-page',
            //'fields' => 'ids',
            'posts_per_page' => -1,
            'tax_query' => array(
                array(
                    'taxonomy' => 'wpm-category',
                    'field' => 'term_id',
                    'terms' => $this->getCategoryId()
                )
            ),
            'orderby' => 'menu_order title',
            'order' => 'ASC'
        );

        if ($this->paginate) {
            $countArgs = $args;
            $countArgs['fields'] = 'ids';
            $postIds = get_posts($countArgs);
            $this->totalCount = count($postIds);


            $args['posts_per_page'] = $this->perPage;
            $args['offset'] = ($this->getCurrentPage() - 1) * $this->perPage;
        }

        $posts = get_posts($args);

        foreach ($posts as $post) {
            $this->addPage(new MBLPage($post));
        }
    }

    /**
     * @param MBLPage $mblPage
     */
    public function addPage($mblPage)
    {
        $this->pages[$mblPage->getId()] = $mblPage;
    }

    public function getPage($id)
    {
        return wpm_array_get($this->pages, $id);
    }

    public function getNextMBLPage($currentId)
    {
        $next = null;

        reset($this->pages);

        do {
            $curKey = key($this->pages);
            $res = next($this->pages);
        } while (($curKey != $currentId) && $res);

        if ($res) {
            $next = current($this->pages);
        }

        reset($this->pages);

        return $next;
    }

    public function getPreviousMBLPage($currentId)
    {
        end($this->pages);
        $prev = null;

        do {
            $curKey = key($this->pages);
            $res = prev($this->pages);
        } while (($curKey != $currentId) && $res);

        if ($res) {
            $prev = current($this->pages);
        }

        reset($this->pages);

        return $prev;
    }

    /**
     * @return MBLPage[]
     */
    public function getPages()
    {
        return $this->pages;
    }

    /**
     * @return int
     */
    public function getCategoryId()
    {
        return $this->categoryId;
    }

    public function count()
    {
        return count($this->pages);
    }
}