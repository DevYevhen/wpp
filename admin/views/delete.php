<div class="wrap">
 
	<h1>Are you sure you want to delete Quote#<?php echo esc_html( $quote['id'] ); ?>?</h1>
	<form method="post" action="<?php echo esc_html( admin_url( 'admin-post.php' ) ); ?>">
		<input type="hidden" name="action" value="quotes_rest_delete"/>
			<input type="hidden" name="quote_id" value="<?php echo $quote['id']; ?>"/>
		<label>Quote Quthor:</label>
			<p><strong><?php echo $quote['author']['name']; ?></strong></p>
		</label>
		<label>Quote Body:
			<textarea name="quote_body" style="width:100%" rows="10" readonly><?php echo esc_html($quote['body']); ?></textarea>
		</label>

		<p class="submit"><input type="submit" name="submit" id="submit" class="button button-link-delete" value="Delete Quote"></p>
    </form>
 
</div>
