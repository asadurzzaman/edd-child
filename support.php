<?php
if (!is_user_logged_in()) {
    wp_redirect(site_url('log-in'));
    exit;
}
/**
 * Template Name: Support Template
 */
?>
<?php
get_header();
?>
<style>
    .footer-area {
        padding-top: 100px !important;
    }
</style>

<?php
$current_user = wp_get_current_user();
?>


<!-- Checkout  Area  -->
<section class="wfn-myaccount-area">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="header-section text-center">
                    <h4>Hello <?php echo $current_user->user_login; ?></h4>
                    <p>From here, you'll be able to see your purchase details, download plugins, get your license key, manage your profile and payment methods.</p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="myaccount-wrapper">
                    <div class="tabs">
                        <a class="tab_item" href="<?php echo site_url() ?>/my-account/#dashboard" data-target="dashboard">Dashboard</a>
                        <a href="<?php echo site_url() ?>/my-account/#purches_history" class="tab_item" data-target="purches_history">Purchase History</a>
                        <a href="<?php echo site_url() ?>/my-account/#subscriptio" class="tab_item " data-target="subscriptio">Subscriptions</a>
                        <a href="<?php echo site_url() ?>/affiliate-area/" class="tab_item" data-target="affiliate-area">Affiliate</a>
                        <a href="<?php echo site_url() ?>/support/" class="tab_item active" data-target="support">Support</a>
                        <a href="<?php echo site_url() ?>/my-account/#profile" class="tab_item" data-target="profile">Profile</a>
                        <a class="tab_item" href="<?php echo wp_logout_url(home_url() . '/log-in'); ?>" class="btn btn-left-custom">Log Out</a>
                    </div>

                    <div class="tap-body-wrapper">
                        <div id="support" class=" tab_body">
                            <?php echo do_shortcode('[fluent_support_portal]'); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php
get_footer();
?>