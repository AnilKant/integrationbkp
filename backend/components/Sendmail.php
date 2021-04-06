<?php 

namespace backend\components;
use yii\base\Component;

class Sendmail extends Component
{
	
	public static function configmail($email)
	{
		$mailData = ['sender' => 'shopify@cedcommerce.com',
                    'reciever' => $email,
                    'email' => $email,
                    'subject' => "Please set jet_configuration and sale on Jet.com",
                    'bcc' => 'moattarraza@cedcoss.com,james@cedcommerce.com,kshitijverma@cedcoss.com,karshitbhargava@cedcoss.com'
                    //'bcc' => 'james@cedcommerce.com,kshitijverma@cedcoss.com,satyaprakash@cedcoss.com,abhishekjaiswal@cedcoss.com,karshitbhargava@cedcoss.com,moattarraza@cedcoss.com'
                    ];
	    $mailer = new Mail($mailData,'email/configmail.html','php',true);
	    $mailer->sendMail();
	} 
	public static function commonmail($email,$data)
	{
		$mailData = ['sender' => 'shopify@cedcommerce.com',
                    'reciever' => $email,
                    'email' => $email,
                    'subject' => "Your Subcription is going to expire for Jet-Integration app",
                    'bcc' => 'james@cedcommerce.com,kshitijverma@cedcoss.com,karshitbhargava@cedcoss.com',
                    'data' => $data,
                    ];
	    $mailer = new Mail($mailData,'email/commonmail.html','php',true);
	    $mailer->sendMail();
	} 
	
	
	public function feedback($email ,$data){

	}
	
}
?>