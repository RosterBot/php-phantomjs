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
interface ProcedureFactoryInterface
{
    /**
     * Create new procedure instance.
     *
     * @access public
     * @return \JonnyW\PhantomJSBundle\Procedure\ProcedureInterface
     */
    public function createProcedure();
}
