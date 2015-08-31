<?php
$dataProvider = new CArrayDataProvider($banks);
$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'=>$dataProvider,
    'columns'=>array(
        'name',          // display the 'title' attribute
        'phone',  // display the 'name' attribute of the 'category' relation
        'source_id',   // display the 'content' attribute as purified HTML
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