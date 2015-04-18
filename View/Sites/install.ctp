<?php

    echo $this->Html->script('/mt_sites/js/jstz.min', true);

    echo $this->Form->create('Site');
?>
    <div class="install">
        <h2><?php echo __d('install', 'Crear Nuevo Comercio'); ?></h2>

        <table>
            <tr>
                <td>
                <?php

                echo $this->Form->hidden('timezone', array('id'=>'timezone'));


                echo $this->Form->input('name', array(
                    'label' => __d('install', 'Nombre de Fantasía'),
                    'placeholder' => __d('install', 'Ej: Mc Burger'),

                ));               
                ?>
            </td>

            </tr>

        <tr>
            <td>
                <?php
                echo $this->Form->input('type', array(
                        'options' => array(
                            'restaurante' => 'Restaurante',
                            'hotel' => 'Hotel',
                            'generic' => 'Generic'
                        ),
                        'type' => 'radio',
                        'legend' => false,
                        'label' => false,
                        'default' => 'restaurante',
                    )
                );
                ?>
            </td>
        </tr>
        </table>
    </div>
    <div class="form-actions">
        <?php echo $this->Form->submit(__d('install', 'Guardar'), array('class' => 'btn btn-success', 'div' => false)); ?>
        <?php echo $this->Html->link(__d('install', 'Cancelar'), '/', array( 'class' => 'btn cancel')); ?>
    </div>

<?php echo $this->Form->end(); ?>

<script type="text/javascript">
         var tz = jstz.determine();
         tz.name();
         $('#timezone').val( tz.name() );
</script>