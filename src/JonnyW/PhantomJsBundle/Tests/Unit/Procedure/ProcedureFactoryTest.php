<?php

/*
 * This file is part of the php-phantomjs.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace JonnyW\PhantomJSBundle\Tests\Unit\Procedure;

use JonnyW\PhantomJSBundle\Cache\CacheInterface;
use JonnyW\PhantomJSBundle\Parser\ParserInterface;
use JonnyW\PhantomJSBundle\Template\TemplateRendererInterface;
use JonnyW\PhantomJSBundle\Procedure\ProcedureFactory;

/**
 * PHP PhantomJSBundle
 *
 * @author Jon Wenmoth <contact@jonnyw.me>
 */
class ProcedureFactoryTest extends \PHPUnit_Framework_TestCase
{

/** +++++++++++++++++++++++++++++++++++ **/
/** ++++++++++++++ TESTS ++++++++++++++ **/
/** +++++++++++++++++++++++++++++++++++ **/

    /**
     * Test create procedure returns instance
     * of procedure.
     *
     * @access public
     * @return void
     */
    public function testCreateProcedureReturnsInstanceOfProcedure()
    {
        $parser    = $this->getParser();
        $cache     = $this->getCache();
        $renderer  = $this->getRenderer();

        $procedureFactory = $this->getProcedureFactory($parser, $cache, $renderer);

        $this->assertInstanceOf('\JonnyW\PhantomJs\Procedure\Procedure', $procedureFactory->createProcedure());
    }

/** +++++++++++++++++++++++++++++++++++ **/
/** ++++++++++ TEST ENTITIES ++++++++++ **/
/** +++++++++++++++++++++++++++++++++++ **/

    /**
     * Get procedure factory instance.
     *
     * @access protected
     *
*@param  \JonnyW\PhantomJSBundle\Parser\ParserInterface             $parser
     * @param  \JonnyW\PhantomJSBundle\Cache\CacheInterface               $cacheHandler
     * @param  \JonnyW\PhantomJSBundle\Template\TemplateRendererInterface $renderer
     *
*@return \JonnyW\PhantomJSBundle\Procedure\ProcedureFactory
     */
    protected function getProcedureFactory(ParserInterface $parser, CacheInterface $cacheHandler, TemplateRendererInterface $renderer)
    {
        $procedureFactory = new ProcedureFactory($parser, $cacheHandler, $renderer);

        return $procedureFactory;
    }

/** +++++++++++++++++++++++++++++++++++ **/
/** ++++++++++ MOCKS / STUBS ++++++++++ **/
/** +++++++++++++++++++++++++++++++++++ **/

    /**
     * Get mock parser instance.
     *
     * @access protected
     * @return \JonnyW\PhantomJSBundle\Parser\ParserInterface
     */
    protected function getParser()
    {
        $mockParser = $this->getMock('\JonnyW\PhantomJs\Parser\ParserInterface');

        return $mockParser;
    }

    /**
     * Get mock cache instance.
     *
     * @access protected
     * @return \JonnyW\PhantomJSBundle\Cache\CacheInterface
     */
    protected function getCache()
    {
        $mockCache = $this->getMock('\JonnyW\PhantomJs\Cache\CacheInterface');

        return $mockCache;
    }

    /**
     * Get mock template renderer instance.
     *
     * @access protected
     * @return \JonnyW\PhantomJSBundle\Template\TemplateRendererInterface
     */
    protected function getRenderer()
    {
        $mockTemplateRenderer = $this->getMock('\JonnyW\PhantomJs\Template\TemplateRendererInterface');

        return $mockTemplateRenderer;
    }
}
