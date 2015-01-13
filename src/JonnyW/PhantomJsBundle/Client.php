<?php

/*
 * This file is part of the php-phantomjs.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace JonnyW\PhantomJSBundle;

use JonnyW\PhantomJSBundle\Exception\InvalidExecutableException;
use JonnyW\PhantomJSBundle\Procedure\ProcedureLoaderInterface;
use JonnyW\PhantomJSBundle\Message\MessageFactoryInterface;
use JonnyW\PhantomJSBundle\Message\RequestInterface;
use JonnyW\PhantomJSBundle\Message\ResponseInterface;

/**
 * PHP PhantomJSBundle
 *
 * @author Jon Wenmoth <contact@jonnyw.me>
 */
class Client implements ClientInterface
{
    /**
     * Procedure loader instance
     *
     * @var \JonnyW\PhantomJSBundle\Procedure\ProcedureLoaderInterface
     * @access protected
     */
    protected $procedureLoader;

    /**
     * Message factory instance
     *
     * @var \JonnyW\PhantomJSBundle\Message\MessageFactoryInterface
     * @access protected
     */
    protected $messageFactory;

    /**
     * Bin directory path.
     *
     * @var string
     * @access protected
     */
    protected $binDir;

    /**
     * Path to PhantomJSBundle executable
     *
     * @var string
     * @access protected
     */
    protected $phantomJs;

    /**
     * Path to PhantomJSBundle loader executable
     *
     * @var string
     * @access protected
     */
    protected $phantomLoader;

    /**
     * Debug.
     *
     * @var boolean
     * @access protected
     */
    protected $debug;

    /**
     * Log info
     *
     * @var array
     * @access protected
     */
    protected $log;

    /**
     * PhantomJSBundle run options
     *
     * @var mixed
     * @access protected
     */
    protected $options;

    /**
     * Internal constructor
     *
     * @access public
     *
     * @param \JonnyW\PhantomJSBundle\Procedure\ProcedureLoaderInterface $procedureLoader
     * @param \JonnyW\PhantomJSBundle\Message\MessageFactoryInterface    $messageFactory
     */
    public function __construct(ProcedureLoaderInterface $procedureLoader, MessageFactoryInterface $messageFactory)
    {
        $this->procedureLoader = $procedureLoader;
        $this->messageFactory  = $messageFactory;
        $this->binDir          = 'bin';
        $this->phantomJs       = '%s/phantomjs';
        $this->phantomLoader   = '%s/phantomloader';
        $this->options         = [];
    }

    /**
     * Get message factory instance
     *
     * @access public
     * @return \JonnyW\PhantomJSBundle\Message\MessageFactoryInterface
     */
    public function getMessageFactory()
    {
        return $this->messageFactory;
    }

    /**
     * Get procedure loader instance
     *
     * @access public
     * @return \JonnyW\PhantomJSBundle\Procedure\ProcedureLoaderInterface
     */
    public function getProcedureLoader()
    {
        return $this->procedureLoader;
    }

    /**
     * Send request
     *
     * @access public
     *
     * @param  \JonnyW\PhantomJSBundle\Message\RequestInterface  $request
     * @param  \JonnyW\PhantomJSBundle\Message\ResponseInterface $response
     *
     * @return \JonnyW\PhantomJSBundle\Message\ResponseInterface
     */
    public function send(RequestInterface $request, ResponseInterface $response)
    {
        $this->clearLog();

        $procedure = $this->procedureLoader->load($request->getType());
        $procedure->run($this, $request, $response);

        return $response;
    }

    /**
     * Get PhantomJSBundle run command with
     * loader and run options.
     *
     * @access public
     * @return string
     */
    public function getCommand()
    {
        $phantomJs     = $this->getPhantomJs();
        $phantomLoader = $this->getPhantomLoader();

        $this->validateExecutable($phantomJs);
        $this->validateExecutable($phantomLoader);

        $options = $this->getOptions();

        if ($this->debug)
        {
            array_push($options, '--debug=true');
        }

        return sprintf('%s %s %s', $phantomJs, implode(' ', $options), $phantomLoader);
    }

    /**
     * Set bin directory.
     *
     * @access public
     *
     * @param  string $path
     *
     * @return \JonnyW\PhantomJSBundle\Client
     */
    public function setBinDir($path)
    {
        $this->binDir = rtrim($path, '/\\');

        return $this;
    }

    /**
     * Get bin directory.
     *
     * @access public
     * @return string
     */
    public function getBinDir()
    {
        return $this->binDir;
    }

    /**
     * Set new PhantomJSBundle executable path.
     *
     * @access public
     *
     * @param  string $path
     *
     * @return \JonnyW\PhantomJSBundle\Client
     */
    public function setPhantomJs($path)
    {
        $this->validateExecutable($path);

        $this->phantomJs = $path;

        return $this;
    }

    /**
     * Get PhantomJSBundle executable path.
     *
     * @access public
     * @return string
     */
    public function getPhantomJs()
    {
        return sprintf($this->phantomJs, $this->getBinDir());
    }

    /**
     * Set PhantomJSBundle loader executable path.
     *
     * @access public
     *
     * @param  string $path
     *
     * @return \JonnyW\PhantomJSBundle\Client
     */
    public function setPhantomLoader($path)
    {
        $this->validateExecutable($path);

        $this->phantomLoader = $path;

        return $this;
    }

    /**
     * Get PhantomJSBundle loader executable path.
     *
     * @access public
     * @return string
     */
    public function getPhantomLoader()
    {
        return sprintf($this->phantomLoader, $this->getBinDir());
    }

    /**
     * Set PhantomJSBundle run options.
     *
     * @access public
     *
     * @param  array $options
     *
     * @return \JonnyW\PhantomJSBundle\Client
     */
    public function setOptions(array $options)
    {
        $this->options = $options;

        return $this;
    }

    /**
     * Get PhantomJSBundle run options.
     *
     * @access public
     * @return array
     */
    public function getOptions()
    {
        return (array)$this->options;
    }

    /**
     * Add single PhantomJSBundle run option.
     *
     * @access public
     *
     * @param  string $option
     *
     * @return \JonnyW\PhantomJSBundle\Client
     */
    public function addOption($option)
    {
        if (!in_array($option, $this->options))
        {
            $this->options[] = $option;
        }

        return $this;
    }

    /**
     * Debug.
     *
     * @access public
     *
     * @param  boolean $doDebug
     *
     * @return \JonnyW\PhantomJSBundle\Client
     */
    public function debug($doDebug)
    {
        $this->debug = $doDebug;

        return $this;
    }

    /**
     * Set log info.
     *
     * @access public
     *
     * @param  string $info
     *
     * @return \JonnyW\PhantomJSBundle\Client
     */
    public function setLog($info)
    {
        $this->log = $info;

        return $this;
    }

    /**
     * Get log info.
     *
     * @access public
     * @return string
     */
    public function getLog()
    {
        return $this->log;
    }

    /**
     * Clear log info.
     *
     * @access public
     * @return \JonnyW\PhantomJSBundle\Client
     */
    public function clearLog()
    {
        $this->log = '';

        return $this;
    }

    /**
     * Validate execuable file.
     *
     * @access private
     *
     * @param  string $file
     *
     * @return boolean
     * @throws \JonnyW\PhantomJSBundle\Exception\InvalidExecutableException
     */
    private function validateExecutable($file)
    {
        if (!file_exists($file) || !is_executable($file))
        {
            throw new InvalidExecutableException(sprintf('File does not exist or is not executable: %s', $file));
        }

        return true;
    }
}
