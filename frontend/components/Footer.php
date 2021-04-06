<?php
namespace frontend\components;


use Yii;
use yii\base\Component;

class Footer extends Component
{

	public static function footerArray()
	{
		$module=Yii::$app->controller->module->id;
	$data['popular_apps'] =  [

					'magenative'=>[
									'label'=>'Magenative',
									//'src'=>yii::$app->request->baseUrl.'/static/modules/pricefalls/assets/images/footer-icon/maginative.png',
									'href'=>'https://apps.shopify.com/magenative-app'
								],
						'walmart'=>[
									'label'=>'Walmart',
									//'src'=>yii::$app->request->baseUrl.'/static/modules/pricefalls/assets/images/footer-icon/walmart-app.png',
									'href'=>'https://apps.shopify.com/walmart-marketplace-integration'
								],
								'jet'=>[
									'label'=>'Jet',
									//'src'=>yii::$app->request->baseUrl.'/static/modules/pricefalls/assets/images/footer-icon/jet-app.png',
									'href'=>'https://apps.shopify.com/jet-integration'
								],
								
								'sears'=>[
									'label'=>'Sears',
									//'src'=>yii::$app->request->baseUrl.'/static/modules/pricefalls/assets/images/footer-icon/sears-app.png',
									'href'=>'https://apps.shopify.com/sears-marketplace-integration'
								]
								// ,
								// 'newegg'=>[
								// 	'label'=>'NEWEGG',
								// 	'src'=>yii::$app->request->baseUrl.'/static/modules/pricefalls/assets/images/newegg-app.png',
								// 	'href'=>'https://apps.shopify.com/newegg-marketplace-integration'
								// ],
							
								// // 'bonanza'=>[
								// // 	'label'=>'BONANZA',
								// // 	'src'=>yii::$app->request->baseUrl.'/static/modules/pricefalls/assets/images/bonanza.png',
								// // 	'href'=>'https://apps.shopify.com/newegg-marketplace-integration'
								// // ],
								// 'fruugo'=>[
								// 	'label'=>'FRUUGO',
								// 	'src'=>yii::$app->request->baseUrl.'/static/modules/pricefalls/assets/images/fruugo.png',
								// 	'href'=>'https://integration.cedcommerce.com/fruugo'
								// ],
								// 'tophatter'=>[
								// 	'label'=>'TOPHATTER',
								// 	'src'=>yii::$app->request->baseUrl.'/static/modules/pricefalls/assets/images/tophatters.png',
								// 	'href'=>'https://integration.cedcommerce.com/tophatter'
								// ]
						];
$data['partners']=[
						'walmart'=>[
									'label'=>'Walmart',
									'src'=>yii::$app->request->baseUrl.'/images/footer-icon/walmart-logo-png-1.png',
									//'href'=>'https://www.walmart.com'
									'href' => 'https://marketplace.walmart.com/knowledgebase/articles/Article/Integration-Methods-Channel-Partner'
								],

								/*'jet'=>[
									'label'=>'Jet',
									'src'=>yii::$app->request->baseUrl.'/static/modules/pricefalls/assets/images/footer-icon/jet-logo.png',
									'href'=>'https://www.jet.com'
								],*/
								'best buy'=>[
									'label'=>'best buy',
									'src'=>yii::$app->request->baseUrl.'/images/footer-icon/best_buy.png',
									'href'=>'https://www.bestbuy.com'
								],
					
								'sears'=>[
									'label'=>'Sears',
									'src'=>yii::$app->request->baseUrl.'/images/footer-icon/sears-logo.png',
									'href'=>'https://www.searscommerceservices.com/preferred-partners/?lipi=urn:li:page:d_flagship3_messaging%3BXiHtVa33QHmO4dbNxn8VlQ%3D%3D'
								],
								'newegg-canada'=>[
									'label'=>'NewEgg Ca',
									'src'=>yii::$app->request->baseUrl.'/images/footer-icon/newegg1.png',
									'href'=>'https://www.newegg.com/sellers/index.php/integration-providers/#Ced'
								],
								/*'newegg-us'=>[
									'label'=>'NewEgg US',
									'src'=>yii::$app->request->baseUrl.'/static/modules/pricefalls/assets/images/footer-icon/newegg1.png',
									'href'=>'https://www.newegg.com'
								]
								,*/


								/*'pricefalls'=>[

									'label'=>'Pricefalls',
									'src'=>yii::$app->request->baseUrl.'/images/footer-icon/pricefalls.png',
									'href'=>'https://www.pricefalls.com'
								],*/
									'fruugo'=>[
									'label'=>'Fruugo',
									'src'=>yii::$app->request->baseUrl.'/images/footer-icon/fruugo.png',
									'href'=>'https://fruugo.atlassian.net/wiki/spaces/RR/pages/94568449/CedCommerce'
								],
								'tophatter'=>[
									'label'=>'Tophatter',
									'src'=>yii::$app->request->baseUrl.'/images/footer-icon/tophatters.png',
									'href'=>'https://tophatter.com/sellers/onboarding/start'
								]
									];

$data['integrations']=[
						'walmart'=>[
									'label'=>'Walmart',
									//'src'=>yii::$app->request->baseUrl.'/static/modules/pricefalls/assets/images/footer-icon/shopify-walmart.png',
									'href'=>'https://apps.shopify.com/walmart-marketplace-integration'
								],
								'jet'=>[
									'label'=>'Jet',
									//'src'=>yii::$app->request->baseUrl.'/static/modules/pricefalls/assets/images/footer-icon/jet-shopify_1.png',
									'href'=>'https://apps.shopify.com/jet-integration'
								],
								'sears'=>[
									'label'=>'Sears',
									//'src'=>yii::$app->request->baseUrl.'/static/modules/pricefalls/assets/images/footer-icon/sears-shopify-integration.png',
									'href'=>'https://apps.shopify.com/sears-marketplace-integration'
								],
								'newegg-canada'=>[
									'label'=>'NewEgg-Canada',
									//'src'=>yii::$app->request->baseUrl.'/static/modules/pricefalls/assets/images/footer-icon/newegg-shopify.png',
									'href'=>'https://apps.shopify.com/newegg-marketplace-integration'
								],
									'newegg-us'=>[
									'label'=>'NewEgg-US',
									//'src'=>yii::$app->request->baseUrl.'/static/modules/pricefalls/assets/images/footer-icon/newegg-shopify.png',
									'href'=>'https://apps.shopify.com/newegg-marketplace-integration'
								],
									'pricefalls'=>[
									'label'=>'Pricefalls',
									//'src'=>yii::$app->request->baseUrl.'/static/modules/pricefalls/assets/images/footer-icon/pricefalls.png',
									'href'=>'https://integration.cedcommerce.com/pricefalls'
								],
									'fruugo'=>[
									'label'=>'Fruugo',
									//'src'=>yii::$app->request->baseUrl.'/static/modules/pricefalls/assets/images/footer-icon/fruugo.png',
									'href'=>'https://integration.cedcommerce.com/fruugo'
								],
									'tophatter'=>[
									'label'=>'Tophatter',
									//'src'=>yii::$app->request->baseUrl.'/static/modules/pricefalls/assets/images/footer-icon/tophatters.png',
									'href'=>'https://integration.cedcommerce.com/tophatter'
								]
						];

						$module=Yii::$app->controller->module->id;
						unset($data['integrations'][$module]);

						// unset($data['partners'][$module]);


						return $data;
					}
				}
?>