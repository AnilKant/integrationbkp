<h1>Recurring Payment List</h1>
<div class="grid-view">
<?php
	if(!empty($payments))
	{
		$arr = current($payments);
		$headers = array_keys($arr);
?>
		<table class="table table-striped table-bordered">
			<thead>
				<tr>

			<?php foreach ($headers as $header) { ?>
					<th><?= $header ?></th>
			<?php } ?>

					<th>Actions</th>
				</tr>
			</thead>
			<tbody>

			<?php foreach ($payments as $payment) { ?>
				<tr>

				<?php foreach ($payment as $value) { ?>
					<td><?= $value ?></td>
				<?php } ?>

					<td>
					<?php if($payment['status'] === 'active') { ?>
						<a onclick="confirmCancel(<?= $payment['id'] ?>)">cancel</a>		
					<?php } ?>
					</td>
				</tr>
			<?php } ?>

			</tbody>				
		</table>
<?php
	}
	else
	{
?>
		<p>No Records found.</p>
<?php
	}
?>
</div>

<script type="text/javascript">
	function confirmCancel(payment_id)
	{
		var r = confirm("Are you sure want to cancel this recurring?");
		if (r == true) {
			var s = confirm("Are you absolutely sure want to cancel this recurring?");
			if (s == true) {
				window.location.href = './cancel-payment?pid=' + payment_id + '&mid=<?= $merchant_id ?>&app=<?= $app ?>';
			}
		}
	}
</script>