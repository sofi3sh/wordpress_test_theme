<?php get_header(); ?>
<main class="wrapper home_page"><span class="opacity-bg"></span>
    <section class="container ">
    <!-- New Block-->
        <div class="slider-container">
            <span class="title">Explore by Room: Tailored Furniture Selections</span>
            <button class="slider-prev">&#10094;</button>
            <div class="slider-wrapper">
                <div class="slider">
                    <?php
                    /**
                     * The query to get the slider posts.
                     * @var WP_Query $slider_query
                     */
                    $args = array(
                        'post_type'      => 'slider',
                        'posts_per_page' => 10,
                        'orderby'        => 'date',
                        'order'          => 'DESC',
                    );
                    $slider_query = new WP_Query($args);

                    if ($slider_query->have_posts()) :
                        while ($slider_query->have_posts()) : $slider_query->the_post();

                            /**
                             * Get the image URL.
                             * @var string $image
                             */
                            $image = get_the_post_thumbnail_url(get_the_ID(), 'large');

                            ?>
                            <div class="slide" data-id="<?php the_ID(); ?>">
                                <img src="<?php echo esc_url($image); ?>" alt="<?php the_title(); ?>">
                                <h3><?php the_title(); ?></h3>
                            </div>
                        <?php endwhile;
                        wp_reset_postdata();
                    endif;
                    ?>
                </div>
            </div>
            <button class="slider-next">&#10095;</button>
            <div class="slider-dots"></div>
        </div>


    <!--  // Модальне вікно -->
        <div id="sliderModal" class="modal" style="display: none">
            <div class="modal-content">
                <span class="close">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M18 6L6 18M6 6L18 18" stroke="#997F5A" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </span>
                <h2 id="modal-title">Description</h2>
                <svg width="752" height="2" viewBox="0 0 752 2" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <line y1="1" x2="752" y2="1" stroke="#8A8A8A"/>
                </svg>
                <p id="modal-description"></p>
            </div>
        </div>
    </section>
</main>
<?php get_footer(); ?>
