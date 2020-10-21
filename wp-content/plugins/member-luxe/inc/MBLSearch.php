<?php

class MBLSearch implements MBLPaginationInterface
{
    /**
     * @var MBLSearchPage[]
     */
    private $pages = array();
    /**
     * @var bool
     */
    private $paginate;
    private $perPage;

    private $totalCount;

    private $term;

    /**
     * MBLCategoryCollection constructor.
     * @param bool $paginate
     */
    public function __construct($paginate = true)
    {
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

        $fullUrl = $pageNumber > 1
            ? ($url . '/page/' . $pageNumber)
            : $url;

        return $fullUrl . $this->_getQueryString();
    }

    private function _getQueryString()
    {
        global $wp;

        return (get_option('permalink_structure') != '' ? '?s=' : '&s=') . wpm_array_get($wp->query_vars, 's');
    }

    public function getPrevPageLink()
    {
        return $this->getPageLink($this->getCurrentPage() - 1);
    }

    public function getNextPageLink()
    {
        return $this->getPageLink($this->getCurrentPage() + 1);
    }

    public function getBreadcrumbs()
    {
        return array(
            array(
                'name' => __('Результаты поиска', 'mbl'),
                'link' => (wpm_search_link() . $this->_getQueryString()),
                'icon' => 'search'
            )
        );
    }

    private function _initPages()
    {
        global $wpdb, $wp_query;

        $this->term = $wp_query->get('s');

        $args = array(
            'post_type' => 'wpm-page',
            'posts_per_page' => -1,
            'orderby' => 'menu_order post_date',
            'order' => 'ASC',
        );

        $isAdmin = wpm_is_admin();
        if (!$isAdmin) {
            $accessibleCategoryIds = $this->getAccessibleCategoryIds();
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'wpm-category',
                    'field' => 'term_id',
                    'terms' => $accessibleCategoryIds
                ),
            );
        }

        $term = $wpdb->esc_like($this->term);

        $wheres = array(
            ("(post_title LIKE '%{$term}%')"),
        );

        $query = "SELECT ID FROM {$wpdb->posts} WHERE " . implode(' OR ', $wheres);
        $ids = $wpdb->get_col($query);

        $descArgs = $args;
        $descArgs['fields'] = 'ids';
        $descArgs['meta_query'] = array(
            array(
                'key' => 'mbl_short_description',
                'value' => $this->term,
                'compare' => 'LIKE'
            )
        );

        $ids = array_merge($ids, get_posts($descArgs));

        if (count($ids)) {
            $args['post__in'] = $ids;

            if ($this->paginate) {
                $countArgs = $args;
                $countArgs['fields'] = 'ids';
                $postIds = get_posts($countArgs);
                $this->totalCount = count($postIds);

                $args['posts_per_page'] = $this->perPage;
                $args['offset'] = ($this->getCurrentPage() - 1) * $this->perPage;
            }

            $posts = get_posts($args);
        } else {
            $posts = array();
        }

        foreach ($posts as $post) {
            $terms = wp_get_post_terms($post->ID, 'wpm-category');

            if (!$isAdmin) {
                $category = null;

                foreach ($terms as $term) {
                    $category = new MBLCategory($term);
                    break;
                }
            } else {
                $term = wpm_array_get($terms, 0);
                $category = $term ? new MBLCategory($term) : null;
            }

            if ($category) {
                $category->setCurrentPost($post);
                $this->addPage(new MBLSearchPage($post, $category));
            }
        }
    }

    /**
     * @return mixed
     */
    public function getTerm()
    {
        return esc_html($this->term);
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

    /**
     * @return MBLSearchPage[]
     */
    public function getPages()
    {
        return $this->pages;
    }

    public function count()
    {
        return count($this->pages);
    }

    private function getAccessibleCategoryIds()
    {
        $exclude = wpm_get_excluded_categories();
        $args = array(
            'taxonomy' => 'wpm-category',
            'orderby' => 'name',
            'order' => 'ASC',
            'hide_empty' => 0,
            'hierarchical' => true,
            'current_category' => 0,
            'child_of' => 0,
            'exclude_tree' => $exclude,
            'exclude' => '',
            'fields' => 'ids',
            'parent' => 0,
        );

        return get_categories($args);
    }
}