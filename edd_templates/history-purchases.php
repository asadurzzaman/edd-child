<?php

/**
 * Shortcode: Purchase History - [purchase_history]
 *
 * @package EDD
 * @category Template
 *
 * @since 3.0 Allow details link to appear for `partially_refunded` orders.
 */

if (!empty($_GET['edd-verify-success'])) : ?>
	<p class="edd-account-verified edd_success">
		<?php esc_html_e('Your account has been successfully verified!', 'easy-digital-downloads'); ?>
	</p>
<?php
endif;

/**
 * This template is used to display the purchase history of the current user.
 */

if (!is_user_logged_in()) {
	return;
}

$page    = get_query_var('paged') ? get_query_var('paged') : 1;
$user_id = get_current_user_id();
$orders  = edd_get_orders(
	array(
		'user_id'        => $user_id,
		'number'         => 20,
		'offset'         => 20 * (intval($page) - 1),
		'type'           => 'sale',
		'status__not_in' => array('trash'),
	)
);

/**
 * Fires before the order history, whether or not orders have been found.
 *
 * @since 3.0
 * @param array $orders  The array of order objects for the current user.
 * @param int   $user_id The current user ID.
 */

do_action('edd_pre_order_history', $orders, $user_id);

if ($orders) :
	do_action('edd_before_order_history', $orders);
?>
	<div class="ph_purchase_history">
		<h4>Purchase History</h4>
		<table id="edd_user_history" class="edd-table">
			<thead>
				<tr class="edd_purchase_row">
					<th class="edd_purchase_id"><?php esc_html_e('ID', 'easy-digital-downloads'); ?></th>
					<th class="edd_purchase_date"><?php esc_html_e('Date', 'easy-digital-downloads'); ?></th>
					<th class="edd_purchase_details"><?php esc_html_e('Payment Method', 'easy-digital-downloads'); ?></th>
					<th class="edd_purchase_amount"><?php esc_html_e('Amount', 'easy-digital-downloads'); ?></th>
					<th class="edd_purchase_amount"><?php esc_html_e('Details', 'easy-digital-downloads'); ?></th>
					<th class="edd_purchase_amount"><?php esc_html_e('Invoice', 'easy-digital-downloads'); ?></th>
				</tr>
			</thead>

			<?php foreach ($orders as $order) : ?>

				<tr class="edd_purchase_row">
					<td class="edd_purchase_id"><a href="/my-account/?action=manage_licenses&payment_id=<?php echo esc_html($order->get_number()); ?>">#<?php echo esc_html($order->get_number()); ?></a></td>
					<td class="edd_purchase_date"><?php echo esc_html(edd_date_i18n(EDD()->utils->date($order->date_created, null, true)->toDateTimeString())); ?></td>
					<td class="payment_meathord"><?php echo $order->gateway; ?></td>

					<td class="edd_purchase_amount">
						<span class="edd_purchase_amount"><?php echo esc_html(edd_display_amount($order->total, $order->currency)); ?></span>
					</td>
					<td class="order_status"><?php echo $order->status; ?></td>


					<td class="edd_invoice">
						<a href="<?php echo esc_url(edd_invoices_get_invoice_url($order->id)); ?>"><?php esc_html_e('View Invoice', 'edd-invoices'); ?></a>
					</td>

				</tr>
			<?php endforeach; ?>
		</table>
	</div>

	<?php
	$count = edd_count_orders(
		array(
			'user_id' => get_current_user_id(),
			'type'    => 'sale',
		)
	);

	echo edd_pagination(
		array(
			'type'  => 'purchase_history',
			'total' => ceil($count / 20), // 20 items per page
		)
	);
	do_action('edd_after_order_history', $orders);
	?>
<?php else : ?>
	<p class="edd-no-purchases"><?php esc_html_e('You have not made any purchases.', 'easy-digital-downloads'); ?></p>
<?php
endif;
