  <footer class="footer">
  </footer>
    
  <script>
  <?php foreach($AddToWindow as $global_obj => $mapping): ?>
    window.<?php echo $global_obj; ?> = <?php echo json_encode($mapping); ?>;
  <?php endforeach; ?>
  </script>
	<?php wp_footer(); ?>
</body>

</html>