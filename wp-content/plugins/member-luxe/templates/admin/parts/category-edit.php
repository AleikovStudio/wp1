<tr>
    <th>Автотренинг</th>
    <td>
        <label>
        <?php if($term_meta['category_type'] == 'on'){ ?>
            <input type="checkbox" name="term_meta[category_type]" checked="">
        <?php }else{ ?>
            <input type="checkbox" name="term_meta[category_type]">
        <?php } ?>
        Сделать автотренингом</label>
    </td>
</tr>

<tr>
    <th>Доступ</th>
    <td>
        <select name="term_meta[visibility_level_action]">
            <option value="hide" <?php echo ($term_meta['visibility_level_action'] == 'hide')? 'selected':''; ?>>Не отображать для cледующих уровней доступа:</option>
            <option value="show_only" <?php echo ($term_meta['visibility_level_action'] == 'show_only')? 'selected':''; ?>>Отображать только для следующих уровней доступа:</option>
        </select>
            <?php  wpm_hierarchical_category_tree(0, $term_meta); ?>
        <p>
            <label>
                <input type="checkbox"
                       name="term_meta[hide_for_not_registered]"
                    <?php echo $term_meta['hide_for_not_registered']=='on' ? 'checked' : ''; ?>>
                Не отображать для незарегистрированных пользователей.
            </label>
        </p>
    </td>
</tr>