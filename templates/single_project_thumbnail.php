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
        <style>
            .single_project_thumbnail_container {
                width: 100%;
                padding: 16px;
                display: flex;
                flex-direction: column;
                position: relative;
            }

            .single_project_thumbnail_container>h3{
                order: 3;
            }

            .single_project_thumbnail_container>p{
                position: absolute;
                right: 25px;
                top: 25px;
                z-index: 20;
                background-color: white;
                padding: 5px 15px;
                border-radius: 10px;
                font-size: 14px;
                font-family: Inter, Arial, Helvetica, sans-serif;
            }

            .single_project_thumbnail_container>img{
                width: 100%;
                height: 300px;
                object-fit: cover;
                border-radius: 15px;
            }
        </style>
        <a href="<?= $url ?>" class="single_project_thumbnail_container">
            <h3><?= $name ?></h3>
            <img src="<?=$image?>" />
            <p><?= $service ?></p>
        </a>
        <?php
    }
}