<div class="wrap">
 
	<h1>Edit Quote#<?php echo esc_html( $quote['id'] ); ?></h1>
	<form method="post" action="<?php echo esc_html( admin_url( 'admin-post.php' ) ); ?>">
		<input type="hidden" name="action" value="quotes_rest_edit"/>
			<input type="hidden" name="quote_id" value="<?php echo $quote['id']; ?>"/>
		<label>Quote Quthor:
			<input type="text" name="quote_author" value="<?php echo $quote['author']['name']; ?>"/>
		</label>
		<label>Quote Body:
			<textarea name="quote_body" style="width:100%" rows="10"><?php echo esc_html($quote['body']); ?></textarea>
		</label>

		<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Update Quote"></p>
    </form>
 
</div>
