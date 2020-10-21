<?php /** @var MBLCategory $category */ ?>
<a href="<?php echo $category->getLink(); ?>"
   class="folder-wrap folder-with-<?php echo $category->hasChildren() ? 'subfolders' : 'files'; ?> folder-<?php echo $category->getAccessClass(); ?>">
    <?php if ($category->hasLabel()) : ?>
        <span class="label"
              style="background: <?php echo $category->getLabelBgColor(); ?>; color: <?php echo $category->getLabelTextColor(); ?>;"
        ><?php echo $category->getLabelText(); ?></span>
    <?php endif; ?>
    <div class="folder-content">
        <h1 class="title"><?php echo $category->getName(); ?></h1>
        <div class="bottom-icons">
            <?php if ($category->showComments() && $category->getCommentsNumber() && !wpm_hide_materials($category->getTermId())) : ?>
                <span class="folder-icon comments"><span
                            class="icon-comment-o"></span> <?php echo $category->getCommentsNumber(); ?></span>
            <?php endif; ?>
            <?php if ($category->showViews()) : ?>
                <span class="folder-icon views"><span
                            class="icon-eye"></span> <?php echo $category->getViewsNumber(); ?></span>
            <?php endif; ?>

            <?php if ($category->showAccess()) : ?>
                <span class="folder-icon status"><span
                            class="icon-<?php echo $category->getAccessClass(); ?>"></span></span>
            <?php endif; ?>
            <?php if ($category->showProgressOnFolder()) : ?>
                <div class="course-progress-wrap">
                    <div class="progress">
                        <div class="progress-bar progress-bar-<?php echo $category->getAccessClass(); ?>"
                             role="progressbar" title="<?php echo $category->getProgress(); ?>"
                             style="width: <?php echo $category->getProgress(); ?>%">
                            <span class="sr-only"><?php echo $category->getProgress(); ?>
                                % <?php _e('Пройдено уроков', 'mbl'); ?></span>
                        </div>
                    </div>
                    <div class="progress-count">
                        <?php echo $category->getProgress(); ?>%
                    </div>
                </div>
            <?php endif; ?>
            <span class="bottom-icons-clearfix"></span>
        </div>
    </div>
    <svg class="folder-<?php echo $category->hasChildren() ? 'sub-' : ''; ?>front"
         version="1.1" id="folder-<?php echo $category->getTermId(); ?>"
         xmlns="http://www.w3.org/2000/svg"
         xmlns:xlink="http://www.w3.org/1999/xlink"
         width="100%"
         viewBox="0 0 270 <?php echo $category->hasChildren() ? '229.37' : '204'; ?>"
         preserveAspectRatio="xMidYMid slice">
        <defs>
            <?php if ($category->hasBackgroundImage()) : ?>
                <pattern
                        id="image-front-<?php echo $category->getTermId(); ?>"
                        patternUnits="userSpaceOnUse"
                        width="270"
                        height="<?php echo $category->hasChildren() ? '229.37' : '204'; ?>"
                        x="0"
                        y="0">
                    <image xlink:href="<?php echo $category->getBackgroundImage(); ?>"
                           x="<?php echo $category->hasChildren() ? '-10%' : '0'; ?>"
                           y="<?php echo $category->hasChildren() ? '-10%' : '0'; ?>"
                           preserveAspectRatio="xMidYMid slice"
                           width="<?php echo $category->hasChildren() ? '120%' : '100%'; ?>"
                           height="<?php echo $category->hasChildren() ? '120%' : '100%'; ?>"/>
                </pattern>
            <?php endif; ?>
            <path id="shape-front-<?php echo $category->getTermId(); ?>"
                  d="<?php echo $category->hasChildren() ? 'M7.39,0H77.23c6.69,0,14.19,22,22.19,22H263.25A6.77,6.77,0,0,1,270,28.75V222.62a6.77,6.77,0,0,1-6.75,6.75H6.75A6.77,6.77,0,0,1,0,222.62V7.39A7.41,7.41,0,0,1,7.39,0Z' : 'M262.61,0H104.69C98,0,94,15,86,15H6.75A6.77,6.77,0,0,0,0,21.75v175.5A6.77,6.77,0,0,0,6.75,204h256.5a6.77,6.77,0,0,0,6.75-6.75V7.39A7.41,7.41,0,0,0,262.61,0Z'; ?>"
            />
        </defs>
        <?php if ($category->hasBackgroundImage()) : ?>
            <use xlink:href="#shape-front-<?php echo $category->getTermId(); ?>" fill="#adc8dd"></use>
            <use xlink:href="#shape-front-<?php echo $category->getTermId(); ?>"
                 fill="url(#image-front-<?php echo $category->getTermId(); ?>)"></use>
        <?php else : ?>
            <use xlink:href="#shape-front-<?php echo $category->getTermId(); ?>" fill="#adc8dd"></use>
        <?php endif; ?>
    </svg>
    <?php if (!$category->hasChildren()) : ?>
        <div class="files-group">
            <img class="file file-1" src="<?php echo plugins_url('/member-luxe/2_0/images/file.svg'); ?>" alt="">
            <img class="file file-2" src="<?php echo plugins_url('/member-luxe/2_0/images/file.svg'); ?>" alt="">
            <img class="file file-3" src="<?php echo plugins_url('/member-luxe/2_0/images/file.svg'); ?>" alt="">
            <img class="file file-4" src="<?php echo plugins_url('/member-luxe/2_0/images/file.svg'); ?>" alt="">
        </div>
    <?php endif; ?>
    <svg class="folder-<?php echo $category->hasChildren() ? 'sub' : 'back'; ?>"
         version="1.1"
         id="folder-back-<?php echo $category->getTermId(); ?>"
         xmlns="http://www.w3.org/2000/svg"
         xmlns:xlink="http://www.w3.org/1999/xlink"
         width="100%"
         viewBox="<?php echo $category->hasChildren() ? '0 0 253.833 234.537' : '0 0 270 239'; ?>"
         preserveAspectRatio="<?php echo $category->hasChildren() ? 'xMaxYMax' : ''; ?>">
        <defs>
            <pattern id="image-back-<?php echo $category->getTermId(); ?>"
                     patternUnits="userSpaceOnUse"
                     width="<?php echo $category->hasChildren() ? '253.833' : '270'; ?>"
                     height="<?php echo $category->hasChildren() ? '234.537' : '239'; ?>"
                     x="0"
                     y="0">
                <?php if ($category->hasBackgroundImage()) : ?>
                    <image
                            xlink:href="<?php echo $category->getBackgroundImage(); ?>"
                            opacity="<?php echo $category->hasChildren() ? '.5' : '1'; ?>"
                            x="<?php echo $category->hasChildren() ? '-15%' : '0%'; ?>"
                            y="<?php echo $category->hasChildren() ? '-15%' : '0%'; ?>"
                            width="<?php echo $category->hasChildren() ? '130%' : '100%'; ?>"
                            preserveAspectRatio="xMidYMin slice"
                            height="<?php echo $category->hasChildren() ? '130%' : '100%'; ?>"/>
                <?php else : ?>
                    <rect fill="#adc8dd"
                          opacity="<?php echo $category->hasChildren() ? '.5' : '.3'; ?>"
                          x="<?php echo $category->hasChildren() ? '-15%' : '-10%'; ?>"
                          y="<?php echo $category->hasChildren() ? '-15%' : '-10%'; ?>"
                          width="<?php echo $category->hasChildren() ? '130%' : '120%'; ?>"
                          height="<?php echo $category->hasChildren() ? '130%' : '120%'; ?>"/>
                <?php endif; ?>
            </pattern>

            <path id="shape-back-<?php echo $category->getTermId(); ?>"
                  d="<?php echo $category->hasChildren() ? 'M7.39,0H77.23c6.69,0,14.19,21,22.19,21H247.08a6.77,6.77,0,0,1,6.75,6.75v200a6.77,6.77,0,0,1-6.75,6.75H6.75A6.77,6.77,0,0,1,0,227.79V7.39A7.41,7.41,0,0,1,7.39,0Z' : 'M7.39,0H77.23c6.69,0,14.19,22,22.19,22H263.25A6.77,6.77,0,0,1,270,28.75V232.22a6.77,6.77,0,0,1-6.75,6.75H6.75A6.77,6.77,0,0,1,0,232.22V7.39A7.41,7.41,0,0,1,7.39,0Z'; ?>"
            />
        </defs>
        <use xlink:href="#shape-back-<?php echo $category->getTermId(); ?>" fill="white"></use>
        <?php if ($category->hasBackgroundImage()) : ?>
            <use xlink:href="#shape-back-<?php echo $category->getTermId(); ?>" fill="rgba(173, 200, 221, 0.3)"></use>
        <?php endif; ?>
        <use <?php echo $category->hasChildren() ? '' : 'x="0" y="0"'; ?>
                xlink:href="#shape-back-<?php echo $category->getTermId(); ?>"
                fill="url(#image-back-<?php echo $category->getTermId(); ?>)"></use>
        <?php if ($category->hasBackgroundImage()) : ?>
            <use xlink:href="#shape-back-<?php echo $category->getTermId(); ?>" fill="rgba(255, 255, 255, 0.5)"></use>
        <?php endif; ?>
    </svg>
    <?php if ($category->hasChildren()) : ?>
        <svg class="folder-sub-back"
             version="1.1"
             id="folder-sub-back-<?php echo $category->getTermId(); ?>"
             xmlns="http://www.w3.org/2000/svg"
             xmlns:xlink="http://www.w3.org/1999/xlink"
             width="100%"
             viewBox="0 0 239.833 238.537"
             preserveAspectRatio="xMaxYMax">
            <defs>
                <?php if ($category->hasBackgroundImage()) : ?>
                    <pattern id="image-sub-back-<?php echo $category->getTermId(); ?>"
                             patternUnits="userSpaceOnUse"
                             width="239.833"
                             height="238.537"
                             x="0"
                             y="0">
                        <image xlink:href="<?php echo $category->getBackgroundImage(); ?>" x="-20%" y="-20%"
                               preserveAspectRatio="xMidYMin slice"
                               width="140%" height="140%"/>
                    </pattern>
                <?php endif; ?>

                <path id="shape-sub-back-<?php echo $category->getTermId(); ?>"
                      d="M7.39,0H77.23c6.69,0,14.19,21,22.19,21H233.08a6.77,6.77,0,0,1,6.75,6.75v204a6.77,6.77,0,0,1-6.75,6.75H6.75A6.77,6.77,0,0,1,0,231.79V7.39A7.41,7.41,0,0,1,7.39,0Z"/>
            </defs>
            <?php if ($category->hasBackgroundImage()) : ?>
                <use xlink:href="#shape-sub-back-<?php echo $category->getTermId(); ?>" fill="#adc8dd"></use>
                <use xlink:href="#shape-sub-back-<?php echo $category->getTermId(); ?>"
                     fill="url(#image-sub-back-<?php echo $category->getTermId(); ?>)"></use>
            <?php else : ?>
                <use xlink:href="#shape-sub-back-<?php echo $category->getTermId(); ?>" fill="#adc8dd" x="0"
                     y="0"></use>
            <?php endif; ?>
        </svg>
    <?php endif; ?>
</a>
