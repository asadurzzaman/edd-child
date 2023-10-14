<?php get_header(); ?>

<div id="main-content" class="row store-template">
    <div class="content clearfix">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="product_left">
                        <h2 class="title"><?php the_title(); ?></h2>
                        <?php the_post_thumbnail('', array('alt' => get_the_title())); ?>
                        <?php the_content(); ?>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="product_right">
                        <?php if (function_exists('edd_price')) { ?>
                            <div class="product-price">
                                <?php
                                if (edd_has_variable_prices(get_the_ID())) {
                                    // if the download has variable prices, show the first one as a starting price
                                    echo 'Starting at: ';
                                    edd_price(get_the_ID());
                                } else {
                                    edd_price(get_the_ID());
                                }
                                ?>
                            </div>
                            <!--end .product-price-->
                        <?php } ?>
                    </div>
                    <?php if (function_exists('edd_price')) { ?>
                        <div class="product-buttons">
                            <?php if (!edd_has_variable_prices(get_the_ID())) { ?>
                                <?php echo edd_get_purchase_link(get_the_ID(), 'Add to Cart', 'button'); ?>
                            <?php } ?>
                        </div>
                        <!--end .product-buttons-->
                    <?php } ?>
                </div>

                <!--end .product-->
            </div>
        </div>
    </div>
    <!--end .content-->
</div>
<!--end #main-content.row-->

<?php get_footer(); ?>