<?php

class hc_single_project_thumbnail
{
    public function __construct(
        $url,
        $name,
        $service,
        $image
    ) {
        ?>
        <a href="<?= $url ?>" class="single_project_thumbnail_container">
            <h3><?= $name ?></h3>
            <img src="<?= $image ?>" />
            <div class="service">
                <span><?= $service ?></span>
            </div>
        </a>
        <?php
    }
}