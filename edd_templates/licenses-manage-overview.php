<?php

if (!is_user_logged_in()) {
    return;
}

$payment_id = absint($_GET['payment_id']);
$user_id    = edd_get_payment_user_id($payment_id);

echo $payment_id ;

if (!current_user_can('manage_licenses') && $user_id != get_current_user_id()) {
    return;
}

$color = edd_get_option('checkout_color', 'gray');
$color = ($color == 'inherit') ? '' : $color;

$customer = edd_get_customer_by('user_id', get_current_user_id());
$page     = get_query_var('paged') ? get_query_var('paged') : 1;

if (!empty($customer)) {
    $orders = edd_get_orders(
        array(
            'customer_id'    => $customer->id,
            'number'         => 20,
            'offset'         => 20 * (intval($page) - 1),
            'type'           => 'sale',
            'status__not_in' => array('trash', 'refunded', 'abandoned'),
        )
    );
} else {
    $orders = array();
}




foreach ($orders as $order) :
    foreach ($order->get_items_with_bundles() as $key => $item) :
        $name           = $item->product_name;
        $price_id       = $item->price_id;
        $order_id       = $item->order_id;
        $product_id     = $item->product_id;
        $download_files = edd_get_download_files($product_id, $price_id);
            // echo "<pre>";
            // var_dump($item);
            // echo "<pre>";
    endforeach;
endforeach;


// Retrieve all license keys for the specified payment
$keys = edd_software_licensing()->get_licenses_of_purchase($payment_id);
$keys = apply_filters('edd_sl_manage_template_payment_licenses', $keys, $payment_id);

?>
<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
        var showKeys = document.querySelectorAll('.edd_sl_show_key');
        if (showKeys) {
            for (var i = 0; i < showKeys.length; i++) {
                showKeys[i].addEventListener('click', function(e) {
                    e.preventDefault();
                    var key = this.parentNode.querySelector('.edd_sl_license_key');
                    key.style.display = (key.style.display != 'block') ? 'block' : 'none';
                });
            }
        }
    });
</script>
<p><a href="<?php echo esc_url(remove_query_arg(array('action', 'payment_id', 'edd_sl_error'))); ?>" class="edd-manage-license-back edd-submit button <?php echo esc_attr($color); ?>"><?php _e('Go back', 'edd_sl'); ?></a></p>
<?php


if ($keys) :
    foreach ($keys as $license) :
    echo "<pre>";
    var_dump($keys);
    echo "</pre>";

?>
        <div class="ph_pament_details">
            <h4>Payment Details</h4>
            <div class="fedd_payment_header">
                <ul>
                    <li><span class="head_label">Payment ID:</span><span class="head_value"><?php echo $license->payment_id; ?></span></li>
                    <li><span class="head_label">Date:</span><span class="head_value"><?php echo esc_html(edd_date_i18n(EDD()->utils->date($license->date_created, null, true)->toDateTimeString())); ?></span></li>
                    <li><span class="head_label">Total:</span><span class="head_value"><?php echo esc_html(edd_display_amount($item->amount, $order->currency)); ?></span></li>
                    <li><span class="head_label">Payment Status:</span><span class="head_value"><?php echo $order->status; ?></span></li>
                    <li><span class="head_label">Payment Method:</span><span class="head_value"><?php echo $order->gateway; ?></span></li>
                    <li><span class="head_label">Transaction ID:</span><span class="head_value"><?php echo $order->payment_key; ?></span></li>
                    <li><span class="head_label">Invoice:</span><span class="head_value"><a href="<?php echo esc_url(edd_invoices_get_invoice_url($order->id)); ?>">View Invoice</a></span></li>
                </ul>
            </div>
        </div>

        <div class="ph_product_details">
            <h4>Downloads and License Details</h4>
            <div class="edd_dashborad">
                <div class="edd_title">
                    <h4><?php echo $license->get_name(); ?></h4>
                    <p class="license_status license_active edd-sl-<?php echo esc_attr($license->status); ?>"><?php echo wp_kses_post($license->get_display_status()); ?></p>
                    <ul class="licenses_info">
                        <li><span><i class="demo-icon ca-globe">&#xe807;</i>Site:</span> <small><?php echo esc_html($license->activation_count); ?></span><span class="edd_sl_limit_sep">&nbsp;/&nbsp;</span><span class="edd_sl_limit_max"><?php echo esc_html(0 !== $license->activation_limit ? $license->activation_limit : __('Unlimited', 'edd_sl')); ?></span> </small></li>
                        <li><span><i class="demo-icon ca-calendar">&#xe809;</i>Purchase Date :</span> <small><?php echo esc_html(edd_date_i18n(EDD()->utils->date($license->date_created, null, true)->toDateTimeString())); ?></small></li>
                        <li><span><i class="demo-icon ca-clock">&#xe80a;</i>Expiration Date:</span>
                            <small>
                                <?php if ($license->is_lifetime) : ?>
                                    <?php esc_html_e('Lifetime', 'edd_sl'); ?>
                                <?php else : ?>
                                    <?php echo date_i18n('F j, Y', $license->expiration); ?>
                                <?php endif; ?>
                            </small>
                        </li>
                    </ul>
                    <div class="license_details">
                        <p><span>License Key:</span> <?php echo '<code>' . $license->license_key . '</code>'; ?></p>
                    </div>
                </div>
                <div class="edd-footer">
                    <ul> 
                        <li class="download_button">
                            <?php if (!edd_no_redownload()) : ?>
                                <?php
                                if ($item->is_deliverable()) :
                                // echo "<pre>";
                                // var_dump($item);
                                // echo "</pre>";


                                    if ($download_files) :
                                        foreach ($download_files as $filekey => $file) :
                                            $download_url = edd_get_download_file_url($order->payment_key, $order->email, $filekey, $item->product_id, $price_id);
                               ?>
                                            <a href="<?php echo esc_url($download_url); ?>" class="edd_download_file_link"><i class="demo-icon ca-download">&#xe805;</i>Download</a>
                                    <?php
                                            do_action('edd_download_history_download_file', $filekey, $file, $item, $order);
                                        endforeach;
                                    else :
                                        esc_html_e('No downloadable files found.', 'easy-digital-downloads');
                                    endif; // End if payment complete
                                else : ?>
                                    <span class="edd_download_payment_status">
                                        <?php
                                        printf(
                                            /* translators: the order item's status. */
                                            esc_html__('Status: %s', 'easy-digital-downloads'),
                                            esc_html(edd_get_status_label($item->status))
                                        );
                                        ?>
                                    </span>
                                <?php
                                endif; // End if $download_files
                                ?>
                            <?php endif; ?>
                        </li>

                        <?php if (!edd_software_licensing()->force_increase()) :
                            // $keys = edd_software_licensing()->get_licenses_of_purchase($payment_id);
                            // $keys = apply_filters('edd_sl_manage_template_payment_licenses', $keys, $payment_id);
                            // foreach ($keys as $license) :
                        ?>
                            <li class="manage_button">
                                <a href="/my-account/?action=manage_licenses&payment_id=<?php echo esc_html($order->get_number()); ?>&license_id=<?php echo $license->ID; ?>"><i class="demo-icon ca-compass">&#xe808;</i><?php esc_html_e('Manage Sites', 'edd_sl'); ?></a>
                            </li>
                        <?php //endforeach;
                        endif; ?>
                        <li class="extend_button">
                            <?php if (edd_sl_renewals_allowed() && 0 == $license->parent) : ?>
                                <?php if ('expired' === $license->status && edd_software_licensing()->can_renew($license->ID)) : ?>
                                    <a href="<?php echo esc_url($license->get_renewal_url()); ?>"><i class="demo-icon ca-download-cloud">&#xe806;</i><?php esc_html_e('Renew license', 'edd_sl'); ?></a>
                                <?php elseif (!$license->is_lifetime && edd_software_licensing()->can_extend($license->ID)) : ?>
                                    <a href="<?php echo esc_url($license->get_renewal_url()); ?>"><i class="demo-icon ca-download-cloud">&#xe806;</i><?php esc_html_e('Extend license', 'edd_sl'); ?></a>
                                <?php endif; ?>
                            <?php endif; ?>
                        </li>
                        <li class="renew_button">
                            <?php if ('expired' !== $license->status && edd_sl_license_has_upgrades($license->ID)) : ?>
                                <a href="<?php echo esc_url(add_query_arg(array('view' => 'upgrades', 'license_id' => $license->ID))); ?>"><i class="demo-icon ca-download-cloud">&#xe806;</i><?php esc_html_e('View Upgrades', 'edd_sl'); ?></a>
                            <?php elseif ('expired' === $license->status && edd_sl_license_has_upgrades($license->ID)) : ?>
                                <span class="edd_sl_no_upgrades"><i class="demo-icon ca-download-cloud">&#xe806;</i><?php esc_html_e('Renew to upgrade', 'edd_sl'); ?></span>
                            <?php else : ?>
                                <span class="edd_sl_no_upgrades"><?php esc_html_e('No upgrades available', 'edd_sl'); ?></span>
                            <?php endif; ?>
                        </li>
                    </ul>
                </div>
            </div>

        </div>
<?php endforeach;
endif;
?>