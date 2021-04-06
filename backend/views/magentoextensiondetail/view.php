<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\MagentoExtensionDetail */

$this->title = $_GET['id'];
$this->params['breadcrumbs'][] = ['label' => 'Magento Extension Details', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="magento-extension-detail-view">

    <h1><?= Html::encode("Current weak details for : ".$this->title) ?></h1>  
    <table class="table table-striped table-bordered" cellspacing="0" width="100%">
    	<thead>
    		<tr>
    			<th>Total Products </th>
    			<th>Published</th>
    			<th>Unpublished</th>
    			<th>Total Order</th>
    			<th>Orders Completed</th>
    			<th>Revenue</th>
    			<th>Last Updated</th>
    		</tr>
    	</thead>
    	<tbody>
			<?php
				foreach ($model as $key => $value) 
				{
					$data = json_decode($value['details'],true);
					?>
						<tr>
							<td><?=$data['totalProducts'] ?></td>
							<td><?=$data['totalPublishedProducts'] ?></td>
							<td><?=$data['totalUnpublishedProducts'] ?></td>
							<td><?=$data['totalOrderCount'] ?></td>
							<td><?=$data['orderCountComplete'] ?></td>
							<td><?=$data['totalRevenue'] ?></td>
							<td><?=$value['last_updated'] ?></td>
						</tr>
					<?php
				}
			?>    		
    	</tbody>
    </table>  
</div>
