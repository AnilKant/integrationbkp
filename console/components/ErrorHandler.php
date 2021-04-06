<?php
namespace console\components;

use Yii;
use yii\helpers\Console;
use yii\base\UserException;
use yii\console\Exception;
use yii\console\ErrorException;
use yii\console\UnknownCommandException;
use frontend\modules\walmart\components\Data;

class ErrorHandler extends \yii\console\ErrorHandler
{
    /**
     * Renders an exception using ansi format for console output.
     * @param \Exception $exception the exception to be rendered.
     */
    protected function renderException($exception)
    {
        if ($exception instanceof UnknownCommandException) {
            // display message and suggest alternatives in case of unknown command
            $message = $this->formatMessage($exception->getName() . ': ') . $exception->command;
            $alternatives = $exception->getSuggestedAlternatives();
            if (count($alternatives) === 1) {
                $message .= "\n\nDid you mean \"" . reset($alternatives) . "\"?";
            } elseif (count($alternatives) > 1) {
                $message .= "\n\nDid you mean one of these?\n    - " . implode("\n    - ", $alternatives);
            }
        } elseif ($exception instanceof Exception && ($exception instanceof UserException || !YII_DEBUG)) {
            $message = $this->formatMessage($exception->getName() . ': ') . $exception->getMessage();
        } elseif (YII_DEBUG) {
            if ($exception instanceof Exception) {
                $message = $this->formatMessage("Exception ({$exception->getName()})");
            } elseif ($exception instanceof ErrorException) {
                $message = $this->formatMessage($exception->getName());
            } else {
                $message = $this->formatMessage('Exception');
            }
            $message .= $this->formatMessage(" '" . get_class($exception) . "'", [Console::BOLD, Console::FG_BLUE])
                . ' with message ' . $this->formatMessage("'{$exception->getMessage()}'", [Console::BOLD]) //. "\n"
                . "\n\nin " . dirname($exception->getFile()) . DIRECTORY_SEPARATOR . $this->formatMessage(basename($exception->getFile()), [Console::BOLD])
                . ':' . $this->formatMessage($exception->getLine(), [Console::BOLD, Console::FG_YELLOW]) . "\n";
            if ($exception instanceof \yii\db\Exception && !empty($exception->errorInfo)) {
                $message .= "\n" . $this->formatMessage("Error Info:\n", [Console::BOLD]) . print_r($exception->errorInfo, true);
            }
            $message .= "\n" . $this->formatMessage("Stack trace:\n", [Console::BOLD]) . $exception->getTraceAsString();
        } else {
            $message = $this->formatMessage('Error: ') . $exception->getMessage();
        }

        if (PHP_SAPI === 'cli') {
            Console::stderr($message . "\n");
        } else {
            echo $message . "\n";
        }

        self::createLogforConsoleError($message, $exception);
    }

    public function createLogforConsoleError($message, $exception)
    {
        $errorMessage = "Error in console :\n" . $message;

        $actualMessage = "\nActual Error :\n" . self::prepareMessage($exception) . "\n";

        $errorMessage = $errorMessage . "\n" . $actualMessage;

        $requested_route = Yii::$app->requestedRoute;
        $requested_route = explode('/', $requested_route);

        $file_path = Yii::getAlias('@webroot') . DIRECTORY_SEPARATOR . 'var' . DIRECTORY_SEPARATOR . 'errors-exceptions' . DIRECTORY_SEPARATOR .'console' . DIRECTORY_SEPARATOR . $requested_route[0];
        
        self::createDirectory($file_path, 0775);

        $filename = time() . ".log";
        $filepath = $file_path . DIRECTORY_SEPARATOR . $filename;

        self::createLog($errorMessage, $filepath, 'a', true);
    }

    private function prepareMessage($exception)
    {
        $message = $exception->getMessage() . ' in ' . $exception->getFile() . ' at line ' . $exception->getLine();
        return $message;
    }

    public static function createLog($message, $file_path, $mode='a', $sendMail=false, $trace=false)
    {
        $dir = dirname($file_path);

        self::createDirectory($dir, 0775);

        $fileOrig = fopen($file_path, $mode);
        if ($trace) {
            try {
                throw new \Exception($message);
            } catch (Exception $e) {
                $message = $e->getTraceAsString();
            }
        }

        fwrite($fileOrig, "\n" . date('d-m-Y H:i:s') . "\n" . $message);
        fclose($fileOrig);

        if ($sendMail) {
            $email = 'himanshusahu@cedcoss.com';
            $subject = 'Shopify Cedcommerce Console Exception Log';
            Data::sendEmail($file_path, $message, $email, $subject);
        }
    }

    public static function createDirectory($directoryPath, $permission)
    {
        if (!file_exists($directoryPath)) {
            $old_umask = umask(0);
            mkdir($directoryPath, $permission, true);
            umask($old_umask);
        }
    }
}
