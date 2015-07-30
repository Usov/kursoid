<?php
$this->pageTitle=Yii::app()->name . ' - Contact Us';
$this->breadcrumbs=array(
	'Contact',
);
?>

<h1>Contact Us</h1>

<?php if(Yii::app()->user->hasFlash('contact')): ?>

<div class="flash-success">
	<?php echo Yii::app()->user->getFlash('contact'); ?>
</div>

<?php else: ?>

<p>
If you have business inquiries or other questions, please fill out the following form to contact us. Thank you.
</p>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm'); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'email'); ?>
		<?php echo $form->textField($model,'email'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'subject'); ?>
		<?php echo $form->textField($model,'subject',array('size'=>60,'maxlength'=>128)); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'body'); ?>
		<?php echo $form->textArea($model,'body',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<?php if(CCaptcha::checkRequirements()): ?>
	<div class="row">
		<?php echo $form->labelEx($model,'verifyCode'); ?>
		<div>
		<?php $this->widget('CCaptcha'); ?>
		<?php echo $form->textField($model,'verifyCode'); ?>
		</div>
		<div class="hint">Please enter the letters as they are shown in the image above.
		<br/>Letters are not case-sensitive.</div>
	</div>
	<?php endif; ?>

	<div class="row submit">
		<?php echo CHtml::submitButton('Submit'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->

<?php endif; ?>
<script>
    for (var i in coordinates) {
        var bankId = departments[i].bankId;
        for (var currency in rates[bankId]) {
            for (var typeId in ['buy', 'sale']) {

                var type = ['buy', 'sale'][typeId],
                    rateString = rates[bankId][currency][0][type];

                if (!_.isNaN(parseFloat(rateString))) {
                    var rate = parseFloat(rateString).toFixed(2);
                }

                if (rate) {
                    console.warn('createPlacemark');
                    var placeMark = new ymaps.GeoObject({
                        geometry: {
                            type: "Point",
                            coordinates: coordinates[i]
                        },
                        properties: {
                            rate: rate,
                            id: i,
                        }
                    }, {
                        iconContentLayout: ymaps.templateLayoutFactory.createClass(
                            '{{properties.rate}}'
                        ),
                        preset: 'islands#blueStretchyIcon'
                    });

                    (function (p) {
                        p.events.add('click', function () {
                            Application.showBankDetails({id: p.properties.get('id')});
                        });
                    })(placeMark);
                    window.placeMarkCollection[currency][type].push(placeMark);
                }
            }
        }
    }