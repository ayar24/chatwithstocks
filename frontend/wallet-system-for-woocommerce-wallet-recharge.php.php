<?php
/**
 * Exit if accessed directly
 *
 * @package Wallet_System_For_Woocommerce
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$wsfw_min_max_value = apply_filters( 'wsfw_min_max_value_for_wallet_recharge', array() );
if ( is_array( $wsfw_min_max_value ) ) {
	if ( ! empty( $wsfw_min_max_value['min_value'] ) ) {
		$min_value = $wsfw_min_max_value['min_value'];
		$min_value = apply_filters( 'wps_wsfw_show_converted_price', $min_value );
	} else {
		$min_value = 0;
	}
	if ( ! empty( $wsfw_min_max_value['max_value'] ) ) {
		$max_value = $wsfw_min_max_value['max_value'];
		$max_value = apply_filters( 'wps_wsfw_show_converted_price', $max_value );
	} else {
		$max_value = '';
	}
}
?>
<div class='content active'>
	<?php
	$is_wallet_recharge_enabled = get_option( 'wps_wsfwp_wallet_recharge_tab_enable' );
	$is_pro_plugin              = apply_filters( 'wps_wsfwp_pro_plugin_check', $is_pro_plugin );

	if ( ! $is_pro_plugin ) {
		$is_wallet_recharge_enabled = false;
	}

	if ( 'on' == $is_wallet_recharge_enabled ) {
		?>
		<div class="wallet-recharge-tab">
			<div class="wps-wsfw__re-tab-head">
				<h3><span class="wps-re-title"><?php echo esc_html__( 'Add Balance :', 'wallet-system-for-woocommerce' ); ?></span></h3>
			</div>
			<div class="wps-wsfw__re-tab-wrap">
				<?php
				$wps_wallet_recharge_tab_array = get_option( 'wps_wallet_action_recharge_tab_array' );
				if ( ! empty( $wps_wallet_recharge_tab_array ) && is_array( $wps_wallet_recharge_tab_array ) ) {
					if ( '' == $wps_wallet_recharge_tab_array[0] ) {

						$wps_wallet_recharge_tab_array = array();
					}
				} else {
					$wps_wallet_recharge_tab_array = array();
				}

				$count_data = count( $wps_wallet_recharge_tab_array );
				if ( ! empty( $wps_wallet_recharge_tab_array ) && is_array( $wps_wallet_recharge_tab_array ) ) {
					if ( $count_data > 0 ) {
						for ( $i = 0; $i < $count_data; $i++ ) {
							?>
							<div class="wps-wsfw__re-tab-item wps-active">
							<div class="wps-re__item-wrap">
							<p class="wps-re-offer-desc wps_wallet_top_up_custom_button" recharge_amount="<?php echo esc_attr( $wps_wallet_recharge_tab_array[ $i ] ); ?>"><?php echo wp_kses_post( wc_price( $wps_wallet_recharge_tab_array[ $i ] ) ); ?></p>
							</div>
							</div>
							<?php
						}
					}
				}
				?>
			</div>
		</div>
		<?php
	}
	?>
	<span id="wps_wallet_transfer_form">
		<p class="wps-wallet-field-container form-row form-row-wide">
			<label for="wps_wallet_recharge_amount"><?php echo esc_html__( 'Enter Amount (', 'wallet-system-for-woocommerce' ) . esc_html( get_woocommerce_currency_symbol( $current_currency ) ) . '):'; ?></label>

			<select style="display:none" id="wps_wallet_recharge" name="wps_wallet_recharge_amount" required="">
    		<option value="20">20</option>
    		<option value="50">50</option>
    		<option value="100">100</option>
			</select>
			<button class="recharge_button wps-btn__filled button highlight" id="price1" type="button" onclick="changeValue(20)">$20</button>
			<button  class="recharge_button wps-btn__filled button" id="price2" type="button" onclick="changeValue(50)">$50</button>
			<button  class="recharge_button wps-btn__filled button" id="price3" type="button" onclick="changeValue(100)">$100</button>

			<script>
			function changeValue(value) {
				document.getElementById("wps_wallet_recharge").value = value;
				document.getElementById("price1").classList.remove("highlight");
				document.getElementById("price2").classList.remove("highlight");
				document.getElementById("price3").classList.remove("highlight");
				if (value == 20)
					document.getElementById("price1").classList.add("highlight");
				if (value == 50)
					document.getElementById("price2").classList.add("highlight");
				if (value == 100)
					document.getElementById("price3").classList.add("highlight");
				
			}
			</script>
		</p>
		<p class="error"></p>
		<?php
		do_action( 'wsfw_make_wallet_recharge_subscription' );
		?>
		<p class="wps-wallet-field-container form-row">
			<input type="hidden" name="user_id" value="<?php echo esc_attr( $user_id ); ?>">
			<input type="hidden" name="product_id" value="<?php echo esc_attr( $product_id ); ?>">
			<input type="submit" class="wps-btn__filled button" id="wps_recharge_wallet" name="wps_recharge_wallet" value="<?php esc_html_e( 'Proceed', 'wallet-system-for-woocommerce' ); ?>">
		</p>
</span>
</div>
