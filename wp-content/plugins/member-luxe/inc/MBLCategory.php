<?php

class MBLCategory implements MBLPaginationInterface
{
    /**
     * @var WP_Term
     */
    private $_wp_category;

    private $_meta;

    /**
     * @var MBLCategoryCollection
     */
    private $_children;

    private $_progress = array();
    private $_progressPassed = array();
    private $_progressNotPassed = array();
    private $_accessClass = array();
    private $_treeTermIds = array();

    private $_treePostIds;
    private $_treeCommentIds;
    private $_hasAccess;
    private $_isAutotraining;


    /**
     * @var MBLPageCollection
     */
    private $_mblPages;

    /**
     * @var bool
     */
    private $_paginateChildren;

    /**
     * @var AutoTrainingView
     */
    private $_autotrainingView;
    private $perPage;
    private $totalPages;
    private $totalCount;
    private $_displayMaterials;

    /**
     * @var WP_Post
     */
    private $_currentPost;


    /**
     * MBLCategory constructor.
     * @param WP_Term $wpCategory
     * @param bool $getChildren
     * @param bool $paginateChildren
     */
    public function __construct($wpCategory, $getChildren = false, $paginateChildren = false)
    {
        $this->_wp_category = $wpCategory;
        $this->_children = new MBLCategoryCollection($this->_wp_category->term_id, $getChildren, $paginateChildren);
        $this->_meta = get_option("taxonomy_term_" . $this->getTermId());
        $this->_paginateChildren = $paginateChildren;
        $this->perPage = intval(wpm_get_option('main.posts_per_page', -1));
        $this->getTotalPages();
    }

    /**
     * @param WP_Post $currentPost
     */
    public function setCurrentPost($currentPost)
    {
        $this->_currentPost = $currentPost;
    }

    /**
     * @param MBLCategory $mblCategory
     */
    public function addChild($mblCategory)
    {
        $this->_children->addCategory($mblCategory);
    }

    public function hasParent()
    {
        return (bool)$this->_wp_category->parent;
    }

    public function getParentId()
    {
        return $this->_wp_category->parent;
    }

    public function getTermId()
    {
        return $this->_wp_category->term_id;
    }

    public function displayMaterials()
    {
        if (!isset($this->_displayMaterials)) {
            $this->_displayMaterials = wpm_user_is_active() && !wpm_hide_materials($this->getTermId());
        }

        return $this->_displayMaterials;
    }

    public function getName()
    {
        return $this->_wp_category->name;
    }

    public function showTitleRow()
    {
        return wpm_get_design_option('term_header.hide') != 'on'
            || wpm_get_design_option('term_subheader.hide') != 'on'
            || ($this->hasDescription() && $this->showDescription());
    }

    public function hasDescription()
    {
        return $this->getDescription() && trim($this->getDescription()) != '';
    }

    public function getDescription()
    {
        return wpm_remove_protocol_from_text($this->_wp_category->description);
    }

    public function hasShortDescription()
    {
        return $this->getShortDescription() && trim($this->getShortDescription()) != '';
    }

    public function getShortDescription()
    {
        return wpm_remove_protocol_from_text(wpm_array_get($this->_meta, 'short_description'));
    }

    public function hasChildren()
    {
        return (bool)$this->_children->count();
    }

    public function hasBackgroundImage()
    {
        return (bool)$this->getBackgroundImage();
    }

    public function getBackgroundImage()
    {
        $image = $this->getTermMeta('bg_url');

        return $image ? $image :  wpm_get_option('folders.bg_url');
    }

    public function getProgress($userId = null)
    {
        if ($userId === null) {
            $userId = wpm_get_current_user('ID');
        }

        if ($userId === null) {
            return 0;
        }

        if (!isset($this->_progress[$userId])) {
            $meta = get_term_meta($this->getTermId(), 'wmp_term_progress', true);

            $this->_progress[$userId] = min(100, ceil(count(wpm_array_get($meta, $userId, array())) / $this->totalCount * 100));
        }

        return $this->_progress[$userId];
    }

    public function getProgressPassed($passed, $userId = null)
    {
        if ($userId === null) {
            $userId = wpm_get_current_user('ID');
        }

        if ($userId === null) {
            return 0;
        }

        $meta = get_term_meta($this->getTermId(), 'wmp_term_progress', true);

        $count = count(wpm_array_get($meta, $userId, array()));
        $value = $passed ? 0 : 1;

        return min(100, ceil(($count + $value) / $this->totalCount * 100));
    }

    public function getProgressNotPassed($passed, $userId = null)
    {
        if ($userId === null) {
            $userId = wpm_get_current_user('ID');
        }

        if ($userId === null) {
            return 0;
        }

        $meta = get_term_meta($this->getTermId(), 'wmp_term_progress', true);

        $count = count(wpm_array_get($meta, $userId, array()));
        $value = $passed ? -1 : 0;

        return min(100, ceil(($count + $value) / $this->totalCount * 100));
    }


    public function getAccessClass($userId = null)
    {
        if ($userId === null) {
            $userId = wpm_get_current_user('ID');
        }

        if (!$userId || !isset($this->_accessClass[$userId])) {
            $accessCnt = 0;
            $materialsCount = 0;
            if ($this->hasAccess() || wpm_get_design_option('page.show_all') == 'on') {
                foreach ($this->getTreePostIds() as $postId) {
                    ++$materialsCount;
                    $meta = get_post_meta($postId, 'wmp_material_access', true);

                    if (!$meta) {
                        $meta = array();
                    }

                    if ($userId && wpm_array_get($meta, $userId)) {
                        ++$accessCnt;
                    } elseif (MBLPage::checkAccess($userId, $postId)) {
                        ++$accessCnt;
                    }
                }

                if ($materialsCount == 0) {
                    $class = 'unlock';
                } elseif ($accessCnt == 0) {
                    $class = 'lock';
                } elseif ($accessCnt < count($this->getTreePostIds())) {
                    $class = 'unlock-alt';
                } else {
                    $class = 'unlock';
                }
            } else {
                $class = 'lock';
            }

            if (!$userId) {
                return $class;
            }

            $this->_accessClass[$userId] = $class;
        }

        return $this->_accessClass[$userId];
    }

    public function getTreePostIds()
    {
        if (!isset($this->_treePostIds)) {
            $termIds = $this->getTreeTermIds();
            $this->_treePostIds = get_posts(array(
                    'post_type' => 'wpm-page',
                    'fields' => 'ids',
                    'posts_per_page' => -1,
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'wpm-category',
                            'field' => 'term_id',
                            'terms' => implode(',', $termIds)
                        )
                    ),
                )
            );
        }

        return $this->_treePostIds;
    }

    public function getTreeTermIds()
    {
        if (!count($this->_treeTermIds)) {
            $termIds = array($this->getTermId());

            foreach ($this->getChildren() as $child) {
                $termIds = array_merge($termIds, $child->getTreeTermIds());
            }
            $this->_treeTermIds = $termIds;
        }

        return $this->_treeTermIds;
    }

    /**
     * @return MBLCategory[]
     */
    public function getChildren()
    {
        return $this->_children->getCategories();
    }

    /**
     * @return MBLCategoryCollection
     */
    public function getChildrenCollection()
    {
        return $this->_children;
    }

    public function getCommentsNumber()
    {
        if (!isset($this->_treeCommentIds)) {
            $postIds = $this->getTreePostIds();
            if(!empty($postIds)) {
                $this->_treeCommentIds = get_comments(array(
                    'post__in' => implode(',', $postIds),
//                    'hierarchical' => 'flat',
                    'fields' => 'ids',
                    'status' => 'approve'
                ));
            } else {
                $this->_treeCommentIds = array();
            }
        }

        return count($this->_treeCommentIds);
    }

    public function getViewsNumber()
    {
        $views = 0;

        foreach ($this->getTreePostIds() as $postId) {
            $views += intval(get_term_meta($postId, 'wpm_page_views', true));
        }

        return $views;
    }

    public function getLink()
    {
        return get_term_link($this->getTermId());
    }

    public function getBreadcrumbs()
    {
        $ancestors = get_ancestors($this->getTermId(), 'wpm-category');

        $breadcrumbs = array();

        foreach ($ancestors as $ancestor) {
            $term = new MBLCategory(get_term($ancestor));
            array_unshift($breadcrumbs, array(
                'name' => $term->getName(),
                'link' => $term->getLink(),
                'icon' => 'folder-open-o'
            ));
        }

        $breadcrumbs[] = array(
            'name' => $this->getName(),
            'link' => $this->getLink(),
            'icon' => 'folder-open-o'
        );

        return $breadcrumbs;
    }

    public function hasAccess()
    {
        if (!isset($this->_hasAccess)) {
            $this->_hasAccess = !in_array($this->getTermId(), wpm_get_excluded_categories());
        }

        return $this->_hasAccess || wpm_is_admin();
    }

    public function getPageCollection()
    {
        if (!isset($this->_mblPages)) {
            $this->_mblPages = new MBLPageCollection($this->getTermId());
        }

        return $this->_mblPages;
    }

    public function getAutotrainingView($paginate = true)
    {
        if (!isset($this->_autotrainingView)) {
            $this->_autotrainingView = new AutoTrainingView($this->getTermId(), $paginate);
        }

        return $this->_autotrainingView;
    }

    public function iterateComposer()
    {
        the_post();
        $this->getAutotrainingView()->iterate();
    }

    public function postIsHidden()
    {
        return !$this->getAutotrainingView()->showPost() && !$this->getAutotrainingView()->showAll();
    }

    /**
     * @param $id
     * @return MBLPage|null
     */
    public function getMBLPage($id)
    {
        return $this->getPageCollection()->getPage($id);
    }

    public function getCurrentMBLPage()
    {
        return $this->getMBLPage($this->_currentPost ? $this->_currentPost->ID : get_the_ID());
    }

    public function getPrevMBLPage()
    {
        return $this->getPageCollection()->getPreviousMBLPage($this->_currentPost ? $this->_currentPost->ID : get_the_ID());
    }

    public function getNextMBLPage()
    {
        return $this->getPageCollection()->getNextMBLPage($this->_currentPost ? $this->_currentPost->ID : get_the_ID());
    }

    public function isPageFirstPreview()
    {
        return $this->getAutotrainingView()->isFirstPreview;
    }

    public function isPageOnlyPreview()
    {
        return $this->getAutotrainingView()->onlyPreview || !$this->hasAccess();
    }

    public function getCurrentPageLink()
    {
        return wpm_material_link($this->getWpCategory(), $this->_currentPost ? $this->_currentPost : get_post());
    }

    public function getCurrentPage()
    {
        global $paged;

        return intval($paged) ?: 1;
    }

    public function getTotalPages()
    {
        if (!isset($this->totalCount)) {
            $this->initPages();
        }

        return $this->perPage > 0
            ? intval(ceil($this->totalCount / $this->perPage))
            : 1;
    }

    public function rewindToCurrent()
    {
        $this->getAutotrainingView(false);
        foreach ($this->getPageCollection()->getPages() as $mblPage) {
            $this->getAutotrainingView(false)->iterate($mblPage->getId());
            if (!$this->getAutotrainingView(false)->showPost() && !$this->getAutotrainingView(false)->showAll()) {
                continue;
            }
            $this->getAutotrainingView()->updateAccessMeta($mblPage->hasAccess());
            if ($this->getAutotrainingView(false)->postId == $this->_currentPost->ID) {
                return true;
            }
            $this->getAutotrainingView(false)->updatePostIterator();
        }

        return false;
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
        return get_pagenum_link($pageNumber);
    }

    public function getPrevPageLink()
    {
        return $this->getPageLink($this->getCurrentPage() - 1);
    }

    public function getNextPageLink()
    {
        return $this->getPageLink($this->getCurrentPage() + 1);
    }

    public function isAutotraining()
    {
        if (!isset($this->_isAutotraining)) {
            $this->_isAutotraining = wpm_is_autotraining($this->getTermId());
        }

        return $this->_isAutotraining;
    }

    public function isMaterialPassed()
    {
        return $this->getCurrentMBLPage()->isPassed();
    }

    public function isHomeworkDone()
    {
        return $this->isAutotraining() && $this->getCurrentMBLPage()->isHomeworkDone();
    }

    public function getHomeworkStatusClass()
    {
        if (!$this->isAutotraining()) {
            return '';
        }

        return $this->getCurrentMBLPage()->getHomeworkStatusClass();
    }

    public function getHomeworkStatusText()
    {
        return $this->getCurrentMBLPage()->getHomeworkStatusText();
    }

    /**
     * @return WP_Term
     */
    public function getWpCategory()
    {
        return $this->_wp_category;
    }

    private function initPages()
    {
        $this->totalCount = 0;
        $composer = new AutoTrainingView($this->getTermId(), false);
        foreach ($this->getPageCollection()->getPages() as $mblPage) {
            $composer->iterate($mblPage->getId());
            if (!$composer->showPost() && !$composer->showAll()) {
                continue;
            }
            $composer->updateAccessMeta($mblPage->hasAccess());
            $composer->updateProgressMeta($this->getTermId(), $mblPage->isPassed());
            ++$this->totalCount;
            $composer->updatePostIterator();
        }
    }

    /**
     * @param string $key
     * @param null $default
     * @return mixed
     */
    public function getTermMeta($key = null, $default = null)
    {
        return wpm_array_get($this->_meta, $key, $default);
    }

    public function hasLabel()
    {
        return $this->getTermMeta('sticker_show') == 'on';
    }

    public function getLabelBgColor()
    {
        return '#' . $this->getTermMeta('sticker_bg_color', 'cd814e');
    }

    public function getLabelTextColor()
    {
        return '#' . $this->getTermMeta('sticker_text_color', 'fff');
    }

    public function getLabelText()
    {
        return $this->getTermMeta('sticker_text');
    }

    public function showDescription()
    {
        $show = $this->getTermMeta('individual_description')
            ? $this->getTermMeta('show_description')
            : wpm_get_option('folders.show_description', 1);

        return $show;
    }

    public function expandDescription()
    {
        $expand = $this->getTermMeta('individual_description')
            ? $this->getTermMeta('description_expand')
            : wpm_get_option('folders.description_expand', 0);

        return $expand || $this->showDescriptionAlways();
    }

    public function showDescriptionAlways()
    {
        return $this->getTermMeta('individual_description') && $this->getTermMeta('show_description_always', 0);
    }

    public function showComments()
    {
        return $this->showIndicators('comments_show')
            && !wpm_option_is('main.comments_mode', 'cackle');
    }
    
    private function showIndicators($alias)
    {
        return $this->getTermMeta('individual_indicators')
            ? $this->getTermMeta($alias)
            : wpm_get_option('folders.' . $alias, 1);
    }

    public function showProgressOnFolder()
    {
        return $this->_showProgress()
            && is_user_logged_in()
            && (
                ($this->hasChildren() && wpm_get_option('progress.parent_folders', 1))
                || wpm_get_option('progress.folders', 1)
            );
    }

    public function showProgressOnStatusRow()
    {
        return $this->_showProgress()
            && is_user_logged_in()
            && wpm_get_option('progress.content', 1);
    }

    public function showProgressOnBreadcrumbs()
    {
        return $this->_showProgress()
            && (
                ($this->hasChildren() && wpm_get_option('progress.subfolders', 1))
                || wpm_get_option('progress.materials', 1)
            );
    }

    public function showViews()
    {
        return $this->showIndicators('views_show');
    }

    public function showAccess()
    {
        return $this->showIndicators('access_show');
    }

    public function showCurrentMaterialBottomIcons()
    {
        return ($this->isAutotraining() && $this->getCurrentMBLPage()->hasHomework() && $this->getCurrentMBLPage()->showHomeworkStatus())
            || $this->getCurrentMBLPage()->showComments()
            || $this->getCurrentMBLPage()->showDate()
            || $this->getCurrentMBLPage()->showViews();
    }

    private function _showProgress()
    {
        return $this->getTermMeta('individual_indicators')
            ? $this->getTermMeta('progress_show')
            : wpm_get_option('progress.show', 1);
    }
}