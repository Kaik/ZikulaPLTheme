<?php
/**
 * Copyright Zikula Foundation 2015 - Zikula Application Framework
 *
 * This work is contributed to the Zikula Foundation under one or more
 * Contributor Agreements and licensed to You under the following license:
 *
 * @license MIT
 * @package SpecTheme
 *
 * Please see the NOTICE file distributed with this source code for further
 * information regarding copyright and licensing.
 */

namespace Zikula\PLTheme\Twig;

use SessionUtil;

class PLThemeExtension extends \Twig_Extension
{
    public function __construct()
    {
    }

    public function getName()
    {
        return 'pltheme_extension';
    }

    public function getFunctions()
    {
        return ['isloggedin' => new \Twig_Function_Method($this, 'isLoggedIn', array('is_safe' => array('html')))
        ];
    }
    
    

    /**
     * @param $string
     * @return string
     */
    public function isLoggedIn()
    {
        return (bool)SessionUtil::getVar('uid');
    }
}