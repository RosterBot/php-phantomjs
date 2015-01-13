<?php

/*
 * This file is part of the php-phantomjs.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace JonnyW\PhantomJSBundle\Procedure;

use \JonnyW\PhantomJSBundle\Cache\CacheInterface;
use \JonnyW\PhantomJSBundle\Parser\ParserInterface;
use \JonnyW\PhantomJSBundle\Template\TemplateRendererInterface;

/**
 * PHP PhantomJSBundle
 *
 * @author Jon Wenmoth <contact@jonnyw.me>
 */
class ProcedureFactory implements ProcedureFactoryInterface
{
    /**
     * Parser.
     *
     * @var \JonnyW\PhantomJSBundle\Parser\ParserInterface
     * @access protected
     */
    protected $parser;

    /**
     * Cache handler.
     *
     * @var \JonnyW\PhantomJSBundle\Cache\CacheInterface
     * @access protected
     */
    protected $cacheHandler;

    /**
     * Template renderer.
     *
     * @var \JonnyW\PhantomJSBundle\Template\TemplateRendererInterface
     * @access protected
     */
    protected $renderer;

    /**
     * Internal constructor.
     *
     * @access public
     *
*@param \JonnyW\PhantomJSBundle\Parser\ParserInterface             $parser
     * @param \JonnyW\PhantomJSBundle\Cache\CacheInterface               $cacheHandler
     * @param \JonnyW\PhantomJSBundle\Template\TemplateRendererInterface $renderer
     */
    public function __construct(ParserInterface $parser, CacheInterface $cacheHandler, TemplateRendererInterface $renderer)
    {
        $this->parser       = $parser;
        $this->cacheHandler = $cacheHandler;
        $this->renderer     = $renderer;
    }

    /**
     * Create new procedure instance.
     *
     * @access public
     * @return \JonnyW\PhantomJSBundle\Procedure\Procedure
     */
    public function createProcedure()
    {
        $procedure = new Procedure(
            $this->parser,
            $this->cacheHandler,
            $this->renderer
        );

        return $procedure;
    }
}
