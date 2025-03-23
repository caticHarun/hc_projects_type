<?php
get_header();

// REQUIRE
// require plugin_dir_path(__FILE__) . '/templates/sliders/firstSlider/firstSlider.min.php'; //HC_UPDATE uncomment
require plugin_dir_path(__FILE__) . './slider.php';
// require plugin_dir_path(__FILE__) . '/templates/single_project_thumbnail.min.php'; //HC_UPDATE uncomment
require plugin_dir_path(__FILE__) . './single_project_thumbnail.php';
?>

<style>
    .single_project_thumbnail_container {
        width: 100%;
        padding: 16px;
        display: flex;
        flex-direction: column;
        position: relative;
    }

    .single_project_thumbnail_container>h3 {
        order: 3;
        font-size: 15px;
        font-family: "Inter", Sans-serif;
        color: #1B1B1B;
        padding: 5px 15px;
        font-weight: 500;
    }

    .single_project_thumbnail_container>.service {
        position: absolute;
        right: 25px;
        top: 25px;
        z-index: 20;
        background-color: white;
        padding: 5px 15px;
        border-radius: 10px;
        font-size: 14px;
        font-family: "Inter", Sans-serif;
        color: #1B1B1B;
        font-weight: 500;
        padding-left: 30px;
    }

    .single_project_thumbnail_container>.service>span {
        list-style: disc outside none;
        display: list-item;
    }

    .single_project_thumbnail_container>img {
        width: 100%;
        height: 300px;
        object-fit: cover;
        border-radius: 15px;
    }

    .hc_projects_slider_container {
        width: 100%;
        max-width: 1400px;
        margin: 0 auto;
    }

    .hc_projects_slider_container>div {
        display: flex;
        flex-wrap: wrap;
    }

    .hc_projects_slider_container .single {
        width: calc(1/3 * 100%);
    }

    .pagination {
        max-width: 200px;
        margin: 0 auto;
        display: flex;
        justify-content: center;
        gap: 10px;
    }

    .pagination a {
        width: 100%;
        text-align: center;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
        padding: 10px;
        background-color: #1B1B1B;
        border-radius: 5px;
        color: #F5F5F5;
        font-weight: 500;
        transition-property: all;
        transition-duration: 150ms;
        transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    }

    .pagination a:hover {
        background-color: #2B2B2B;
    }
</style>

<?php if (have_posts()): ?>
    <div class="hc_projects_slider_container">
        <div>
            <?php while (have_posts()):
                the_post(); ?>
                <divm class="single">
                    <?php
                    $post = get_post();
                    $service = get_post_meta($post->ID, hc_projects_type_plugin::$service_field_id, true);
                    $url = get_the_post_thumbnail_url($post->ID);
                    new hc_single_project_thumbnail(
                        $post->guid,
                        $post->post_title,
                        $service,
                        get_the_post_thumbnail_url($post->ID)
                    );
                    ?>
                </divm>
            <?php endwhile; ?>
        </div>
    </div>
<?php else: ?>
    <p>No projects found.</p>
<?php endif; ?>

<div class="pagination">
    <?php previous_posts_link(__("Previous")); ?>
    <?php next_posts_link(__("Next")); ?>
</div>

<?php get_footer(); ?>