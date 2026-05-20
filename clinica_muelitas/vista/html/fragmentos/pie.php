
		<footer id="pie">
			<p>Clínica Muelitas &middot; Sistema interno de citas</p>
		</footer>
		<script>
		window.CSRF_TOKEN = <?php echo json_encode(Seguridad::obtenerTokenCsrf(), JSON_HEX_TAG | JSON_HEX_AMP); ?>;
		</script>
