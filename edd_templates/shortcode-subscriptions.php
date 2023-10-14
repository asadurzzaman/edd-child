<?php

/**
 *  EDD Template File for [edd_subscriptions] shortcode
 *
 * @description: Place this template file within your theme directory under /my-theme/edd_templates/
 *
 * @copyright  : http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since      : 2.4
 */

//For logged in users only
if (is_user_logged_in()) :

	if (!empty($_GET['updated']) && '1' === $_GET['updated']) :

?>
		<div class="edd-alert edd-alert-success">
			<?php _e('<strong>Success:</strong> Subscription payment method updated', 'edd-recurring'); ?>
		</div>
		<?php

	endif;

	//Get subscription
	$subscriber    = new EDD_Recurring_Subscriber(get_current_user_id(), true);
	$subscriptions = $subscriber->get_subscriptions(0, array('active', 'expired', 'cancelled', 'failing', 'trialling'));


	if ($subscriptions) :
		foreach ($subscriptions as $subscription) :
			$frequency    = EDD_Recurring()->get_pretty_subscription_frequency($subscription->period);
			$renewal_date = !empty($subscription->expiration) ? date_i18n(get_option('date_format'), strtotime($subscription->expiration)) : __('N/A', 'edd-recurring');
			$created_date = !empty($subscription->created) ? date_i18n(get_option('date_format'), strtotime($subscription->created)) : __('N/A', 'edd-recurring');
		?>
			<div class="subscriber">
				<div class="subscriber_top">
					<div class="product_name">
						<td>
							<?php
							$download = edd_get_download($subscription->product_id);
							if ($download instanceof EDD_Download) {
								$product_name = $download->get_name();
								if (!is_null($subscription->price_id) && $download->has_variable_prices()) {
									$prices = $download->get_prices();
									if (isset($prices[$subscription->price_id]) && !empty($prices[$subscription->price_id]['name'])) {
										$product_name .= ' &mdash; ' . $prices[$subscription->price_id]['name'];
									}
								}
							} else {
								$product_name = '&mdash;';
							}
							?>
							<h4>
								<span class="edd_subscription_name">
									<?php echo esc_html($product_name); ?>
								</span>
								<span class="edd_subscription_billing_cycle">
									<?php echo edd_currency_filter(edd_format_amount($subscription->recurring_amount), edd_get_payment_currency_code($subscription->parent_payment_id)) . ' / ' . $frequency; ?>
								</span>
							</h4>
 
							<p class="status license_status license_active edd-sl-<?php echo esc_attr(strtolower($subscription->get_status_label())); ?>"><?php echo wp_kses_post($subscription->get_status_label()); ?></p>
							<ul class="date">
								<li><span><i class="demo-icon ca-calendar">&#xe809;</i>Start Date: </span>
									<span class="edd_subscription_created_date"><?php echo $created_date; ?></span>
								</li>
								<li><span><i class="demo-icon ca-clock">&#xe80a;</i>Renewal:
										<?php if ('trialling' == $subscription->status) : ?>
											<?php _e('Trialling Until:', 'edd-recurring'); ?>
										<?php endif; ?>
										<span class="edd_subscription_renewal_date"><?php echo $renewal_date; ?></span>
									</span></li>
							</ul>
					</div>
				</div>
				<div class="subscriber_bottom">
					<?php if ($subscription->can_update()) : ?>
						&nbsp;|&nbsp;
						<a href="<?php echo esc_url($subscription->get_update_url()); ?>"><?php _e('Update Payment Method', 'edd-recurring'); ?></a>
					<?php endif; ?>
					<?php if ($subscription->can_renew()) : ?>
						&nbsp;|&nbsp;
						<a href="<?php echo esc_url($subscription->get_renew_url()); ?>" class="edd_subscription_renew"><?php _e('Renew', 'edd-recurring'); ?></a>
					<?php endif; ?>
					<?php if ($subscription->can_cancel()) : ?>
						&nbsp;|&nbsp;
						<a href="<?php echo esc_url($subscription->get_cancel_url()); ?>" class="edd_subscription_cancel">
							<?php echo edd_get_option('recurring_cancel_button_text', __('Cancel', 'edd-recurring')); ?>
						</a>
					<?php endif; ?>
					<?php if ($subscription->can_reactivate()) : ?>
						&nbsp;|&nbsp;
						<a href="<?php echo esc_url($subscription->get_reactivation_url()); ?>" class="edd-subscription-reactivate"><?php _e('Reactivate', 'edd-recurring'); ?></a>
					<?php endif; ?>
				</div>
			</div>

		<?php
		endforeach;
	else :
		?>

		<p class="edd-no-purchases"><?php _e('You have not made any subscription purchases.', 'edd-recurring'); ?></p>

	<?php endif; //end if subscription 
	?>

<?php endif; //end is_user_logged_in() 
?>