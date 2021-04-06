<?php 
namespace frontend\components;

use Yii;
use yii\base\Component;
use common\models\User;
use app\models\AppStatus;
use frontend\components\Data;

class Walmartappdetails extends Component
{	
	public static function isValidateapp($merchant_id, $connection=null)
	{
        try
        {
	        $expdate="";
	        $query="";
	        $model="";
	        $queryObj="";
	        $query = "Select merchant_id,expire_date,status from walmart_extension_detail where merchant_id='".$merchant_id."'";
	        
	        if($connection){
	        	$queryObj = $connection->createCommand($query);
	        	$model = $queryObj->queryOne();
	    	} else {
	    		$model = Data::sqlRecords($query, 'one');
	    	}
	        
			if($model)
	        {
	        	$expdate=strtotime($model['expire_date']);
	        	if(time()>$expdate)
	        	{
	        		if($model['status']=="Trial Expired")
	        		{
	        			return "not purchase";
	        		}
	        		elseif($model['status']=="License Expired")
	        		{
	        			return "expire";
	        		}
			        elseif($model['status']=="Not Purchase")
			        {
			        	$sql="";
			        	$result="";
			        	$sql="UPDATE `jet_extension_detail` SET status='Trial Expired' where merchant_id='".$merchant_id."'";
			        	
			        	if($connection)
			        		$result = $connection->createCommand($sql)->execute();
			        	else
			        		$result = Data::sqlRecords($sql, null, 'update');

	        			/*$model->status="Trial Expired";
	        			$model->save(false);*/
	        			return "not purchase";
			        }
			      	elseif($model['status']=="Purchased")
			      	{
			      		$sql="";
			      		$result="";
			      		$sql="UPDATE `jet_extension_detail` SET status='License Expired' where merchant_id='".$merchant_id."'";
			      		
			      		if($connection)
			      			$result = $connection->createCommand($sql)->execute();
			      		else
			      			$result = Data::sqlRecords($sql, null, 'update');

	      				/*$model->status="License Expired";
	      				$model->save(false);*/
	      				return "expire";
			      	}
	        	}
	        }
        }
        catch(Exception $e)
   		{
        	//exit(0);
        	return "";
        }   	 
	}

	public static function appstatus($shop,$connection=null){
		$query="";
		$model="";
		$queryObj="";
		$query = "Select status from app_status where shop='".$shop."'";

		if($connection){
			$queryObj = $connection->createCommand($query);
			$model = $queryObj->queryOne();
		} else {
			$model = Data::sqlRecords($query, 'one');
		}

		if($model){
			if($model['status']==0)
				return false;
		}
		return true;
	}

	public static function getConnection()
	{
		$username = 'cedcom5_Mx42Qt';
		$password = 'VW-88yVH0]ws';
		try
		{
			$connection = new \yii\db\Connection([
		
					'dsn' => 'mysql:host=127.0.0.1;dbname=cedcom5_Mx42Qt',
					'username' => $username,
					'password' => $password,
					//'charset' => 'utf8',
					]);
			//$connection->open();
			return $connection;
		}
		catch(\yii\db\Exception $e){
			return "connection failed";
		}
	
	}

	public function autologin()
	{
		$merchant_id= \Yii::$app->user->identity->id;
		$url="";
		$shop="";
		$model="";
		$model1="";
		if(isset($_SERVER['HTTP_REFERER']))
		{
			$hostname = Yii::getAlias('@hostname').'/';
			$url=parse_url($_SERVER['HTTP_REFERER']);
			if(isset($url['host']) && $url['host']!=$hostname)
			{
				$shop=$url['host'];
				$model=User::find()->where(['username'=>$shop])->one();
				if($model)
				{
					return $shop;
				}
			}
		}
		//}
	}

	public static function appstatus1($id){
		$model="";
		$usermodel="";
		$usermodel=User::findOne($id);
		$model=AppStatus::find()->where(["shop"=>$usermodel->username])->one();
		if($model){
			if($model->status==0)
				return false;
		}
		return true;
	}
}
?>