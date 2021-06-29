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
            ));
    }
}