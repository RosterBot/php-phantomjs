<?php

/*
 * This file is part of the php-phantomjs.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace JonnyW\PhantomJSBundle\Procedure;

use JonnyW\PhantomJSBundle\ClientInterface;
use JonnyW\PhantomJSBundle\Message\RequestInterface;
use JonnyW\PhantomJSBundle\Message\ResponseInterface;

/**
 * PHP PhantomJSBundle
 *
 * @author Jon Wenmoth <contact@jonnyw.me>
 */
interface ProcedureInterface
{
    /**
     * Run procedure.
     *
     * @access public
     *
*@param \JonnyW\PhantomJSBundle\ClientInterface           $client
     * @param \JonnyW\PhantomJSBundle\Message\RequestInterface  $request
     * @param \JonnyW\PhantomJSBundle\Message\ResponseInterface $response
     */
    public function run(ClientInterface $client, RequestInterface $request, ResponseInterface $response);

    /**
     * Load procedure.
     *
     * @access public
     * @param string $procedure
     */
    public function load($procedure);

    /**
     * Get procedure template.
     *
     * @access public
     * @return string
     */
    public function getProcedure();
}
