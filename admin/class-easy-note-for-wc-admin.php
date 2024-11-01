<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://bigbenteam.com
 * @since      1.0.0
 *
 * @package    Easy_Note_For_WC
 * @subpackage Easy_Note_For_WC/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 *
 * @package    Easy_Note_For_WC
 * @subpackage Easy_Note_For_WC/admin
 * @author     Bigben Team <hello@bigbenteam.com>
 */
class Easy_Note_For_WC_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in WC_Easy_Note_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The WC_Easy_Note_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/easy-note-for-wc-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in WC_Easy_Note_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The WC_Easy_Note_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/easy-note-for-wc-admin.js', array( 'jquery' ), $this->version, false );

	}

	public function columns_function($columns){
		$new_columns = (is_array($columns)) ? $columns : array();
		unset( $new_columns['order_actions'] );
	
		//edit this for you column(s)
		//all of your columns will be added before the actions column
		
		$new_columns['order_lastComments'] = 'Last Note';
		//stop editing
	
		$new_columns['order_actions'] = $columns['order_actions'];
		return $new_columns;
	}
	
	public function columns_values_function($column){
		global $post, $the_order;
		$data = get_post_meta( $post->ID );
		$commentsCount = get_comments_number( $post->ID );

		if(empty($the_order) || $the_order->get_id() != $post->ID) {
			$the_order = wc_get_order($post->ID);
			}

		$notes = wc_get_order_notes( ['order_id' => $the_order->get_id(), 'order_by' => 'date_created', 'type' => 'internal'] );
		error_log(print_r($notes[0], true));
		//start editing, I was saving my fields for the orders as custom post meta
		//if you did the same, follow this code
		
		if ( $column == 'order_lastComments' ) {   
			if(isset($notes[0])) {
				?> <strong><?php echo esc_html($notes[0]->content); ?> </strong><br/>by <strong> <?php esc_html($notes[0]->added_by); ?> </strong> on <strong> <?php echo esc_html($notes[0]->date_created->date( __( 'd/m/Y g:i:s A', 'woocommerce' ))) ?> </strong> <?php
			} else {
				?> <strong> <?php echo esc_html('There are no notes yet.'); ?> </strong> <?php
			}
		}
		if( $column == 'order_number') {
			?> <a href="#" class="order-notes" data-order-id="<?php echo esc_html(absint( $the_order->get_id() )); ?>" title="<?php echo esc_html(esc_attr(  $commentsCount . ' Notes' )); ?>"> <?php echo esc_html($commentsCount . ' Notes'); ?> </a> <?php
		}
		//stop editing
	}

	public function admin_order_preview_add_order_notes_data( $data, $order ) {
        
		$notes = wc_get_order_notes([
			'order_id' => $order->get_id(),
			'number' => 5
		]);
	
		ob_start();
	
		?>
		<div class="wc-order-preview-order-note-container" style="padding: 20px;">
			<div class="wc-order-preview-custom-note">
			<h2 class="order-note">Order Note:</h2>

				<div class="add_note">
					<p>
						<label for="add_order_note"><?php esc_html_e( 'Add note', 'woocommerce' ); ?> <?php echo wc_help_tip( __( 'Add a note for your reference, or add a customer note (the user will be notified).', 'woocommerce' ) ); ?></label>
						<textarea type="text" name="order_note" id="add_order_note" class="input-text" cols="20" rows="5"></textarea>
					</p>
					<p>
						<label for="order_note_type" class="screen-reader-text"><?php esc_html_e( 'Note type', 'woocommerce' ); ?></label>
						<select name="order_note_type" id="order_note_type">
							<option value=""><?php esc_html_e( 'Private note', 'woocommerce' ); ?></option>
							<option value="customer"><?php esc_html_e( 'Note to customer', 'woocommerce' ); ?></option>
						</select>
						<button type="button" class="add_note bgbn_wc_cm_add_note button"><?php esc_html_e( 'Add', 'woocommerce' ); ?></button>
					</p>
				</div>
				<hr />
				<ul class="order_notes" id="woocommerce-comment-manager-order-notes">
					<?php
					if ( $notes ) {
						foreach ( $notes as $note ) {
							$css_class   = array( 'note' );
							$css_class[] = $note->customer_note ? 'customer-note' : '';
							$css_class[] = 'system' === $note->added_by ? 'system-note' : '';
							$css_class   = apply_filters( 'woocommerce_order_note_class', array_filter( $css_class ), $note );
							?>
							<li rel="<?php echo absint( $note->id ); ?>" class="<?php echo esc_attr( implode( ' ', $css_class ) ); ?>">
								<div class="note_content">
									<?php echo wpautop( wptexturize( wp_kses_post( $note->content ) ) ); // @codingStandardsIgnoreLine ?>
								</div>
								<p class="meta">
									<abbr class="exact-date" title="<?php echo esc_attr( $note->date_created->date( 'Y-m-d H:i:s' ) ); ?>">
										<?php
										
										echo esc_html( sprintf( __( '%1$s at %2$s', 'woocommerce' ), $note->date_created->date_i18n( wc_date_format() ), $note->date_created->date_i18n( wc_time_format() ) ) );
										?>
									</abbr>
									<?php
									if ( 'system' !== $note->added_by ) :
										
										echo esc_html( sprintf( ' ' . __( 'by %s', 'woocommerce' ), $note->added_by ) );
									endif;
									?>
									<a href="#" class="delete_note bgbn_wc_cm_delete_note" role="button"><?php esc_html_e( 'Delete note', 'woocommerce' ); ?></a>
								</p>
							</li>
							<?php
						}
					} else {
						?>
						<li class="no-items"><?php esc_html_e( 'There are no notes yet.', 'woocommerce' ); ?></li>
						<?php
					}
					?>
				</ul>
				<hr />
			</div>
		</div>
		<?php
	
		$order_notes = ob_get_clean();  
	
		$data['order_notes'] = $order_notes;
	
		return $data;
	
	}

	public function woocommerce_admin_order_preview_order_notes() {
		?> {{{data.order_notes}}} <?php
	}
}
