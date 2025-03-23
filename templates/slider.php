<?php

class HC_HTML_slider_template
{
    public $id;
    public $slider_id;
    public $slide_id;

    public function singleItem($i)
    {
        ?>
        <div class="<?= $this->slide_id ?> min-w-1/3 max-w-1/3 w-1/3">
            <?= $i ?>
        </div>
        <?php
    }

    /**
     * Summary of __construct
     * @param mixed $id = string | number
     * @param mixed $items = array()
     * @param mixed $responsive | breakpoints for single items | = ["breakpoint" => "number", "width" => "string"]
     * @param mixed $transition_speed = number
     * @param mixed $interval_speed = number
     * @param mixed $first_timeout_speed = number
     */
    public function __construct(
        $id,
        $items = [],
        $responsive = [
            ["breakpoint" => 900, "width" => "50%"],
            ["breakpoint" => 700, "width" => "100%"],
        ],
        $transition_speed = 1500,
        $interval_speed = 2000,
        $first_timeout_speed = 1000
    ) {
        $this->id = $id;
        $this->slider_id = "hc_" . $id . "_slider";
        $this->slide_id = "hc_" . $id . "_slide";

        ?>
        <div id="hc_slider_container" class="w-full overflow-hidden">
            <div class="w-full flex" id="<?= $this->slider_id ?>">
                <?php
                for ($i = 0; $i < count($items); $i++) {
                    $this->singleItem($items[$i]);
                }
                ?>
            </div>
        </div>

        <style>
            <?= "#$this->slider_id" ?>
                {
                transition:
                    <?= "transform " . $transition_speed . "ms ease-in-out" ?>
                ;
            }

            <?php
            for ($i = 0; $i < count(($responsive)); $i++) {
                $point = $responsive[$i];

                ?>

                <?= "@media screen and (max-width: " . $point["breakpoint"] . "px)" ?>
                    {

                    <?= ".$this->slide_id" ?>
                        {
                        <?= "width: " . $point["width"] . ";" ?>
                        <?= "min-width: " . $point["width"] . ";" ?>
                        <?= "max-width: " . $point["width"] . ";" ?>
                    }
                }

                <?php
            }
            ?>
        </style>

        <script>
            window.addEventListener("load", () => {
                const delay = (milliseconds) => {
                    return new Promise(resolve => {
                        setTimeout(resolve, milliseconds);
                    });
                };

                const isInViewport = (element) => {
                    const rect = element.getBoundingClientRect();
                    const scrolledHeight = window.innerHeight;

                    return (
                        rect.top <= scrolledHeight && // Bottom of the element is inside viewport
                        rect.bottom > 0
                    );
                };

                const slider = document.querySelector("<?= "#$this->slider_id" ?>");

                let scrolling = false;
                let resizeFunc;
                let interval;

                const scrollFunc = async () => {
                    let intersect = isInViewport(slider);

                    if (!intersect) {
                        scrolling = false;
                        if (resizeFunc) {
                            resizeFunc();
                            window.removeEventListener("resize", resizeFunc)
                        }
                        if (interval) clearTimeout(interval);
                        return;
                    }

                    if (scrolling) return;
                    scrolling = true;

                    //Duplicating the first three so it looks like a smooth transition
                    const og_slides = [];

                    document.querySelectorAll(".<?= $this->slide_id ?>").forEach(e => og_slides.push(e.cloneNode(true)));

                    //Slider Logic
                    let index = 0;
                    const slides = document.querySelectorAll(".<?= $this->slide_id ?>");
                    let slideWidth = slides[0].offsetWidth; // Slide width + margin

                    resizeFunc = () => {
                        const slides = document.querySelectorAll(".<?= $this->slide_id ?>");
                        slideWidth = slides[0].offsetWidth;
                        index = 0;
                        slider.style.transform = `translateX(-${0}px)`;

                        slides.forEach((slide, slideIndex) => {
                            if (slideIndex > og_slides.length - 1) slider.removeChild(slide);
                        })
                    };
                    window.addEventListener("resize", resizeFunc);

                    const moveSlider = () => {
                        index++;
                        let slideToTransfer = (index % og_slides.length) - 1;
                        if (slideToTransfer < 0) slideToTransfer = og_slides.length - 1;

                        slider.style.transform = `translateX(-${index * slideWidth}px)`;

                        slider.appendChild(og_slides[slideToTransfer].cloneNode(true))
                    };

                    await delay(<?= $first_timeout_speed ?>);
                    moveSlider();
                    interval = setInterval(moveSlider, <?= $interval_speed ?>);
                };
                scrollFunc();
                document.addEventListener("scroll", scrollFunc)
            })
        </script>

        <style>
            .flex {
                display: flex;
            }

            .w-1\/3 {
                width: calc(1/3 * 100%);
            }

            .w-full {
                width: 100%;
            }

            .max-w-1\/3 {
                max-width: calc(1/3 * 100%);
            }

            .min-w-1\/3 {
                min-width: calc(1/3 * 100%);
            }

            .overflow-hidden {
                overflow: hidden;
            }
        </style>
        <?php
    }
}

?>