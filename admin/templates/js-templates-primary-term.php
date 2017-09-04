<?php
/**
 * Underscore.js style template for buttons and input fields
 * to be included on post add and edit screens.
 *
 * @package Easy_Primary_Category
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}
?>

<script type="text/html" id="tmpl-primary-term-input">
	<input type="hidden" class="easy-primary-term"
		   id="easy-primary-{{data.taxonomy.name}}"
		   name="epc_primary_{{data.taxonomy.name}}_term"
		   value="{{data.taxonomy.primary}}">

	<?php wp_nonce_field( 'save-primary-term', 'epc_primary_{{data.taxonomy.name}}_nonce' ); ?>
</script>

<script type="text/html" id="tmpl-primary-term-ui">
<?php
printf(
	'<button type="button" class="easy-make-primary-term" aria-label="%1$s">%2$s</button>', esc_attr(
		sprintf(
			/* translators: accessibility text. %1$s expands to the term title, %2$s to the taxonomy title. */
			__( 'Make %1$s primary %2$s', 'easy-primary-category' ), '{{data.term}}', '{{data.taxonomy.title}}'
		)
	), esc_html__( 'Make primary', 'easy-primary-category' )
);
?>

	<span class="easy-is-primary-term" aria-hidden="true"><?php esc_html_e( 'Primary', 'easy-primary-category' ); ?></span>
</script>

<script type="text/html" id="tmpl-primary-term-screen-reader">
	<span class="screen-reader-text easy-primary-category-label">
	<?php
		echo esc_html(
			sprintf(
				/* translators: %s is the taxonomy title. This will be shown to screenreaders */
				 '(' . __( 'Primary %s', 'easy-primary-category' ) . ')', '{{data.taxonomy.title}}'
			)
		);
		?>
		</span>
</script>
