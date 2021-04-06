<?php
/* @var $this \yii\web\View */
/* @var $exception \yii\base\Exception */
/* @var $handler \yii\web\ErrorHandler */
/*----------------------Call Stack Trace Variables--------------------------*/
/* @var $file string|null */
/* @var $line integer|null */
/* @var $class string|null */
/* @var $method string|null */
/* @var $index integer */
/* @var $lines string[] */
/* @var $begin integer */
/* @var $end integer */
/* @var $args array */

use yii\helpers\Html;
use yii\web\ErrorHandler;
use yii\web\View;
use frontend\assets\AppAsset;

$view = new View;
AppAsset::register($view);
$valuecheck = "";
$handler = $this;
$valuecheck = Yii::$app->request->get('shop');
if (!($handler instanceof ErrorHandler) && ($handler instanceof View) && property_exists($handler, 'context')) {
    $handler = $handler->context;
}


/*------------------Html saving code starts----------------------*/

$useErrorView = (!YII_DEBUG || $exception instanceof UserException);
$errorView = '@yii/views/errorHandler/error.php';
$exceptionView = '@yii/views/errorHandler/exception.php';
$file = $useErrorView ? $errorView : $exceptionView;
$data = $handler->renderFile($file, [
    'exception' => $exception,
]);
if (false) {
    echo $data;
    die;
} else {
    /* To direct get Error String
$errorString = $this->convertExceptionToString($exception);
*/

    //code by himanshu
    if (isset($_GET['debug'])) :
        echo $data;
    else :
        //end

        /* By Himanshu */
        $requestedRoute = Yii::$app->requestedRoute;
        $route = explode('/', $requestedRoute);

        $module = $route[0];
        $controller = isset($route[1]) ? $route[1] : 'index';
        if (!isset($route[2]))
            $action = 'index';

        $allowedControllers = ['bulk-upload-server', 'bulk-upload'];
        if (in_array($controller, $allowedControllers)) {
            $filename = time() . ".html";

            $filepath = Yii::getAlias('@webroot') . DIRECTORY_SEPARATOR . 'frontend' . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . 'walmart' . DIRECTORY_SEPARATOR . 'upload-errors' . DIRECTORY_SEPARATOR . $filename;

            if (!file_exists(dirname($filepath))) {
                mkdir(dirname($filepath), 0777, true);
            }

            $handle = fopen($filepath, (file_exists($filepath)) ? 'a' : 'w');
            fwrite($handle, $data);
            fclose($handle);

            $mailContent = 'There has been an error occurred during the Bulk Product Upload Process. Please check the error in the attached document.';
            frontend\modules\walmart\components\Data::sendEmail($filepath, $mailContent, 'himanshusahu@cedcoss.com', 'Product Upload Exception in Walmart');
            frontend\modules\walmart\components\Data::sendEmail($filepath, $mailContent, 'shivamverma@cedcoss.com', 'Product Upload Exception in Walmart');
        }
        /* Code By Himanshu End */

        $base_path = Yii::getAlias('@webroot') . '/error';

        if (!file_exists($base_path)) {
            mkdir($base_path, 0775, true);
        }
        $filename = time() . ".html";
        $filepath = "";
        $filepath = $base_path . DIRECTORY_SEPARATOR . $filename;
        //    $fh = fopen($filepath, (file_exists($filepath)) ? 'a' : 'w');
        //    fwrite($fh, $data);
        //    fclose($fh);
        /*------------------Html saving code ends----------------------*/
        $newUrl = Yii::$app->getUrlManager()->getBaseUrl() . '/error/' . $filename;
?>
        <?php $view->beginPage() ?>
        <div class='error_data' style="display: none">
            <?php echo $data; ?>
        </div>
        <html lang="<?= Yii::$app->language ?>">

        <head>
            <a class="show-error" href="<?= $newUrl ?>" style="display:none"></a>
            <link rel="icon" href="<?php echo Yii::$app->request->baseUrl ?>/images/favicon.ico">
            <meta charset="<?= Yii::$app->charset ?>">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <meta content="INDEX,FOLLOW" name="robots">
            <meta name="google-site-verification" content="vJ8BS5PRRVFvpk5dCrUMgTZdDVIuWKMbvmCo2QTIEag" />
            <script type="text/javascript" src="<?= Yii::$app->getUrlManager()->getBaseUrl(); ?>/js/jquery.js"></script>
            <link rel="stylesheet" href="<?= Yii::$app->getUrlManager()->getBaseUrl(); ?>/css/font-awesome.min.css">
            <script type="text/javascript" src="<?= Yii::$app->getUrlManager()->getBaseUrl(); ?>/js/jquery.datetimepicker.full.min.js"></script>
            <?= Html::csrfMetaTags() ?>
            <title><?= Html::encode("Shopify Walmart Integration | CedCommerce"); ?></title>
            <title><?= Html::encode($view->title) ?></title>
            <?php $view->head() ?>
            <script type="text/javascript">
                (function(i, s, o, g, r, a, m) {
                    i['GoogleAnalyticsObject'] = r;
                    i[r] = i[r] || function() {
                        (i[r].q = i[r].q || []).push(arguments)
                    }, i[r].l = 1 * new Date();
                    a = s.createElement(o),
                        m = s.getElementsByTagName(o)[0];
                    a.async = 1;
                    a.src = g;
                    m.parentNode.insertBefore(a, m)
                })(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');
                ga('create', 'UA-63841461-1', 'auto');
                ga('send', 'pageview');
            </script>

            <link rel="stylesheet" href="<?= Yii::$app->getUrlManager()->getBaseUrl(); ?>/css/bootstrap.css">
            <link rel="stylesheet" href="<?= Yii::$app->getUrlManager()->getBaseUrl(); ?>/css/style.css">


        </head>

        <body class="iframe-body">
            <?php $view->beginBody() ?>
            <div class="wrap ced-jet-navigation-mbl">

                <div style="display: none;">
                    <?= $data; ?>
                </div>
                <div class="fixed-container-body-class">
                    <style>
                        /*Error 404 page design css starts*/
                        .error {
                            background: none repeat scroll 0 0 #f4f6f8;
                            padding: 50px 0;
                            text-align: center;
                        }

                        .error .lead,
                        .error .error-msg,
                        .error .error-text,
                        .error .error-subtext {
                            color: #213e8a;
                            font-family: jet_regular;
                            text-transform: uppercase;
                        }

                        .error .lead {
                            font-size: 130px;
                            font-weight: bold;
                            line-height: 130px;
                            margin: 0 0 10px;
                        }

                        .error .error-msg {
                            font-size: 26px;
                            font-weight: bold;
                            line-height: 28px;
                            margin: 0;
                            padding: 0;
                        }

                        .error .error-text {
                            color: #526fbb;
                            font-size: 13px;
                            margin: 10px 0;
                        }

                        .error .error-subtext {
                            font-size: 22px;
                            line-height: 18px;
                        }

                        .backtohome-btn {
                            background: #fb6a33 none repeat scroll 0 0 !important;
                            border: medium none !important;
                            border-radius: 17px;
                            display: inline-block;
                            font-size: 18px;
                            line-height: 20px;
                            margin-top: 10px;
                            padding: 7px 15px;
                        }

                        .backtohome-btn:hover {
                            text-decoration: none;
                            background: #1a75cf !important;
                        }

                        .backtohome-btn:focus {
                            outline: none;
                            text-decoration: none;
                        }

                        .backtohome-btn .btn-text {
                            color: #fff;
                            font-size: 14px;
                            text-transform: uppercase;
                        }

                        .backtohome-btn .btn-text::after,
                        .backtohome-btn .btn-text::before {
                            background: #fff none repeat scroll 0 0;
                            border-radius: 50%;
                            content: "";
                            display: inline-block;
                            height: 5px;
                            margin: 2px 5px;
                            position: relative;
                            width: 5px;
                        }

                        .image-wrap>img {
                            max-width: 100%;
                        }

                        .error .error-content {
                            margin-top: 60px;
                        }

                        /*Error 404 page design css ends*/

                        /*Error payment page design css starts*/
                        .error_pages .lead {
                            font-size: 45px;
                            line-height: 60px;
                        }

                        .error_pages .error-msg {
                            font-size: 16px;
                            font-weight: normal;
                        }

                        /*Error payment page design css ends*/
                    </style>


                    <div class="error">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-7 col-sm-7 col-xs-12">
                                    <div class="image-wrap">
                                        <img src="<?= Yii::$app->getUrlManager()->getBaseUrl(); ?>/images/gif/404.gif">
                                    </div>
                                </div>
                                <div class="col-md-5 col-sm-5 col-xs-12">
                                    <div class="error-content error_pages">
                                        <p class="lead">Something Went Wrong sorry!!!!</p>
                                        <p class="error-msg">Page Not Found</p>
                                        <p class="error-text">Looks like jnhgftyjnhtyjhsomething was wrong</p>
                                        <p class="error-subtext">We're working on it</p>
                                        <a class="backtohome-btn" href="#"><span class="btn-text">Back to home</span></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div id="helpSection" style="display:none"></div>
            </div>

            <footer class="container-fluid footer-section">
                <div class="contact-section">
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                            <div class="ticket">
                                <div class="icon-box">
                                    <div class="image">
                                        <a title="Click Here to Submit a Support Ticket" href="http://support.cedcommerce.com/" target="_blank"><img src="<?= Yii::$app->request->baseUrl ?>/images/ticket.png"></a>
                                    </div>
                                </div>
                                <div class="text-box">
                                    <span>Submit issue via ticket</span>
                                </div>
                                <div class="clear"></div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                            <div class="mail">
                                <div class="icon-box">
                                    <div class="image">
                                        <a title="Click Here to Contact us through Mail" href="mailto:apps@cedcommerce.com" target="_blank"><img src="<?= Yii::$app->request->baseUrl ?>/images/mail.png"></a>
                                    </div>
                                </div>
                                <div class="text-box">
                                    <span>Send us an E-mail</span>
                                </div>
                                <div class="clear"></div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                            <div class="skype">
                                <div class="icon-box">
                                    <div class="image">
                                        <a title="" href="javascript:void(0)"><img src="<?= Yii::$app->request->baseUrl ?>/images/skype.png"></a>
                                    </div>
                                </div>
                                <div class="text-box">
                                    <span>Connect via skype</span>
                                </div>
                                <div class="clear"></div>
                            </div>
                        </div>

                    </div>
                </div>
            </footer>

            <div class="copyright-section">
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <a title="Click Here to Submit a Support Ticket" href="https://play.google.com/store/apps/details?id=com.cedcommerce.shopifyintegration&hl=en" target="_blank"><img src="<?= Yii::$app->request->baseUrl ?>/images/GooglePlay.png"></a>
                        <a title="Click Here to Contact us through Mail" href="https://itunes.apple.com/us/app/cedbridge-for-shopify/id1186746708?ls=1&mt=8
" target="_blank"><img src="<?= Yii::$app->request->baseUrl ?>/images/App-Store.png"></a>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <div class="copyright">
                            <span>Copyright © 2017 CEDCOMMERCE | All Rights Reserved.</span>
                        </div>
                    </div>
                </div>
            </div>

            <?php $view->endBody() ?>
            <script type="text/javascript">
                $(document).ready(function() {
                    $('.dropdown').addClass('dropdown1').removeClass('dropdown');
                });
            </script>

            <!-- <script src="https://cdn.shopify.com/s/assets/external/app.js"></script> -->
            <?php
            /*if (!Yii::$app->user->isGuest) {
        if ($valuecheck)
        {
            ?>
            <script type="text/javascript">
                //add css for embedded app
                $(document).ready(function () {
                    var head1 = $(document).find('head');
                    var url = '<?= Yii::$app->getUrlManager()->getBaseUrl();?>/css/embapp.css';
                    head1.append($("<link/>", {rel: "stylesheet", href: url, type: "text/css"}));
                    $('.logout_merchant').css('display', 'none');
                    //$('nav.navbar-fixed-top').css('display','none');
                    $('.wrap > .container').css('padding', 0);

                });
                //initialise embedded iframe
                ShopifyApp.init({
                    apiKey: "<?php echo PUBLIC_KEY;?>",
                    shopOrigin: "<?php echo "https://" . \Yii::$app->user->identity->username;?>"
                });
                ShopifyApp.ready(function () {
                    ShopifyApp.Bar.loadingOff();

                });

            </script>
        <?php
        }
        ?>
            <script type="text/javascript">
                if (self !== top) {
                    var head1 = $(self.document.head);
                    var url = '<?= Yii::$app->getUrlManager()->getBaseUrl();?>/css/embapp.css';
                    head1.append($("<link/>", {rel: "stylesheet", href: url, type: "text/css"}));
                    $('.logout_merchant').css('display', 'none');
                    //$('nav.navbar-fixed-top').css('display','none');
                    //$('.wrap > .container').css('padding',0)
                    ShopifyApp.init({
                        apiKey: "<?= WALMART_APP_KEY;?>",
                        shopOrigin: "<?= 'https://' . Yii::$app->user->identity->username;?>"
                    });
                    ShopifyApp.ready(function () {
                        ShopifyApp.Bar.loadingOff();

                    });
                }
            </script>
            <?php
        }*/
            ?>
            <!--Start of Zopim Live Chat Script-->
            <script type="text/javascript">
                window.$zopim || (function(d, s) {
                    var z = $zopim = function(c) {
                            z._.push(c)
                        },
                        $ = z.s =
                        d.createElement(s),
                        e = d.getElementsByTagName(s)[0];
                    z.set = function(o) {
                        z.set._.push(o)
                    };
                    z._ = [];
                    z.set._ = [];
                    $.async = !0;
                    $.setAttribute("charset", "utf-8");
                    $.src = "//v2.zopim.com/?322cfxiaxE0fIlpUlCwrBT7hUvfrtmuw";
                    z.t = +new Date;
                    $.type = "text/javascript";
                    e.parentNode.insertBefore($, e)
                })(document, "script");

                $zopim(function() {
                    window.setTimeout(function() {
                        //$zopim.livechat.window.show();
                    }, 2000); //time in milliseconds
                });
            </script>
            <!-- end menu -->

        </body>

        </html>
        <?php $view->endPage() ?>
    <?php endif; //code by himanshu
    ?>

<?php
} ?>