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
                Field::make('text', 'vg_home_featured_section_title', __('Title'))->set_required(),
                Field::make('rich_text', 'vg_home_featured_section_description', __('Description')),

                Field::make('separator', 'vg_home_coaches_section', __('Coaches Overview')),
                Field::make('text', 'vg_home_coaches_section_title', __('Title')),
                Field::make('rich_text', 'vg_home_coaches_section_subtitle', __('Tag Line')),
                Field::make('association', 'vg_home_coaches_section_coaches', __('Select Coaches to Show On Slider'))
                    ->set_types(array(
                        array(
                            'type'      => 'post',
                            'post_type' => 'coach',
                        ),
                    )),
                Field::make('text', 'vg_home_coaches_section_cta_text', __('Coache Overview Link Text')),
                Field::make('select', 'vg_home_coaches_section_cta_link', __('Coache Overview Link Page'))
                    ->set_options($pages),
                Field::make('text', 'vg_home_coaches_section_cta_link_url', __('Coache Overview Link Url'))
                    ->set_conditional_logic(array(
                        array(
                            'field' => 'vg_home_coaches_section_cta_link',
                            'value' => '-1',
                        )
                    )),

                Field::make('separator', 'vg_home_plans_section', __('Plans Overview')),
                Field::make('text', 'vg_home_plans_section_title', __('Title')),
                Field::make('complex', 'vg_home_plans_section_plans', __('Plans'))
                    ->add_fields(array(
                        Field::make('text', 'name', __('Plan Name'))->set_required(),
                        Field::make('complex', 'features', __('What it offers?'))
                            ->add_fields(array(
                                Field::make('text', 'title', __('Feature'))->set_required()
                            ))
                            ->set_min(1)
                            ->set_header_template('
                                <% if (title) { %>
                                    <%- title %>
                                <% } %>
                            '),
                        Field::make('text', 'btn_text', __('Price Button Text')),
                        Field::make('text', 'btn_link', __('Price Button Link')),
                    ))
                    ->set_min(3)
                    ->set_max(3)
                    ->set_header_template('
                        <% if (name) { %>
                            <%- name %>
                        <% } %>
                    '),
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