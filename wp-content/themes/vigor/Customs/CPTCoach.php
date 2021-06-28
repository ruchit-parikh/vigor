<?php

namespace Customs;

class CPTCoach
{
    public function register()
    {
		add_action('init', array($this, 'regiser_coach_post'));
    }
	
	public function regiser_coach_post()
	{
		$labels = array(
			'name'                => _x('Coaches', 'Post Type General Name', 'vigor'),
			'singular_name'       => _x('Coach', 'Post Type Singular Name', 'vigor'),
			'menu_name'           => __('Coaches', 'vigor'),
			'all_items'           => __('All Coaches', 'vigor'),
			'view_item'           => __('View Coach', 'vigor'),
			'add_new_item'        => __('Add New Coach', 'vigor'),
			'add_new'             => __('Add New', 'vigor'),
			'edit_item'           => __('Edit Coach', 'vigor'),
			'update_item'         => __('Update Coach', 'vigor'),
			'search_items'        => __('Search Coach', 'vigor'),
			'not_found'           => __('Not Found', 'vigor'),
			'not_found_in_trash'  => __('Not found in Trash', 'vigor'),
		);
		
		$args = array(
			'label'               => __('coaches', 'vigor'),
			'description'         => __('Coaches', 'vigor'),
			'labels'              => $labels,
			'supports'            => array('title', 'editor', 'thumbnail'),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_icon'			  => 'dashicons-businessperson',
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'menu_position'       => 5,
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => false,
			'capability_type'     => 'post',
			'show_in_rest'        => true,
		);

		register_post_type('coach', $args);
	}
}