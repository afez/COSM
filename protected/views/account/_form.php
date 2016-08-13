<?php
/* @var $this AccountController */
/* @var $model Account */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'account-form',
	'enableAjaxValidation'=>false,
)); ?>

	   <?php echo $form->errorSummary($model, null, null, array('class' => 'alert alert-danger')) . "<br />"; ?>


	<div class="form-group col-lg-4">
		<?php echo $form->labelEx($model,'account_type'); ?>
		<?php echo $form->dropDownList($model,'account_type', Account::getAccount(), array('class'=>'form-control', 'empty'=>'--select type of account--')); ?>
	
	</div>

	<div class="form-group col-lg-4">
		<?php echo $form->labelEx($model,'account_username'); ?>
		<?php echo $form->textField($model,'account_username',array('class'=>'form-control','size'=>60,'maxlength'=>100)); ?>
		
	</div>

<div class="form-group col-lg-4">
		<?php echo $form->labelEx($model,'account_password'); ?>
		<?php echo $form->textField($model,'account_password',array('class'=>'form-control','size'=>60,'maxlength'=>100)); ?>
	
	</div>

	

	<div class="row buttons col-lg-2 pull-right" >
            <div id="facebook-jssdk"></div>
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Add' : 'Save', array('class' => 'btn btn-info')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
 <script>
            window.fbAsyncInit = function () {
                FB.init({
                    appId: '217631505301402',
                    xfbml: true,
                    version: 'v2.7'
                });
            };

            (function (d, s, id) {
                var js, fjs = d.getElementsByTagName(s)[0];
                if (!d.getElementById(id)) {
                    return;
                }
                js = d.createElement(s);
                js.id = id;
                js.src = "//connect.facebook.net/en_US/sdk.js";
                fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));
        </script>