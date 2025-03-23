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

            .single_project_thumbnail_container>h3 {
                order: 3;
                font-size: 15px;
                font-family: "Inter", Sans-serif;
                color: #F5F5F5;
                padding: 5px 15px;
                font-weight: 500;;
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
        </style>
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