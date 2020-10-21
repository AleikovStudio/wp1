<?php

class MBLCategoryCollection implements MBLPaginationInterface
{
    private $parentCategoryId;

    /**
     * @var MBLCategory[]
     */
    private $categories = array();
    /**
     * @var bool
     */
    private $paginate;
    private $perPage;

    private $totalCount;
    /**
     * @var null
     */
    private $levelId;

    /**
     * MBLCategoryCollection constructor.
     * @param int $parentCategoryId
     * @param bool $init
     * @param bool $paginate
     * @param null $levelId
     */
    public function __construct($parentCategoryId = 0, $init = true, $paginate = false, $levelId = null)
    {
        $this->parentCategoryId = $parentCategoryId;
        $this->paginate = $paginate;
        $this->perPage = intval(wpm_get_option('main.terms_per_page', 12));
        $this->levelId = $levelId;

        if ($init) {
            $this->_initCategories();
        }
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

    public function getIdsByLevel()
    {
        $postArgs = array(
            'fields' => 'ids',
            'post_type' => 'wpm-page',
            'posts_per_page' => -1,
            'tax_query' => array(
                array(
                    'taxonomy' => 'wpm-levels',
                    'field' => 'term_id',
                    'terms' => $this->levelId,
                    'include_children' => false
                )
            )
        );

        $postIds = get_posts($postArgs);
        $categoryIds = array();

        foreach ($postIds as $postId) {
            $catList = wp_get_post_terms($postId, 'wpm-category', array("fields" => "ids"));

            if(is_array($catList)) {
                $categoryIds = array_unique(array_merge($categoryIds, $catList));
            }
        }

        return $categoryIds;
    }

    private function _initCategories()
    {
        $exclude = wpm_get_excluded_categories($this->levelId);
        $args = array(
            'taxonomy' => 'wpm-category',
            'orderby' => 'name',
            'order' => 'ASC',
            'hide_empty' => 0,
            'hierarchical' => true,
            'current_category' => $this->parentCategoryId,
            'child_of' => $this->parentCategoryId,
            'exclude_tree' => $exclude,
            'exclude' => ''
        );

        if ($this->paginate) {
            $countArgs = $args;
            $countArgs['fields'] = 'ids';
            $countArgs['hierarchical'] = false;
            $countArgs['parent'] = $this->parentCategoryId;
            $categoryIds = get_categories($countArgs);
            $this->totalCount = count($categoryIds);

            $currentPageIds = $this->perPage > 0
                ? array_slice($categoryIds, ($this->getCurrentPage() - 1) * $this->perPage, $this->perPage)
                : $categoryIds;
            $args['get'] = 'all';
            $args['exclude_tree'] = array_merge($args['exclude_tree'], array_diff($categoryIds, $currentPageIds));
        } elseif ($this->levelId) {
            $countArgs = $args;
            $countArgs['fields'] = 'ids';
            $countArgs['hierarchical'] = false;
            $countArgs['parent'] = $this->parentCategoryId;
            $categoryIds = get_categories($countArgs);
            $currentPageIds = $this->getIdsByLevel();
            $args['get'] = 'all';
            $args['exclude_tree'] = array_merge($args['exclude_tree'], array_diff($categoryIds, $currentPageIds));
        }

        $categories = get_categories($args);

        /** @var MBLCategory[] $categoriesList */
        $categoriesList = array();
        $children = array();

        foreach ($categories as $category) {
            $mblCategory = new MBLCategory($category);
            $categoriesList[$category->term_id] = $mblCategory;
            if ($category->parent) {
                if (!array_key_exists($category->parent, $children)) {
                    $children[$category->parent] = array();
                }

                $children[$category->parent][] = $mblCategory;
            }
        }

        foreach ($children as $parentId => $_children) {
            foreach ($_children as $child) {
                /** @var MBLCategory $child */
                if (array_key_exists($child->getParentId(), $categoriesList)) {
                    $categoriesList[$child->getParentId()]->addChild($categoriesList[$child->getTermId()]);
                }
            }
        }

        foreach ($categoriesList as $category) {
            if ($category->getParentId() == $this->parentCategoryId) {
                $this->categories[] = $category;
            }
        }
    }

    /**
     * @param MBLCategory $mblCategory
     */
    public function addCategory($mblCategory)
    {
        $this->categories[] = $mblCategory;
    }

    /**
     * @return MBLCategory[]
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * @return int
     */
    public function getParentCategoryId()
    {
        return $this->parentCategoryId;
    }

    public function count()
    {
        return count($this->categories);
    }
}