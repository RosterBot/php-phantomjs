<?php

/*
 * This file is part of the php-phantomjs.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace JonnyW\PhantomJSBundle;

use JonnyW\PhantomJSBundle\Message\RequestInterface;
use JonnyW\PhantomJSBundle\Message\ResponseInterface;

/**
 * PHP PhantomJSBundle
 *
 * @author Jon Wenmoth <contact@jonnyw.me>
 */
interface ClientInterface
{
    /**
     * Get message factory instance
     *
     * @access public
     * @return \JonnyW\PhantomJSBundle\Message\MessageFactoryInterface
     */
    public function getMessageFactory();

    /**
     * Send request
     *
     * @access public
     *
*@param \JonnyW\PhantomJSBundle\Message\RequestInterface  $request
     * @param \JonnyW\PhantomJSBundle\Message\ResponseInterface $response
     */
    public function send(RequestInterface $request, ResponseInterface $response);

    /**
     * Set bin directory.
     *
     * @access public
     *
*@param  string                   $path
     *
*@return \JonnyW\PhantomJSBundle\Client
     */
    public function setBinDir($path);

    /**
     * Get bin directory.
     *
     * @access public
     * @return string
     */
    public function getBinDir();

    /**
     * Set new PhantomJSBundle executable path.
     *
     * @access public
     * @param string $path
     */
    public function setPhantomJs($path);

    /**
     * Get PhantomJSBundle executable path.
     *
     * @access public
     * @return string
     */
    public function getPhantomJs();

        /**
     * Set PhantomJSBundle loader executable path.
     *
     * @access public
     * @param string $path
     */
    public function setPhantomLoader($path);

    /**
     * Get PhantomJSBundle loader executable path.
     *
     * @access public
     * @return string
     */
    public function getPhantomLoader();

    /**
     * Set PhantomJSBundle run options.
     *
     * @access public
     * @param array $options
     */
    public function setOptions(array $options);

    /**
     * Get PhantomJSBundle run options.
     *
     * @access public
     * @return array
     */
    public function getOptions();

    /**
     * Add single PhantomJSBundle run option.
     *
     * @access public
     * @param string $option
     */
    public function addOption($option);

    /**
     * Debug.
     *
     * @access public
     * @param boolean $doDebug
     */
    public function debug($doDebug);

    /**
     * Set log info.
     *
     * @access public
     * @param string $info
     */
    public function setLog($info);

    /**
     * Get log info.
     *
     * @access public
     * @return string
     */
    public function getLog();

    /**
     * Clear log info.
     *
     * @access public
     */
    public function clearLog();
}
