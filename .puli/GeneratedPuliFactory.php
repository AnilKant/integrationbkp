<?php

namespace Puli;

use Puli\Discovery\Api\Discovery;
use Puli\Discovery\Binding\Initializer\ResourceBindingInitializer;
use Puli\Discovery\JsonDiscovery;
use Puli\Manager\Api\Server\ServerCollection;
use Puli\Repository\Api\ResourceRepository;
use Puli\Repository\JsonRepository;
use Puli\UrlGenerator\Api\UrlGenerator;
use Puli\UrlGenerator\DiscoveryUrlGenerator;
use RuntimeException;

/**
 * Creates Puli's core services.
 *
 * This class was auto-generated by Puli.
 *
 * IMPORTANT: Before modifying the code below, set the "factory.auto-generate"
 * configuration key to false:
 *
 *     $ puli config factory.auto-generate false
 *
 * Otherwise any modifications will be overwritten!
 */
class GeneratedPuliFactory
{
    /**
     * Creates the resource repository.
     *
     * @return ResourceRepository The created resource repository.
     */
    public function createRepository()
    {
        if (!interface_exists('Puli\Repository\Api\ResourceRepository')) {
            throw new RuntimeException('Please install puli/repository to create ResourceRepository instances.');
        }

        $repo = new JsonRepository(__DIR__.'/path-mappings.json', __DIR__.'/..', true);

        return $repo;
    }

    /**
     * Creates the resource discovery.
     *
     * @param ResourceRepository $repo The resource repository to read from.
     *
     * @return Discovery The created discovery.
     */
    public function createDiscovery(ResourceRepository $repo)
    {
        if (!interface_exists('Puli\Discovery\Api\Discovery')) {
            throw new RuntimeException('Please install puli/discovery to create Discovery instances.');
        }

        $discovery = new JsonDiscovery(__DIR__.'/bindings.json', array(
            new ResourceBindingInitializer($repo),
        ));

        return $discovery;
    }

    /**
     * Creates the URL generator.
     *
     * @param Discovery $discovery The discovery to read from.
     *
     * @return UrlGenerator The created URL generator.
     */
    public function createUrlGenerator(Discovery $discovery)
    {
        if (!interface_exists('Puli\UrlGenerator\Api\UrlGenerator')) {
            throw new RuntimeException('Please install puli/url-generator to create UrlGenerator instances.');
        }

        $generator = new DiscoveryUrlGenerator($discovery, array());

        return $generator;
    }

    /**
     * Returns the order in which the installed packages should be loaded
     * according to the override statements.
     *
     * @return string[] The sorted package names.
     */
    public function getPackageOrder()
    {
        $order = array(
            'yiisoft/yii2-app-advanced',
            '2amigos/yii2-date-picker-widget',
            'aws/aws-sdk-php',
            'behat/gherkin',
            'bower-asset/bootstrap',
            'bower-asset/bootstrap-datepicker',
            'bower-asset/inputmask',
            'bower-asset/jquery',
            'bower-asset/punycode',
            'bower-asset/typeahead.js',
            'bower-asset/yii2-pjax',
            'cebe/markdown',
            'clue/stream-filter',
            'codeception/base',
            'codeception/phpunit-wrapper',
            'codeception/stub',
            'codeception/verify',
            'container-interop/container-interop',
            'doctrine/instantiator',
            'doctrine/lexer',
            'dpodium/yii2-twilio',
            'egulias/email-validator',
            'ezyang/htmlpurifier',
            'fzaninotto/faker',
            'guzzlehttp/guzzle',
            'guzzlehttp/promises',
            'guzzlehttp/psr7',
            'justinrainbow/json-schema',
            'kartik-v/yii2-krajee-base',
            'kartik-v/yii2-widget-datetimepicker',
            'kartik-v/yii2-widget-select2',
            'liyunfang/yii2-widget-linkpager',
            'mailgun/mailgun-php',
            'markbaker/complex',
            'monolog/monolog',
            'mrclay/minify',
            'mtdowling/jmespath.php',
            'myclabs/deep-copy',
            'natxet/cssmin',
            'nikic/fast-route',
            'paragonie/random_compat',
            'phar-io/manifest',
            'phar-io/version',
            'php-amqplib/php-amqplib',
            'php-http/client-common',
            'php-http/discovery',
            'php-http/guzzle6-adapter',
            'php-http/httplug',
            'php-http/message',
            'php-http/message-factory',
            'php-http/multipart-stream-builder',
            'php-http/promise',
            'phpdocumentor/reflection-common',
            'phpdocumentor/reflection-docblock',
            'phpdocumentor/type-resolver',
            'phpoffice/phpspreadsheet',
            'phpspec/php-diff',
            'phpspec/prophecy',
            'phpunit/php-code-coverage',
            'phpunit/php-file-iterator',
            'phpunit/php-text-template',
            'phpunit/php-timer',
            'phpunit/php-token-stream',
            'phpunit/phpunit',
            'phpunit/phpunit-mock-objects',
            'pimple/pimple',
            'psr/container',
            'psr/http-message',
            'psr/log',
            'psr/simple-cache',
            'puli/composer-plugin',
            'puli/discovery',
            'puli/repository',
            'puli/url-generator',
            'ramsey/uuid',
            'sebastian/code-unit-reverse-lookup',
            'sebastian/comparator',
            'sebastian/diff',
            'sebastian/environment',
            'sebastian/exporter',
            'sebastian/global-state',
            'sebastian/object-enumerator',
            'sebastian/object-reflector',
            'sebastian/recursion-context',
            'sebastian/resource-operations',
            'sebastian/version',
            'seld/jsonlint',
            'skeeks/yii2-assets-auto-compress',
            'slim/slim',
            'swiftmailer/swiftmailer',
            'symfony/browser-kit',
            'symfony/console',
            'symfony/css-selector',
            'symfony/debug',
            'symfony/dom-crawler',
            'symfony/event-dispatcher',
            'symfony/finder',
            'symfony/options-resolver',
            'symfony/polyfill-ctype',
            'symfony/polyfill-mbstring',
            'symfony/process',
            'symfony/yaml',
            'tedivm/jshrink',
            'theseer/tokenizer',
            'twilio/sdk',
            'webmozart/assert',
            'webmozart/expression',
            'webmozart/glob',
            'webmozart/json',
            'webmozart/path-util',
            'yiisoft/yii2',
            'yiisoft/yii2-bootstrap',
            'yiisoft/yii2-composer',
            'yiisoft/yii2-debug',
            'yiisoft/yii2-faker',
            'yiisoft/yii2-gii',
            'yiisoft/yii2-httpclient',
            'yiisoft/yii2-swiftmailer',
            'zendframework/zend-diactoros',
        );

        return $order;
    }
}
