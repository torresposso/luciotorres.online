<?php

namespace App\Seo;

class MetaBox
{
    public function register(): void
    {
        $screens = ['post', 'page'];

        foreach ($screens as $screen) {
            add_meta_box(
                'luciotorres_seo',
                'Vox Populi SEO',
                function ($post) {
                    $this->render($post);
                },
                $screen,
                'normal',
                'high',
            );
        }
    }

    private function render($post): void
    {
        wp_nonce_field('luciotorres_seo_save', 'luciotorres_seo_nonce');

        $metaDesc = get_post_meta($post->ID, '_luciotorres_meta_desc', true);
        $ogTitle = get_post_meta($post->ID, '_luciotorres_og_title', true);
        $ogDesc = get_post_meta($post->ID, '_luciotorres_og_desc', true);
        $ogImage = get_post_meta($post->ID, '_luciotorres_og_image', true);
        $noindex = get_post_meta($post->ID, '_luciotorres_noindex', true);
        $canonical = get_post_meta($post->ID, '_luciotorres_canonical', true);
        ?>
        <table class="form-table">
            <tr>
                <th scope="row"><label for="luciotorres_meta_desc">Meta Description</label></th>
                <td>
                    <textarea id="luciotorres_meta_desc" name="_luciotorres_meta_desc" rows="3" class="large-text" maxlength="160"><?php echo esc_textarea($metaDesc); ?></textarea>
                    <p class="description">Maximum 160 characters.</p>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="luciotorres_og_title">OG Title</label></th>
                <td>
                    <input type="text" id="luciotorres_og_title" name="_luciotorres_og_title" value="<?php echo esc_attr($ogTitle); ?>" class="large-text">
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="luciotorres_og_desc">OG Description</label></th>
                <td>
                    <textarea id="luciotorres_og_desc" name="_luciotorres_og_desc" rows="3" class="large-text"><?php echo esc_textarea($ogDesc); ?></textarea>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="luciotorres_og_image">OG Image</label></th>
                <td>
                    <input type="text" id="luciotorres_og_image" name="_luciotorres_og_image" value="<?php echo esc_attr($ogImage); ?>" class="large-text">
                    <button type="button" class="button" id="luciotorres_og_image_button">Select Image</button>
                    <p class="description">Enter attachment ID or use the media button.</p>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="luciotorres_noindex">Noindex</label></th>
                <td>
                    <label>
                        <input type="checkbox" id="luciotorres_noindex" name="_luciotorres_noindex" value="1" <?php checked($noindex, '1'); ?>>
                        Prevent search engines from indexing this page
                    </label>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="luciotorres_canonical">Canonical URL</label></th>
                <td>
                    <input type="url" id="luciotorres_canonical" name="_luciotorres_canonical" value="<?php echo esc_attr($canonical); ?>" class="large-text">
                </td>
            </tr>
        </table>
        <script>
        (function($) {
            $('#luciotorres_og_image_button').on('click', function(e) {
                e.preventDefault();
                var frame = wp.media({
                    title: 'Select OG Image',
                    multiple: false,
                    library: { type: 'image' }
                });
                frame.on('select', function() {
                    var attachment = frame.state().get('selection').first().toJSON();
                    $('#luciotorres_og_image').val(attachment.id);
                });
                frame.open();
            });
        })(jQuery);
        </script>
        <?php
    }

    public function save(int $postId): void
    {
        if (! isset($_POST['luciotorres_seo_nonce'])
            || ! wp_verify_nonce($_POST['luciotorres_seo_nonce'], 'luciotorres_seo_save')) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (! current_user_can('edit_post', $postId)) {
            return;
        }

        $fields = [
            '_luciotorres_meta_desc',
            '_luciotorres_og_title',
            '_luciotorres_og_desc',
            '_luciotorres_og_image',
            '_luciotorres_noindex',
            '_luciotorres_canonical',
        ];

        foreach ($fields as $field) {
            if (isset($_POST[$field])) {
                $value = wp_unslash($_POST[$field]);

                if ($field === '_luciotorres_meta_desc' || $field === '_luciotorres_og_desc') {
                    $value = sanitize_textarea_field($value);
                } elseif ($field === '_luciotorres_canonical') {
                    if ($value !== '' && (! filter_var($value, FILTER_VALIDATE_URL)
                        || ! (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')))) {
                        delete_post_meta($postId, $field);
                        continue;
                    }
                    $value = esc_url_raw($value);
                } elseif ($field === '_luciotorres_og_image') {
                    $value = absint($value);
                } elseif ($field === '_luciotorres_noindex') {
                    $value = '1';
                } else {
                    $value = sanitize_text_field($value);
                }

                if ($field === '_luciotorres_meta_desc' && mb_strlen($value) > 160) {
                    $value = mb_substr($value, 0, 160);
                }

                update_post_meta($postId, $field, $value);
            } else {
                if ($field === '_luciotorres_noindex') {
                    delete_post_meta($postId, $field);
                }
            }
        }
    }
}
