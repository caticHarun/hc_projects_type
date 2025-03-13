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
        //Registering a custom post type
        public $post_type;
        public $service_field_id;

        public function register_post_type()
        {
            register_post_type(
                $this->post_type,
                [
                    "label" => __("Projects"),
                    "lables" => [],
                    "description" => "Projects Post Type",
                    "public" => true,
                    "supports" => ["title", "editor", "thumbnail",],
                    "has_archive" => true,
                    "rewrite" => [
                        "slug" => "project"
                    ]
                ]
            );
        }
        //Adding Custom Fields
        public function add_custom_fields()
        {
            add_meta_box(
                $this->service_field_id,
                __('Service'),
                [$this, "render_service_field"],
                $this->post_type,
                'normal',
                'default'
            );
        }
        public function render_service_field($post)
        {
            // Get the current value of the field
            $service = get_post_meta($post->ID, $this->service_field_id, true);

            // Add a nonce field for security
            wp_nonce_field('save_service_meta', 'service_meta_nonce');

            // Display the input field
            ?>
            <input type="text" id="project_service" name="project_service" value="<?php echo esc_attr($service); ?>"
                style="width:100%;" />
            <?php
        }
        public function save_service_field($post_id)
        {
            // Check if nonce is valid
            if (!isset($_POST['service_meta_nonce']) || !wp_verify_nonce($_POST['service_meta_nonce'], 'save_service_meta')) {
                return;
            }

            // Prevent saving on autosave
            if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
                return;
            }

            // Check user permission
            if (!current_user_can('edit_post', $post_id)) {
                return;
            }

            // Save the field value
            if (isset($_POST['project_service'])) {
                update_post_meta($post_id, $this->service_field_id, sanitize_text_field($_POST['project_service']));
            }
        }


        //Construct
        public function __construct()
        {
            //Initialization
            $this->post_type = "hc_projects_type";
            $this->service_field_id = $this->post_type . "_service_field";

            //Hooks
            add_action('init', [$this, "register_post_type"]);
            add_action('add_meta_boxes', [$this, 'add_custom_fields']);
            add_action('save_post', [$this, 'save_service_field']);
        }
    }

    new hc_projects_type_plugin();
}