<?php 

$whitelist = array(
            '127.0.0.1',
            '::1'
        );
 ?>
<div class="page-title">
	<div class="row">
		<div class="col-md-9">
			<h3><?php echo __('Panel de informes - Recaudos'); ?></h3>
		
		</div>

		<?php if (in_array(AuthComponent::user("role"),[1])): ?>  	
		<div class="col-md-12">
			<div class="form-group topsearch">
				<?php echo $this->Form->create(null, array('role' => 'form','type'=>'POST','class'=>'')); ?>
				<div class="row">
					<div class="col-md-12 text-center">
						Total de registros: <b><?php echo $data ?></b>, total páginas: <b><?php echo $totalPages ?></b>. 
						Registros por página <b> <?php echo $limit ?></b>					
					</div>
					<div class="col-md-5">						
						<div class="form-group">
							<label for="">
								Tipo de informe a generar
							</label>
							<?php echo $this->Form->input('type', array( "options" => ["1" => "DATACREDITO","2"=>"PROCREDITO"] , 'class'=>'form-control','label'=>false,'div'=>false,'value'=> isset($commerce) ? $commerce : "" )) ?>							
							<?php echo $this->Form->input('limit', array( "type" => "hidden" , 'class'=>'form-control','label'=>false,'div'=>false,'value'=> $limit )) ?>							
						</div>
					</div>
					<div class="col-md-5">
						<?php echo $this->Form->input('page', array( "options" => $pages, 'class'=>'form-control','label'=>"Página de consulta",'div'=>false, )) ?>
					</div>

					<div class="col-md-2 pt-4">						
						<span class="input-group-btn">
							<button class="btn btn-warning" type="submit" id="busca">
								<?php echo __('Exportar Excel '); ?> <i class="fa fa-search"></i>
							</button>

						</span>
					</div>
					
				</div>
				<?php echo $this->Form->end(); ?>
			</div>	
		</div>		
		<?php endif ?>				
	</div>
</div>


<div class="clearfix"></div>

