<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content after
 *
 * @package _tk
 */
?>
			</div><!-- close .*-inner (main-content or sidebar, depending if sidebar is used) -->
	</div><!-- close .container -->
</div><!-- close .main-content -->

<footer id="colophon" class="site-footer" role="contentinfo">
<?php // substitute the class "container-fluid" below if you want a wider content area ?>
	<div class="container">
		<div class="row">

			<div class="site-footer-inner col-sm-6 col-sm-push-6">
				<div class="site-legal">
					<?php //do_action( '_tk_credits' ); ?>
					<!--p><a href="#" title="Regole e Condizioni di Servizio">Regole e Condizioni di Servizio</a></p-->
					<p><a href="http://www.casacaldiero.it/it/privacy-cookie-policy/" title="Privacy & Cookie Policy">Privacy &amp; Cookie Policy</a></p>
				</div><!-- close .site-info -->
			</div>
			<div class="site-footer-inner col-sm-6 col-sm-pull-6">
				<div class="site-info">
					<?php $info = getdate(); ?>
					<p><?php bloginfo( 'description' ); ?></p>
					<p>&copy; <?php echo $info['year']; ?> <?php bloginfo( 'name' ); ?> - Tutti i diritti riservati</p>
				</div><!-- close .site-info -->
			</div>

		</div>
	</div><!-- close .container -->
</footer><!-- close #colophon -->

<?php wp_footer(); ?>

<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-5EE2D8RYGB"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-5EE2D8RYGB');
</script>

</body>
</html>
