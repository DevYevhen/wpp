<div class="wrap">
 
	<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
	<h2>New quote</h2>
	<form method="post" action="<?php echo esc_html( admin_url( 'admin-post.php' ) ); ?>">
		<input type="hidden" name="action" value="quotes_rest_create"/>
		<label>Quote Quthor:
			<input type="text" name="quote_author"/>
		</label>
		<label>Quote Body:
			<textarea name="quote_body"></textarea>
		</label>

		<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Create Quote"></p>
    </form>
	<h2>Quotes list</h2>
	<div class="quotes-list">
	<?php foreach($quotes as $quote):?>
		<div class="quote-item">
			<div><span>Author:</span> <?php echo $quote['author']['name']; ?></div>
			<div><span>Quote:</span><p> <?php echo $quote['body']; ?></div>
			<div class="operations"><ul><li><a href="#">edit</a></li><li><a href="#">delete</a></li></ul></div>
		</div>
	<?php endforeach; ?>
 	</div>
 
</div>
