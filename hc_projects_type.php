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
        public $gallery_field_id;

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
                        "slug" => "projects"
                    ]
                ]
            );
        }
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

            add_meta_box(
                $this->gallery_field_id,
                __("Photos"),
                [$this, "render_gallery_field"],
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
        public function render_gallery_field($post)
        {
            $gallery_images = get_post_meta($post->ID, $this->gallery_field_id, true);
            $gallery_images = is_array($gallery_images) ? $gallery_images : [];

            wp_nonce_field('save_gallery_meta', 'gallery_meta_nonce');

            echo '<div id="gallery-container">';
            echo '<ul id="gallery-preview" style="display: flex; flex-wrap: wrap; gap: 10px;">';
            foreach ($gallery_images as $image_id) {
                echo '<li data-id="' . esc_attr($image_id) . '" style="list-style: none; position: relative;">';
                echo '<img src="' . esc_url(wp_get_attachment_thumb_url($image_id)) . '" style="width:100px; height:auto; border:1px solid #ddd;">';
                echo '<button class="remove-gallery-image" style="position:absolute;top:0;right:0;background:red;color:#fff;border:none;padding:3px;cursor:pointer;">X</button>';
                echo '</li>';
            }
            echo '</ul>';
            echo '</div>';

            echo '<button type="button" class="button button-primary" id="add-gallery-images">' . __('Add Images', 'textdomain') . '</button>';
            echo '<input type="hidden" id="gallery-images" name="gallery_images" value="' . esc_attr(implode(',', $gallery_images)) . '">';
            ?>
            <script>
                document.addEventListener("DOMContentLoaded", function () {
                    let mediaUploader;
                    const addGalleryButton = document.getElementById("add-gallery-images");
                    const galleryInput = document.getElementById("gallery-images");
                    const galleryPreview = document.getElementById("gallery-preview");

                    if (!addGalleryButton || !galleryInput || !galleryPreview) return;

                    // Open Media Uploader
                    addGalleryButton.addEventListener("click", function (e) {
                        e.preventDefault();

                        if (mediaUploader) {
                            mediaUploader.open();
                            return;
                        }

                        mediaUploader = wp.media({
                            title: "Select Images",
                            button: { text: "Add to Gallery" },
                            multiple: true
                        });

                        mediaUploader.on("open", function () {
                            const galleryInput = document.getElementById("gallery-images");
                            const selection = mediaUploader.state().get("selection");
                            let existingImageIDs = galleryInput.value ? galleryInput.value.split(",") : [];

                            existingImageIDs.forEach(function (id) {
                                let attachment = wp.media.attachment(id);
                                attachment.fetch(); // Fetch attachment data
                                selection.add(attachment);
                            });

                            // ✅ Force multi-select behavior by overriding click action
                            let frame = mediaUploader;
                            let library = frame.state().get("library");

                            library.on("reset", function () {
                                console.log('haarun'); //HC_REMOVE
                                frame.content.get().$('.attachment').each(function () {
                                    console.log('haarun'); //HC_REMOVE
                                    let attachment = $(this);
                                    attachment.off("click").on("click", function (e) {
                                        e.preventDefault(); // Prevent default deselect behavior
                                        let attachmentID = attachment.data("id");
                                        let selection = frame.state().get("selection");

                                        let model = library.get(attachmentID);
                                        if (!selection.has(model)) {
                                            selection.add(model);
                                        }
                                    });
                                });
                            });
                        })

                        mediaUploader.on("select", function () {
                            const selection = mediaUploader.state().get("selection");
                            console.log('selection', selection); //HC_REMOVE
                            let imageIDs = [];
                            galleryPreview.innerHTML = ""; // Clear existing previews

                            selection.forEach(function (attachment) {
                                const image = attachment.toJSON();
                                imageIDs.push(image.id);

                                // Create image preview
                                const listItem = document.createElement("li");
                                listItem.setAttribute("data-id", image.id);
                                listItem.style = "list-style: none; position: relative;";

                                const imgElement = document.createElement("img");
                                imgElement.src = image.sizes.thumbnail.url;
                                imgElement.style = "width:100px; height:auto; border:1px solid #ddd;";

                                const removeButton = document.createElement("button");
                                removeButton.textContent = "X";
                                removeButton.style = "position:absolute;top:0;right:0;background:red;color:#fff;border:none;padding:3px;cursor:pointer;";
                                removeButton.addEventListener("click", function () {
                                    listItem.remove();
                                    updateImageIDs();
                                });

                                listItem.appendChild(imgElement);
                                listItem.appendChild(removeButton);
                                galleryPreview.appendChild(listItem);
                            });

                            galleryInput.value = imageIDs.join(",");
                        });

                        mediaUploader.open();
                    });

                    // Update the hidden input field when images are removed
                    function updateImageIDs() {
                        let imageIDs = [];
                        document.querySelectorAll("#gallery-preview li").forEach(function (item) {
                            imageIDs.push(item.getAttribute("data-id"));
                        });
                        galleryInput.value = imageIDs.join(",");
                    }

                    console.log('harun'); //HC_REMOVE

                    // Enable drag-and-drop sorting
                    new Sortable(galleryPreview, {
                        animation: 150,
                        onEnd: function () {
                            updateImageIDs();
                        }
                    });
                });
            </script>
            <?php
        }
        public function save_gallery_field($post_id)
        {
            if (!isset($_POST['gallery_meta_nonce']) || !wp_verify_nonce($_POST['gallery_meta_nonce'], 'save_gallery_meta')) {
                return;
            }
            if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
                return;
            }
            if (!current_user_can('edit_post', $post_id)) {
                return;
            }

            if (isset($_POST['gallery_images'])) {
                $gallery_images = array_filter(explode(',', sanitize_text_field($_POST['gallery_images'])));
                update_post_meta($post_id, $this->gallery_field_id, $gallery_images);
            }
        }
        public function enqueue_wp_media_uploader($hook)
        {
            if ('post.php' !== $hook && 'post-new.php' !== $hook) {
                return;
            }

            wp_enqueue_media();
            wp_enqueue_script('sortablejs', 'https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.2/Sortable.min.js', [], null, true);
        }

        //Disabling Indexing
        public function disable_projects_indexing()
        {
            if (is_post_type_archive($this->post_type) || is_singular($this->post_type)) {
                echo '<meta name="robots" content="noindex, nofollow">' . "\n";
            }
        }


        //Construct
        public function __construct()
        {
            //Initialization
            $this->post_type = "hc_projects_type";
            $this->service_field_id = $this->post_type . "_service_field";
            $this->gallery_field_id = $this->post_type . "_gallery_field";

            //Hooks
            add_action('wp_head', [$this, 'disable_projects_indexing']);
            add_action('init', [$this, "register_post_type"]);
            add_action('add_meta_boxes', [$this, 'add_custom_fields']);
            add_action('save_post', [$this, 'save_service_field']);
            add_action('save_post', [$this, 'save_gallery_field']);
            add_action('admin_enqueue_scripts', [$this, "enqueue_wp_media_uploader"]);
        }
    }

    new hc_projects_type_plugin();
}