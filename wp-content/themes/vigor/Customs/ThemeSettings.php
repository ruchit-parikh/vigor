<?php

namespace Customs;

use Carbon_Fields\Container;
use Carbon_Fields\Field;

class ThemeSettings
{
    public function register()
    {
        Container::make('theme_options', __('Theme Options'))
            ->add_tab(__( 'Logo Options' ), array(
                Field::make('image', 'vg_logo_light', __('Light Logo')),
                Field::make('image', 'vg_logo_dark', __('Dark Logo')),
                Field::make('image', 'vg_logo_special', __('Special Logo')),
            ))
            ->add_tab(__('Locations'), array(
                Field::make('text', 'map_title', __('Map Title')),
                Field::make('complex', 'vg_office_locations', __('Office Locations'))
                    ->add_fields(array(
                        Field::make('text', 'name', __('Office Name'))->set_required(),
                        Field::make('rich_text', 'address', __('Address'))->set_required(),
                        Field::make('complex', 'times', __('Office Opening-Closing Times'))
                            ->add_fields(array(
                                Field::make('text', 'day', __('Day'))->set_required(),
                                Field::make('text', 'time', __('Time'))->set_required(),
                            ))
                            ->set_header_template('
                                <% if (day) { %>
                                    <%- day %>
                                <% } %>
                            '),
                        Field::make('media_gallery', 'images', __('Images')),
                        Field::make('map', 'location', __('Locations'))
                            ->set_help_text('Drag and drop the pin on the map to select location')
                    ))
                    ->set_header_template('
                        <% if (name) { %>
                            <%- name %>
                        <% } %>
                    ')
            ))
            ->add_tab(__('Google Map Settings'), array(
                Field::make('text', 'vg_google_map_api_key', __('API Key')),
            ));
    }
}