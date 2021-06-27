<?php

namespace Customs;

use Carbon_Fields\Container;
use Carbon_Fields\Field;

class HomePageOptions
{
    public function register()
    {
        $pages = $this->get_mapped_pages();

        Container::make('post_meta', __('Page Options'))
            ->where('post_type', '=', 'page')
            ->where('post_id', '=', get_option('page_on_front'))
            ->add_fields(array(
                Field::make('separator', 'vg_home_banner_section', __('Banner Options')),
                Field::make('image', 'vg_home_banner_image', __('Banner Image'))->set_required(),
                Field::make('text', 'vg_home_banner_header', __('Header'))->set_required(),
                Field::make('text', 'vg_home_banner_subheader', __('Sub Header')),
                Field::make('text', 'vg_home_banner_cta_primary_text', __('Primary Button Text')),
                Field::make('select', 'vg_home_banner_cta_primary_link', __('Primary Button Link Page'))
                    ->set_options($pages),
                Field::make('text', 'vg_home_banner_cta_primary_link_url', __('Primary Button Link Url'))
                    ->set_conditional_logic(array(
                        array(
                            'field' => 'vg_home_banner_cta_primary_link',
                            'value' => '-1',
                        )
                    )),
                Field::make('text', 'vg_home_banner_cta_secondary_text', __('Secondary Button Text')),
                Field::make('select', 'vg_home_banner_cta_secondary_link', __('Secondary Button Link Page'))
                    ->set_options($pages),
                Field::make('text', 'vg_home_banner_cta_secondary_link_url', __('Secondary Button Link Url'))
                    ->set_conditional_logic(array(
                        array(
                            'field' => 'vg_home_banner_cta_secondary_link',
                            'value' => '-1',
                        )
                    )),

                Field::make('separator', 'vg_home_featured_section', __('Featured Descriptions')),
                Field::make('text', 'vg_home_featured_section_title', __('Title')),
                Field::make('rich_text', 'vg_home_featured_section_description', __('Description')),
            ));
    }

    /**
     * @return WP_Post[]|int[]|false
     */
    public function get_mapped_pages(): array
    {
        $pages = wp_list_pluck(get_pages(array(
            'post_status' => 'publish'
        )), 'post_title', 'ID');

        return $pages + array('-1' => __('Custom Link'));
    }
}