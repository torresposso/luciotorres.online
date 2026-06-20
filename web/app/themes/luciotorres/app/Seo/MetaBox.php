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
        echo $this->getHtml($post);
    }

    public function getHtml($post): string
    {
        $metaDesc = get_post_meta($post->ID, '_luciotorres_meta_desc', true);
        $ogTitle = get_post_meta($post->ID, '_luciotorres_og_title', true);
        $ogDesc = get_post_meta($post->ID, '_luciotorres_og_desc', true);
        $ogImage = get_post_meta($post->ID, '_luciotorres_og_image', true);
        $noindex = get_post_meta($post->ID, '_luciotorres_noindex', true);
        $canonical = get_post_meta($post->ID, '_luciotorres_canonical', true);

        return wp_nonce_field('luciotorres_seo_save', 'luciotorres_seo_nonce', true, false)
            . $this->buildFormHtml($metaDesc, $ogTitle, $ogDesc, $ogImage, $noindex, $canonical);
    }

    private function buildFormHtml(
        ?string $metaDesc,
        ?string $ogTitle,
        ?string $ogDesc,
        mixed $ogImage,
        ?string $noindex,
        ?string $canonical,
    ): string {
        $html = '<table class="form-table">';

        $html .= '<tr>';
        $html .= '<th scope="row"><label for="luciotorres_meta_desc">Meta Description</label></th>';
        $html .= '<td>';
        $html .= '<textarea id="luciotorres_meta_desc" name="_luciotorres_meta_desc" rows="3" class="large-text" maxlength="160">' . esc_textarea($metaDesc) . '</textarea>';
        $html .= '<p class="description">Maximum 160 characters.</p>';
        $html .= '</td></tr>';

        $html .= '<tr>';
        $html .= '<th scope="row"><label for="luciotorres_og_title">OG Title</label></th>';
        $html .= '<td>';
        $html .= '<input type="text" id="luciotorres_og_title" name="_luciotorres_og_title" value="' . esc_attr($ogTitle) . '" class="large-text">';
        $html .= '</td></tr>';

        $html .= '<tr>';
        $html .= '<th scope="row"><label for="luciotorres_og_desc">OG Description</label></th>';
        $html .= '<td>';
        $html .= '<textarea id="luciotorres_og_desc" name="_luciotorres_og_desc" rows="3" class="large-text">' . esc_textarea($ogDesc) . '</textarea>';
        $html .= '</td></tr>';

        $html .= '<tr>';
        $html .= '<th scope="row"><label for="luciotorres_og_image">OG Image</label></th>';
        $html .= '<td>';
        $html .= '<input type="text" id="luciotorres_og_image" name="_luciotorres_og_image" value="' . esc_attr($ogImage) . '" class="large-text">';
        $html .= '<button type="button" class="button" id="luciotorres_og_image_button">Select Image</button>';
        $html .= '<p class="description">Enter attachment ID or use the media button.</p>';
        $html .= '</td></tr>';

        $html .= '<tr>';
        $html .= '<th scope="row"><label for="luciotorres_noindex">Noindex</label></th>';
        $html .= '<td>';
        $html .= '<label>';
        $html .= '<input type="checkbox" id="luciotorres_noindex" name="_luciotorres_noindex" value="1" ' . checked($noindex, '1', false) . '>';
        $html .= ' Prevent search engines from indexing this page';
        $html .= '</label>';
        $html .= '</td></tr>';

        $html .= '<tr>';
        $html .= '<th scope="row"><label for="luciotorres_canonical">Canonical URL</label></th>';
        $html .= '<td>';
        $html .= '<input type="url" id="luciotorres_canonical" name="_luciotorres_canonical" value="' . esc_attr($canonical) . '" class="large-text">';
        $html .= '</td></tr>';

        $html .= '</table>';

        $html .= '<script>
        (function($) {
            $(\'#luciotorres_og_image_button\').on(\'click\', function(e) {
                e.preventDefault();
                var frame = wp.media({
                    title: \'Select OG Image\',
                    multiple: false,
                    library: { type: \'image\' }
                });
                frame.on(\'select\', function() {
                    var attachment = frame.state().get(\'selection\').first().toJSON();
                    $(\'#luciotorres_og_image\').val(attachment.id);
                });
                frame.open();
            });
        })(jQuery);
        </script>';

        return $html;
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
