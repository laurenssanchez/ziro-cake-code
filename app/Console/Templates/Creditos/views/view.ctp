<div class="page-title">
    <div class="title_left">
        <h3><?php printf("<?php echo __('%s').' '.__('%s'); ?>", 'Visualizando', $singularHumanName); ?></h3>
    </div>

    <div class="title_right">
        <div class="col-md-8 col-sm-8  form-group pull-right top_search">
            <a class="btn btn-sm btn-fill btn-success"  href="<?php echo "<?php echo \$this->Html->url(array('action'=>'index'));?>" ?>">
                <i class="fa fa-list-alt"></i>
                <?php echo "<?php echo __('Listar'); ?>\n"; ?>
            </a>

            <a class="btn btn-sm btn-fill btn-info" href="<?php echo "<?php echo \$this->Html->url(array('action'=>'edit',\$this->Utilidades->encrypt(\${$singularVar}['{$modelClass}']['id'])));?>" ?>">
                <i class="fa fa-edit"></i>
                <?php echo "<?php echo __('Editar'); ?>\n"; ?>
            </a>

            <a class="btn btn-sm btn-fill btn-warning"  href="<?php echo "<?php echo \$this->Html->url(array('action'=>'add'));?>" ?>">
                <i class="fa fa-plus-circle"></i>
                <?php echo "<?php echo __('Adicionar'); ?>\n"; ?>
            </a>
            <a rel="tooltip" href="<?php echo "<?php echo \$this->Html->url(array('action' => 'delete',\$this->Utilidades->encrypt(\${$singularVar}['{$modelClass}']['id']))); ?>" ?>" class="btn btn-danger btn-sm changeState">
                <?php echo "<?php if(\${$singularVar}['{$modelClass}']['state'] == 1): ?>" ?>
                    <i class="fa fa-times-circle-o"></i> Deshabilitar
                <?php echo "<?php  else: ?>"  ?> 
                    <i class="fa fa-check-circle"></i> Habilitar
                <?php echo " <?php endif;  ?>" ?>                                      
            </a>
        </div>
    </div>
</div>

<div class="clearfix"></div>
    <div class="row">
        <div class="col-md-12 col-sm-12 ">
            <div class="x_panel">
                <div class="table-responsive">
                    <table class="table table-condensed"><?php echo "\n"; ?>
                        <tbody><?php echo "\n"; ?>
                            <?php
                                foreach ($fields as $field) {
                                    if(in_array($field, array('id','created','modified'))){
                                        continue;
                                    } 
                                    $isKey = false;
                                    if (!empty($associations['belongsTo'])) {
                                        foreach ($associations['belongsTo'] as $alias => $details) {
                                            if ($field === $details['foreignKey']) {
                                                $label = ucfirst(strtolower(Inflector::humanize($alias)));
                                                $isKey = true;
                                                echo "\n\t\t\t\t\t\t\t\t\t<tr>\n";
                                                echo "\t\t\t\t\t\t\t\t\t\t<td><?php echo __('$label'); ?></td>\n";
                                                echo "\t\t\t\t\t\t\t\t\t\t<td>\n\t\t\t\t\t\t\t\t\t\t\t<?php echo \$this->Html->link(\${$singularVar}['{$alias}']['{$details['displayField']}'], array('controller' => '{$details['controller']}', 'action' => 'view', \${$singularVar}['{$alias}']['{$details['primaryKey']}']), array('class' => '')); ?>&nbsp;\n\t\t\t\t\t\t\t\t\t\t</td>\n";
                                                echo "\t\t\t\t\t\t\t\t\t</tr>\n";
                                                break;
                                            }
                                        }
                                    }
                                    if ($isKey !== true) {
                                        $label = ucfirst(strtolower(Inflector::humanize($field)));
                                        echo "\n\t\t\t\t\t\t\t\t\t<tr>\n";
                                        echo "\t\t\t\t\t\t\t\t\t\t<td><?php echo __('$label'); ?></td>\n";
                                        if($field == "state"){
                                            echo "\t\t\t\t\t\t\t\t<td> <?php echo \${$singularVar}['{$modelClass}']['{$field}'] == 1 ? __('Activo') : __('Inactivo') ;?> </td>";
                                        }else{
                                            
                                        echo "\t\t\t\t\t\t\t\t\t\t<td>\n\t\t\t\t\t\t\t\t\t\t\t<?php echo h(\${$singularVar}['{$modelClass}']['{$field}']); ?>&nbsp;\n\t\t\t\t\t\t\t\t\t\t</td>\n";
                                        }
                                        echo "\t\t\t\t\t\t\t\t\t</tr>\n";
                                    }
                                }
                                echo "\n";
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<?php
if (!empty($associations['hasOne'])) :
    foreach ($associations['hasOne'] as $alias => $details): ?>
    <div class="related">
        <h3><?php echo "<?php echo __('Related " . Inflector::humanize($details['controller']) . "'); ?>"; ?></h3>
    <?php echo "<?php if (!empty(\${$singularVar}['{$alias}'])): ?>\n"; ?>
        <dl>
    <?php
            foreach ($details['fields'] as $field) {
                echo "\t\t<dt><?php echo __('" . Inflector::humanize($field) . "'); ?></dt>\n";
                echo "\t\t<dd>\n\t<?php echo \${$singularVar}['{$alias}']['{$field}']; ?>\n&nbsp;</dd>\n";
            }
    ?>
        </dl>
    <?php echo "<?php endif; ?>\n"; ?>
        <div class="actions">
            <ul>
                <li><?php echo "<?php echo \$this->Html->link(__('Edit " . Inflector::humanize(Inflector::underscore($alias)) . "'), array('controller' => '{$details['controller']}', 'action' => 'edit', \${$singularVar}['{$alias}']['{$details['primaryKey']}'])); ?></li>\n"; ?>
            </ul>
        </div>
    </div>
    <?php
    endforeach;
endif;
if (empty($associations['hasMany'])) {
    $associations['hasMany'] = array();
}
if (empty($associations['hasAndBelongsToMany'])) {
    $associations['hasAndBelongsToMany'] = array();
}
$relations = array_merge($associations['hasMany'], $associations['hasAndBelongsToMany']);
foreach ($relations as $alias => $details):
    $otherSingularVar = Inflector::variable($alias);
    $otherPluralHumanName = Inflector::humanize($details['controller']);
    ?>
<div class="related">
    <h3><?php echo "<?php echo __('Related " . $otherPluralHumanName . "'); ?>"; ?></h3>
    <?php echo "<?php if (!empty(\${$singularVar}['{$alias}'])): ?>\n"; ?>
    <table cellpadding = "0" cellspacing = "0">
    <tr>
<?php
            foreach ($details['fields'] as $field) {
                echo "\t\t<th><?php echo __('" . Inflector::humanize($field) . "'); ?></th>\n";
            }
?>
        <th class="actions"><?php echo "<?php echo __('Actions'); ?>"; ?></th>
    </tr>
<?php
echo "\t<?php foreach (\${$singularVar}['{$alias}'] as \${$otherSingularVar}): ?>\n";
        echo "\t\t<tr>\n";
            foreach ($details['fields'] as $field) {
                echo "\t\t\t<td><?php echo \${$otherSingularVar}['{$field}']; ?></td>\n";
            }

            echo "\t\t\t<td class=\"actions\">\n";
            echo "\t\t\t\t<?php echo \$this->Html->link(__('View'), array('controller' => '{$details['controller']}', 'action' => 'view', \${$otherSingularVar}['{$details['primaryKey']}'])); ?>\n";
            echo "\t\t\t\t<?php echo \$this->Html->link(__('Edit'), array('controller' => '{$details['controller']}', 'action' => 'edit', \${$otherSingularVar}['{$details['primaryKey']}'])); ?>\n";
            echo "\t\t\t\t<?php echo \$this->Form->postLink(__('Delete'), array('controller' => '{$details['controller']}', 'action' => 'delete', \${$otherSingularVar}['{$details['primaryKey']}']), array('confirm' => __('Are you sure you want to delete # %s?', \${$otherSingularVar}['{$details['primaryKey']}']))); ?>\n";
            echo "\t\t\t</td>\n";
        echo "\t\t</tr>\n";

echo "\t<?php endforeach; ?>\n";
?>
    </table>
<?php echo "<?php endif; ?>\n\n"; ?>
    <div class="actions">
        <ul>
            <li><?php echo "<?php echo \$this->Html->link(__('New " . Inflector::humanize(Inflector::underscore($alias)) . "'), array('controller' => '{$details['controller']}', 'action' => 'add')); ?>"; ?> </li>
        </ul>
    </div>
</div>
<?php
endforeach;
?>
