<?php /** @var MBLPage $mblPage */ ?>
<div class="content-wrap">
    <div class="lesson-files-list">
        <?php foreach ($mblPage->getAttachments() as $attachment) : ?>
            <?php if (isset($attachment->thumbnailUrl)) : ?>
                <a href="<?php echo $attachment->url; ?>"
                   title="<?php echo $attachment->name; ?>"
                   rel="wpm_homework_file_<?php echo $attachment->id; ?>"
                   class="fancybox homework-attachment"
                   ><img src="<?php echo wpm_remove_protocol($attachment->thumbnailUrl); ?>"></a>
            <?php else : ?>
                <a href="<?php echo wpm_remove_protocol($attachment->url); ?>"
                   title="<?php echo $attachment->name; ?>"
                   class="homework-attachment"
                   download
                   target="_blank"
                ><span class="icon-<?php echo $attachment->icon_class; ?>-o" aria-hidden="true"></span></a>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</div>