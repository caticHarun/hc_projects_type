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

        //Construct
        private function __construct()
        {
        }
    }

    new hc_projects_type_plugin();
}