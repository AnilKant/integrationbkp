<?php 
use backend\controllers\RecurringPaymentController;
?>
<div>
	<h1>Choose App from which you want to cancel recurring</h1>
	<form name="choose-app" method="get" action="list-recurring">

		<input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" />
		<input type="hidden" name="mid" value="<?= $merchant_id ?>" />
		
		<div class="form-group required">
			<label class="control-label" for="searsrecurringpayment-id">App</label>
			<select name="app" class="form-control">
				<option value="">Choose App</option>
				<?php foreach (RecurringPaymentController::$app_list as $value => $label) : ?>
					<option value="<?= $value ?>"><?= $label ?></option>
				<?php endforeach; ?>
			</select>
		</div>

		<div class="form-group">
        	<button type="submit" class="btn btn-success">Submit</button>
        </div>
	</form>
</div>