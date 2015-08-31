<h1>Авторизация</h1>

<?=CHtml::form(); ?>
<?=CHtml::errorSummary($form); ?><br>

<table id="form2" border="0" width="400" cellpadding="10" cellspacing="10">
    <tr>
        <td width="150"><?=CHtml::activeLabel($form, 'login'); ?></td>
        <td><?=CHtml::activeTextField($form, 'login') ?></td>
    </tr>
    <tr>
        <td><?=CHtml::activeLabel($form, 'passwd'); ?></td>
        <td><?=CHtml::activePasswordField($form, 'passwd') ?></td>
    <tr>
    <tr>
        <td><?php $this->widget('CCaptcha', array('buttonLabel' => '<br>[новый код]')); ?></td>
        <td><?=CHtml::activeTextField($form,'verifyCode'); ?></td>
    </tr>
    <tr>
        <td></td>
        <td><?=CHtml::submitButton('Войти', array('id' => "submit")); ?></td>
    </tr>
</table>

<?=CHtml::endForm(); ?>