<?php
/*
Plugin Name: HC Projects
Description: Adding Project Single Type
Version: 1.0.0
Author: Harun Catic
*/

// Activation
register_activation_hook(__FILE__, 'hc_projects_type_activate');
function hc_projects_type_activate()
{
}

// Deactivation
register_deactivation_hook(__FILE__, 'hc_projects_type_deactivate');
function hc_projects_type_deactivate()
{
}

// Uninstall
register_uninstall_hook(__FILE__, 'hc_projects_type_uninstall');
function hc_projects_type_uninstall()
{
}

//Programming logic
if (!class_exists('hc_projects_type_plugin')) {
    class hc_projects_type_plugin
    {
        private $post_type = "hc_projects_type";

        private function register_post_type()
        {
            register_post_type(
                $this->post_type,
                [
                    "label"         => __("Projects"),
                    "lables"        => [],
                    "description"   => "Projects Post Type",
                    "public"        => true,
                    "supports"      => ["title", "editor", "thumbnail",],
                    "has_archive"   => true,
                    "rewrite" => [
                        "slug" => "project"
                    ]
                ]
            );
        }

        //Construct
        public function __construct()
        {
            add_action( 'init', function(){
                $this->register_post_type();
            } );
        }
    }

    new hc_projects_type_plugin();
}