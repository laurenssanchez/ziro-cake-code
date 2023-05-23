<?php 

	$vars = [
		"APP_URL" => Router::url("/",true)
	];	

?>

<script>
	const varsJs = <?php echo json_encode($vars); ?>
</script>