<?php

/*
 * This file is part of the php-phantomjs.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace JonnyW\PhantomJSBundle\Procedure;

use JonnyW\PhantomJSBundle\ClientInterface;
use JonnyW\PhantomJSBundle\Cache\CacheInterface;
use JonnyW\PhantomJSBundle\Parser\ParserInterface;
use JonnyW\PhantomJSBundle\Message\RequestInterface;
use JonnyW\PhantomJSBundle\Message\ResponseInterface;
use JonnyW\PhantomJSBundle\Template\TemplateRendererInterface;
use JonnyW\PhantomJSBundle\Exception\NotWritableException;
use JonnyW\PhantomJSBundle\Exception\ProcedureFailedException;

/**
 * PHP PhantomJSBundle
 *
 * @author Jon Wenmoth <contact@jonnyw.me>
 */
class Procedure implements ProcedureInterface
{
    /**
     * Parser instance.
     *
     * @var \JonnyW\PhantomJSBundle\Parser\ParserInterface
     * @access protected
     */
    protected $parser;

    /**
     * Cache handler instance.
     *
     * @var \JonnyW\PhantomJSBundle\Cache\CacheInterface
     * @access protected
     */
    protected $cacheHandler;

    /**
     * Procedure template.
     *
     * @var string
     * @access protected
     */
    protected $procedure;

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
     * Run procedure.
     *
     * @access public
     *
*@param  \JonnyW\PhantomJSBundle\ClientInterface                    $client
     * @param  \JonnyW\PhantomJSBundle\Message\RequestInterface           $request
     * @param  \JonnyW\PhantomJSBundle\Message\ResponseInterface          $response
     *
*@throws \JonnyW\PhantomJSBundle\Exception\ProcedureFailedException
     * @throws \Exception
     * @throws \JonnyW\PhantomJSBundle\Exception\NotWritableException
     * @return void
     */
    public function run(ClientInterface $client, RequestInterface $request, ResponseInterface $response)
    {
        try {

            $template  = $this->getProcedure();
            $procedure = $this->renderer->render($template, ['request' => $request]);

            $executable = $this->write($procedure);

            $descriptorspec = [
                ['pipe', 'r'],
                ['pipe', 'w'],
                ['pipe', 'a']
            ];

            $process = proc_open(escapeshellcmd(sprintf('%s %s', $client->getCommand(), $executable)), $descriptorspec, $pipes, null, null);

            if (!is_resource($process)) {
                throw new ProcedureFailedException('proc_open() did not return a resource');
            }

            $result = stream_get_contents($pipes[1]);
            $log    = stream_get_contents($pipes[2]);

            fclose($pipes[0]);
            fclose($pipes[1]);
            fclose($pipes[2]);

            proc_close($process);

            $response->import(
                $this->parser->parse($result)
            );

            $client->setLog($log);

            $this->remove($executable);

        } catch (NotWritableException $e) {
            throw $e;
        } catch (\Exception $e) {

            if (isset($executable)) {
                $this->remove($executable);
            }

            throw new ProcedureFailedException(sprintf('Error when executing PhantomJSBundle procedure "%s" - %s', $request->getType(), $e->getMessage()));
        }
    }

    /**
     * Load procedure.
     *
     * @access public
     *
*@param  string                                $procedure
     *
*@return \JonnyW\PhantomJSBundle\Procedure\Procedure
     */
    public function load($procedure)
    {
        $this->procedure = $procedure;

        return $this;
    }

    /**
     * Get procedure template.
     *
     * @access public
     * @return string
     */
    public function getProcedure()
    {
        return $this->procedure;
    }

    /**
     * Write procedure script cache.
     *
     * @access protected
     * @param  string $procedure
     * @return string
     */
    protected function write($procedure)
    {
        $executable = $this->cacheHandler->save(uniqid(), $procedure);

        return $executable;
    }

    /**
     * Remove procedure script cache.
     *
     * @access protected
     * @param  string $filePath
     * @return void
     */
    protected function remove($filePath)
    {
        $this->cacheHandler->delete($filePath);
    }
}
