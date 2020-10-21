<span class="homework-attachments">
    <?php foreach ($files as $file) : ?>
        <?php if (isset($file->thumbnailUrl)) : ?>
            <a href="<?php echo $file->url; ?>" title="<?php echo $file->name; ?>"
               rel="wpm_homework_file_<?php echo $item->id; ?>" class="fancybox">
                <img src="<?php echo $file->thumbnailUrl; ?>">
            </a>
        <?php else : ?>
            <a href="<?php echo $file->url; ?>" title="<?php echo $file->name; ?>"
               download target="_blank">
                <i class="fa fa-<?php echo $file->icon_class ?>-o"
                   aria-hidden="true"></i>
            </a>
        <?php endif; ?>
    <?php endforeach; ?>
</span>