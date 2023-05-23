<div class="page-title">
    <div class="title_left">
        <h3><?php echo __('Visualizando').' '.__('Simulator'); ?></h3>
    </div>

    <div class="title_right">
        <div class="col-md-8 col-sm-8  form-group pull-right top_search">
            <a class="btn btn-sm btn-fill btn-success"  href="<?php echo $this->Html->url(array('action'=>'index'));?>">
                <i class="fa fa-list-alt"></i>
                <?php echo __('Listar'); ?>
            </a>

            <a class="btn btn-sm btn-fill btn-info" href="<?php echo $this->Html->url(array('action'=>'edit',$this->Utilidades->encrypt($simulator['Simulator']['id'])));?>">
                <i class="fa fa-edit"></i>
                <?php echo __('Editar'); ?>
            </a>

            <a class="btn btn-sm btn-fill btn-warning"  href="<?php echo $this->Html->url(array('action'=>'add'));?>">
                <i class="fa fa-plus-circle"></i>
                <?php echo __('Adicionar'); ?>
            </a>
            <a rel="tooltip" href="<?php echo $this->Html->url(array('action' => 'delete',$this->Utilidades->encrypt($simulator['Simulator']['id']))); ?>" class="btn btn-danger btn-sm changeState">
                <?php if($simulator['Simulator']['state'] == 1): ?>                    <i class="fa fa-times-circle-o"></i> Deshabilitar
                <?php  else: ?>
                    <i class="fa fa-check-circle"></i> Habilitar
                 <?php endif;  ?>
            </a>
        </div>
    </div>
</div>

<div class="clearfix"></div>
    <div class="row">
        <div class="col-md-12 col-sm-12 ">
            <div class="x_panel">
                <div class="table-responsive">
                    <table class="table table-condensed">
                        <tbody>

									<tr>
										<td><?php echo __('Nombre'); ?></td>
										<td>
											<?php echo h($simulator['Simulator']['name']); ?>&nbsp;
										</td>
									</tr>

									<tr>
										<td><?php echo __('Código de proveedor'); ?></td>
										<td>
											<?php echo h($simulator['Simulator']['commerce_code']); ?>&nbsp;
										</td>
									</tr>

									<tr>
										<td><?php echo __('Línea de crédito'); ?></td>
										<td>
											<?php echo $this->Html->link($simulator['CreditsLine']['name'], array('controller' => 'credits_lines', 'action' => 'view', $simulator['CreditsLine']['id']), array('class' => '')); ?>&nbsp;
										</td>
									</tr>

									<tr>
										<td><?php echo __('Website'); ?></td>
										<td>
											<?php echo h($simulator['Simulator']['website']); ?>&nbsp;
										</td>
									</tr>

									<tr>
										<td><?php echo __('Estado'); ?></td>
								<td> <?php echo $simulator['Simulator']['state'] == 1 ? __('Activo') : __('Inactivo') ;?> </td>									</tr>
								<tr>
									<td>
										Código a generar
									</td>
									<td>
										<code class="text-body">
											&lt;!-- Contenedor del simulador (Incrustarlo dondé irá) --&gt;
											<br>
											&lt;div class='container' id='LtD_SmLTcrSp'&gt; &lt;/div&gt;
											<br>
											&lt;!-- Fin Contenedor del simulador--&gt;
											<br>
											<br>
											<br>
											&lt;!-- Simulator al final del documento --&gt;
											<br>
											&lt;script&gt;
												<br>
												&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;var _ltd = '<?php echo base64_encode($this->Utilidades->encrypt($simulator['Simulator']['id'])) ?>';
												<br>
												&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;document.addEventListener("DOMContentLoaded", function(event) { <br>
												    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;
												    var ifrm = document.createElement("iframe"); <br>
												    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;
											        ifrm.setAttribute("src", "<?php echo Router::url("/",true) ?>simulator/"+_ltd); <br>
											        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;
											        ifrm.style.width = "100%";<br>
											        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;
											        ifrm.style.height = "600px"; <br>
											        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;
											        document.getElementById("LtD_SmLTcrSp").appendChild(ifrm);<br>
												&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;});
												<br>
											&lt;/script&gt;
											<br>
											&lt;!-- End Simulator --&gt;
										</code>
									</td>
								</tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
