<?php
if (!defined('ABSPATH')) exit;	
 global $wpdb;
	  if( isset( $_POST['export_sales_submit'] ) ) {
	  if (isset( $_POST['csv_byproductcat'] ) && wp_verify_nonce($_POST['csv_byproductcat'], 'csv-export') ) {
	   $start_date = sanitize_text_field($_POST['start_date']);
       $end_date = sanitize_text_field($_POST['end_date']);
	   $export_sales_cat = sanitize_text_field($_POST['export_sales_cat']);
       $dt= date( 'Y-m-d', strtotime( $start_date )-1 );
       $dt1 = date( 'Y-m-d', strtotime( $end_date )+1 );
       $customer_orders1 = get_posts( array(
              'numberposts' => -1,
              'post_type'   => array( 'shop_order' ),
              'post_status' => array( 'wc-completed' ),
              'date_query' => array(
                  'after' => $dt,
                  'before' => $dt1
              )
          ) );  
      header('Content-Type: text/csv');
      header('Content-Disposition: attachment; filename="sales_data_of_'.$export_sales_cat.'"between"'.$start_date.'"to"'.$end_date.'.csv"');  
     ob_end_clean();
     $fp = fopen('php://output', 'w');
     $header_row = array(
        0 => 'Order status',
        1 => 'Order Id',
        2 => 'User Id',
    	  3 => 'User First Name',
        4 => 'User Last Name',
        5 => 'User Email',
        6 => 'Date',
        7 => 'Product name',
         );
     fputcsv($fp, $header_row); 
  foreach ( $customer_orders1 as $resul ) {
  	$order = wc_get_order( $resul );
		foreach( $order->get_items() as $key => $item ) {
					 if( has_term( $_POST['export_sales_cat'], 'product_cat', $item->get_product_id() ) ) {
				        $modified_values = array(
				                       $order->get_status(),
				                        $order->get_order_number(),
				                        $order->get_user_id(),
										$order->billing_first_name,
										 $order->billing_last_name,
										 $order->billing_email,
										 $resul->post_date,
										 $item['name'],
				        );
				          fputcsv( $fp, $modified_values );	
		            }
		        }	     
    }     
fclose( $fp );
exit;
	  }
} 
?>
<div class="wrap">
  <h2><?php _e( 'Export Sales Data by Product Category', 'woo-sales-data-by-product-category' ); ?></h2>
  <form action="" method="post">  
    <div class="row">
      <div class="left">
        <label><?php _e( 'Order From Date', 'woo-sales-data-by-product-category' ); ?></label>
      </div>
      <div class="right">
        <input type="date" name="start_date" value="" required="true" />
      </div>
    </div>
    <div class="row">
      <div class="left">
        <label for="subject"><?php _e( 'Order To Date', 'woo-sales-data-by-product-category' ); ?></label>
      </div>
      <div class="right">
        <input type="date" name="end_date" value="" required="tue" />
      </div>
    </div>
    <div class="row">
      <div class="left">
        <label for="subject"><?php _e( 'Select Your Product Category', 'woo-sales-data-by-product-category' ); ?></label>
      </div>
      <div class="right">
        <select name="export_sales_cat" required="true">
          <option value=""><?php echo esc_attr( __( 'Select Category', 'woo-sales-data-by-product-category' ) ); ?></option>
          <?php $product_categories = get_terms( 'product_cat' ); foreach ($product_categories as $pcategory) { ?>
          <option value="<?php echo esc_attr( __( $pcategory->slug) ); ?>"><?php echo esc_attr( __( $pcategory->name ) );?></option>
          <?php	 } ?>
        </select>
      </div>
    </div>
    <div class="row">
      <div class="left"></div>
      <div class="right">
	  <?php wp_nonce_field('csv-export','csv_byproductcat'); ?>
        <input type="hidden" name="export_sales_submit" />
        <input type="submit"  Value="Csv Export"/>        
      </div>
    </div>
  </form>
</div>	