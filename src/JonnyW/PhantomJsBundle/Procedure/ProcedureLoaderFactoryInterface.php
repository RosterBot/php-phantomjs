<?php

/*
 * This file is part of the php-phantomjs.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace JonnyW\PhantomJSBundle\Procedure;

/**
 * PHP PhantomJSBundle
 *
 * @author Jon Wenmoth <contact@jonnyw.me>
 */
interface ProcedureLoaderFactoryInterface
{
    /**
     * Create procedure loader instance.
     *
     * @access public
     *
*@param  string                                               $directory
     *
*@return \JonnyW\PhantomJSBundle\Procedure\ProcedureLoaderInterface
     */
    public function createProcedureLoader($directory);
}
