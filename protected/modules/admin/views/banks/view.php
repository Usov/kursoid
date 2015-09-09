<?php
//var_dump($bank);
//var_dump($departments);
//var_dump($rates);

?>
<h2><?=$bank->name;?></h2>
<div class="form">
<?php $form=$this->beginWidget('CActiveForm'); ?>

<?php echo $form->errorSummary($bank); ?>

<div class="row">
    <?php echo $form->label($bank,'Название'); ?>
    <?php echo $form->textField($bank,'name') ?>
</div>

<div class="row">
    <?php echo $form->label($bank,'Телефон'); ?>
    <?php echo $form->textField($bank,'phone') ?>
</div>
<!---->
<!--<div class="row rememberMe">-->
<!--    --><?php //echo $form->checkBox($bank,'rememberMe'); ?>
<!--    --><?php //echo $form->label($bank,'rememberMe'); ?>
<!--</div>-->

<div class="row submit">
    <?php echo CHtml::submitButton('Сохранить'); ?>
</div>

<?php $this->endWidget(); ?>
</div><!-- form -->
    <h3>Отделения</h3>
<?
$dataProvider = new CArrayDataProvider($bank->departments);
$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'=>$dataProvider,
    'columns'=>array(
        'address',          // display the 'title' attribute
        'phone',  // display the 'name' attribute of the 'category' relation
//        'source_id',   // display the 'content' attribute as purified HTML
//        array(            // display 'create_time' using an expression
//            'name'=>'create_time',
//            'value'=>'date("M j, Y", $data->create_time)',
//        ),
//        array(            // display 'author.username' using an expression
//            'name'=>'authorName',
//            'value'=>'$data->author->username',
//        ),
        array(            // display a column with "view", "update" and "delete" buttons
            'class'=>'CButtonColumn',
            'template'=>'{view}{delete}'
        ),
    ),
));
?>
<h3>Курсы</h3>
<?
$dataProvider = new CArrayDataProvider($bank->rates);
$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'=>$dataProvider,
    'columns'=>array(
        'currency',
        'sum',          // display the 'title' attribute
        'buy',
        'sale',
          // display the 'name' attribute of the 'category' relation
//        'source_id',   // display the 'content' attribute as purified HTML
//        array(            // display 'create_time' using an expression
//            'name'=>'create_time',
//            'value'=>'date("M j, Y", $data->create_time)',
//        ),
//        array(            // display 'author.username' using an expression
//            'name'=>'authorName',
//            'value'=>'$data->author->username',
//        ),
        array(            // display a column with "view", "update" and "delete" buttons
            'class'=>'CButtonColumn',
            'template'=>'{view}{delete}'
        ),
    ),
));
?>