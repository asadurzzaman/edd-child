<?php
if (!is_user_logged_in()) {
    wp_redirect(site_url('log-in'));
    exit;
}
/**
 * Template Name: Myaccount Page Template
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
$target_tab = isset($_GET['target_tab']) ? $_GET['target_tab'] : 'dashboard';
$display_block = "display:block;";
$display_none = "display:none;";

$current_user = wp_get_current_user();
?>
<script>
    jQuery(document).ready(function($) {
        // 		$('.myaccount-wrapper .tabs a')
        $(document.body).on('click', '.myaccount-wrapper .tabs a.quick-link', function(e) {
            e.preventDefault();
            var target_id = $(this).data('target');
            $('.myaccount-wrapper .tabs a').removeClass('active');
            $(this).addClass('active');
            $('.tap-body-wrapper .tab_body').hide();
            console.log(target_id);
            $('#' + target_id).css('display', 'block');
        });
    });

</script>

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
<!--                         <a class="tab_item quick-link <?php echo ($target_tab == 'dashboard') ? 'active' : ''; ?>" href="#" data-target="dashboard">Dashboard</a>
                        <a href="#purches_history" class="tab_item quick-link <?php echo ($target_tab == 'purches_history') ? 'active' : ''; ?>" data-target="purches_history">Purchase History</a>
                        <a href="#" class="tab_item quick-link <?php echo ($target_tab == 'subscriptio') ? 'active' : ''; ?>" data-target="subscriptio">Subscriptions</a>
                        <a href="#" class="tab_item <?php echo ($target_tab == 'affiliate') ? 'active' : ''; ?>" data-target="affiliate">Affiliate</a>
                        <a href="#" class="tab_item <?php echo ($target_tab == 'support') ? 'active' : ''; ?>" data-target="support">Support</a>
                        <a href="#" class="tab_item quick-link <?php echo ($target_tab == 'profile') ? 'active' : ''; ?>" data-target="profile">Profile</a> -->
						
						<?php $link_prefix = home_url() . '/my-account?target_tab='; ?>
						
						<a class="tab_item quick-link <?php echo ($target_tab == 'dashboard') ? 'active' : ''; ?>" href="<?php echo $link_prefix . 'dashboard'; ?>" data-target="dashboard">Dashboard</a>
                        <a href="<?php echo $link_prefix . 'purches_history'; ?>" class="tab_item quick-link <?php echo ($target_tab == 'purches_history') ? 'active' : ''; ?>" data-target="purches_history">Purchase History</a>
                        <a href="<?php echo $link_prefix . 'subscriptio'; ?>" class="tab_item quick-link <?php echo ($target_tab == 'subscriptio') ? 'active' : ''; ?>" data-target="subscriptio">Subscriptions</a>
                        <a href="<?php echo site_url() ?>/affiliate-area/" class="tab_item" data-target="affiliate">Affiliate</a>
						<a href="<?php echo site_url() ?>/support/" class="tab_item" data-target="support">Support</a>
                        <a href="<?php echo $link_prefix . 'profile'; ?>" class="tab_item quick-link <?php echo ($target_tab == 'profile') ? 'active' : ''; ?>" data-target="profile">Profile</a>
                        <a class="tab_item" href="<?php echo wp_logout_url(home_url() . '/log-in'); ?>" class="btn btn-left-custom">Log Out</a>
                    </div>
					
                    <div class="tap-body-wrapper">
                        <div id="dashboard" class="tab_body" style="<?php echo ($target_tab == 'dashboard') ? $display_block : $display_none; ?>">
                            <?php echo do_shortcode('[download_history]'); ?>
                        </div>
                        <div id="purches_history" class="tab_body" style="<?php echo ($target_tab == 'purches_history') ? $display_block : $display_none; ?>">
                            <?php echo do_shortcode('[purchase_history]'); ?>
                        </div>
                        <div id="subscriptio" class="tab_body" style="<?php echo ($target_tab == 'subscriptio') ? $display_block : $display_none; ?>">
                            <?php echo do_shortcode('[edd_subscriptions]'); ?>
                        </div>

<!--                         <div id="affiliate" class="tab_body" style="<?php echo ($target_tab == 'affiliate') ? $display_block : $display_none; ?>">
                            <?php echo do_shortcode('[affiliate_area] ');  ?>
                        </div> -->

                        <div id="support" class=" tab_body" style="<?php echo ($target_tab == 'support') ? $display_block : $display_none; ?>">
                            <?php echo do_shortcode('[fluent_support_portal]'); ?>
                        </div>
                        <div id="profile" class="tab_body" style="<?php echo ($target_tab == 'profile') ? $display_block : $display_none; ?>">
                            <?php echo do_shortcode('[edd_profile_editor]'); ?>
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