<?php if($action == 'add') { ?>
<div class="page-title">
	<div class="title_left">
		<h3><?php printf("<?php echo __('%s').' '.__('%s'); ?>", 'Adicionar', $singularHumanName); ?></h3>
	</div>

	<div class="title_right">
		<div class="col-md-5 col-sm-5  form-group pull-right top_search">
			<a class="btn btn-sm btn-default shiny green"  href="<?php echo "<?php echo \$this->Html->url(array('action'=>'index'));?>"?>">
            <i class="glyphicon glyphicon-th-list"></i>
            <?php echo "<?php echo __('Listar'); ?>" ?>
        </a>
		</div>
	</div>
</div>
<div class="clearfix"></div>
<div class="row">
	<div class="col-md-12 col-sm-12 ">
		<div class="x_panel">
			<div class="x_content">
				<br />
				<?php  echo "<?php echo \$this->Form->create('{$modelClass}', array('role' => 'form','data-parsley-validate=\"\"','class'=>'form-horizontal form-label-left')); ?>\n"; ?>

							<?php		
								foreach ($fields as $field) {
									if(in_array($field , array('state'))) continue;
									if (strpos($action, 'add') !== false && $field == $primaryKey) {
										continue;
									} elseif (!in_array($field, array('created', 'modified', 'updated'))) {
										$label = ucfirst(Inflector::humanize(strtolower($field)));
										$rest = substr($label, -2);
										if($rest == 'Id') {
											$label = substr($label, 0, -3);
										}
										echo "\t\t\t\t\t\t<div class='item form-group'>\n";
										if($field != $primaryKey) 
										echo "\t\t\t\t\t\t\t\t<?php echo \$this->Form->label('{$modelClass}.{$field}',__('$label'), array('class'=>'col-form-label col-md-3 col-sm-3 label-align'));?>\n";
										echo "\t\t\t\t\t\t\t\t<div class='col-md-6 col-sm-6'>\n";
										echo "\t\t\t\t\t\t\t\t<?php echo \$this->Form->input('{$field}', array('class' => 'form-control border-input','label'=>false,'div'=>false)); ?>\n";
										echo "\t\t\t\t\t\t\t\t</div>\n";
										echo "\t\t\t\t\t\t</div>\n";
									}
								}
								if (!empty($associations['hasAndBelongsToMany'])) {
									foreach ($associations['hasAndBelongsToMany'] as $assocName => $assocData) {
										$label = ucfirst(Inflector::humanize(strtolower($assocName)));
										echo "\t\t\t\t\t\t<div class='item form-group'>\n";
										echo "\t\t\t\t\t\t\t\t<?php echo \$this->Form->label('',__('$label'), array('class'=>'col-form-label col-md-3 col-sm-3 label-align'));?>\n";
										echo "\t\t\t\t\t\t\t\t<div class='col-md-6 col-sm-6'>\n";

										echo "\t\t\t\t\t\t\t\t<?php echo \$this->Form->input('{$assocName}',array('class' => 'form-control border-input','label'=>false,'div'=>false));?>\n";
										echo "\t\t\t\t\t\t\t\t</div>\n";

										echo "\t\t\t\t\t\t</div>\n";
									}
								}
							?>
							<div class="ln_solid"></div>
							<div class="item form-group">
								<div class="col-md-6 col-sm-6 offset-md-4">
									<button type="submit" class="btn btn-success"><?php echo __('Guardar'); ?></button>
								</div>								
							</div>
				<?php echo "<?php echo \$this->Form->end(); ?>\n";?>
			</div>
		</div>
	</div>
</div>
<?php } ?>



<?php if($action == 'edit') { ?>
<div class="page-title">
	<div class="title_left">
		<h3><?php printf("<?php echo __('%s').' '.__('%s'); ?>", 'Editar', $singularHumanName); ?></h3>
	</div>

	<div class="title_right">
		<div class="col-md-8 col-sm-8  form-group pull-right top_search">
			<a class="btn btn-sm btn-fill btn-success"  href="<?php echo "<?php echo \$this->Html->url(array('action'=>'index'));?>" ?>">
		        <i class="fa fa-list-alt"></i>
		        <?php echo "<?php echo __('Listar'); ?>\n"; ?>
		    </a>

		    <a class="btn btn-sm btn-fill btn-info" href="<?php echo "<?php echo \$this->Html->url(array('action'=>'view',\$this->Utilidades->encrypt(\$this->request->data['{$modelClass}']['id'])));?>" ?>">
		        <i class="fa fa-eye"></i>
		        <?php echo "<?php echo __('Ver'); ?>\n"; ?>
		    </a>

		    <a class="btn btn-sm btn-fill btn-warning"  href="<?php echo "<?php echo \$this->Html->url(array('action'=>'add'));?>" ?>">
		        <i class="fa fa-plus-circle"></i>
		        <?php echo "<?php echo __('Adicionar'); ?>\n"; ?>
		    </a>

		</div>
	</div>
</div>
<div class="clearfix"></div>

<div class="row">
	<div class="col-md-12 col-sm-12 ">
		<div class="x_panel">
			<div class="x_content">
				<br />
				<?php  echo "<?php echo \$this->Form->create('{$modelClass}', array('role' => 'form','data-parsley-validate=\"\"','class'=>'form-horizontal form-label-left')); ?>\n"; ?>
							<?php
								foreach ($fields as $field) {
										if(in_array($field , array('state'))) continue;
										$label = ucfirst(Inflector::humanize(strtolower($field)));
										$rest = substr($label, -2);
										if($rest == 'Id') {
											$label = substr($label, 0, -3);
										}
										$divHide = '';
										if($field == 'id') {
											$divHide = 'style="display:none;"';
										}
										if (strpos($action, 'add') !== false && $field == $primaryKey) {
											continue;
										} elseif (!in_array($field, array('created', 'modified', 'updated'))) {
											echo "\t\t\t\t\t\t<div class='item form-group' $divHide>\n";
											if($field != $primaryKey) echo "\t\t\t\t\t\t\t<?php echo \$this->Form->label('{$modelClass}.{$field}',__('$label'), array('class'=>'col-form-label col-md-3 col-sm-3 label-align'));?>\n";
											echo "\t\t\t\t\t\t\t\t<div class='col-md-6 col-sm-6'>\n";
											echo "\t\t\t\t\t\t\t<?php echo \$this->Form->input('{$field}', array('class' => 'form-control border-input', 'label'=>false,'div'=>false)); ?>\n";
											echo "\t\t\t\t\t\t\t\t</div>\n";
											echo "\t\t\t\t\t\t</div>\n";
										}
									}
									if (!empty($associations['hasAndBelongsToMany'])) {
										foreach ($associations['hasAndBelongsToMany'] as $assocName => $assocData) {
											$label = ucfirst(Inflector::humanize(strtolower($assocName)));
											echo "\t\t\t\t\t\t<div class='item form-group'>\n";
											echo "\t\t\t\t\t\t\t<?php echo \$this->Form->label('',__('$label'), array('class'=>'col-form-label col-md-3 col-sm-3 label-align'));?>\n";
											echo "\t\t\t\t\t\t\t\t<div class='col-md-6 col-sm-6'>\n";
											echo "\t\t\t\t\t\t\t<?php echo \$this->Form->input('{$assocName}',array('class' => 'form-control border-input', 'label'=>false,'div'=>false));?>\n";
											echo "\t\t\t\t\t\t\t\t</div>\n";
											echo "\t\t\t\t\t\t</div>\n";
										}
									}
								?>
	            				<div class="ln_solid"></div>
									<div class="item form-group">
										<div class="col-md-6 col-sm-6 offset-md-4">
											<button type="submit" class="btn btn-success"><?php echo __('Guardar'); ?></button>
										</div>								
									</div>
				<?php echo "<?php echo \$this->Form->end(); ?>\n";?>
			</div>
		</div>
	</div>
</div>
<?php } ?>