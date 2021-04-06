<?php 
namespace backend\components;

use Yii;
use yii\base\Component;

class Referral extends Component
{
	public function getShopUrlFromReferrerId($referrer_id)
	{
		$query = "SELECT `user`.`username` as user_username, `referrer_user`.`username` FROM `referrer_user` LEFT JOIN `user` ON `referrer_user`.`merchant_id`=`user`.`id` WHERE `referrer_user`.`id`='{$referrer_id}'";
		$result = Data::sqlRecords($query, 'one');

		if($result){
			if($result['username'])
				return $result['username'];
			elseif($result['user_username'])
				return $result['user_username'];
		}
		return '';
	}

	public function getMerchantIdFromReferrerId($referrer_id)
	{
		$query = "SELECT `merchant_id` FROM `referrer_user` WHERE `id`='{$referrer_id}'";
		$result = Data::sqlRecords($query, 'one');
		
		if($result){
			return $result['merchant_id'];
		}
		return false;
	}

	public function getRedeemRequestCount($referrer_id)
	{
		$query = "SELECT count(`id`) as `redeem` FROM `referrer_redeem_requests` WHERE `referrer_id`='{$referrer_id}'";
		$result = Data::sqlRecords($query, 'one');
		
		if($result){
			return $result['redeem'];
		}
		return false;
	}

	public function getReferralCount($referrer_id)
	{
		$query = "SELECT count(`id`) as `referral` FROM `referral_user` WHERE `referrer_id`='{$referrer_id}'";
		$result = Data::sqlRecords($query, 'one');
		
		if($result){
			return $result['referral'];
		}
		return false;
	}
}
?>