<?php echo $this->element("/menu-landings"); ?>

<div class="container-fluid bg-img-blue p-4 block4">
  <div class="container p-5 mb-5 bg-white">
     <div class="row">
		<div class="content-tittles">
			<div class="line-tittles">|</div>
			<div>
			  <h1>Pagaré y </h1>
			  <h2>Carta de Instrucciones</h2>
			</div>
		</div>
	   </div>
	   <iframe src="<?php echo $this->Html->url('/legal/pagare.pdf') ?>" frameborder="1" scrolling="auto" width="100%" height="500" class="mb-5"></iframe>

	</div>
</div>
