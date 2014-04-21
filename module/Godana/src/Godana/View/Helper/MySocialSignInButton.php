<?php
namespace Godana\View\Helper;

use Zend\View\Helper\AbstractHelper;

class MySocialSignInButton extends AbstractHelper
{
    public function __invoke($provider, $redirect = false)
    {
        $redirectArg = $redirect ? '?redirect=' . $redirect : '';    
        $translate = $this->view->plugin('translate');    
        echo
            '<a class="btn btn-' . $provider . '" href="'
            . $this->view->url('scn-social-auth-user/login/provider', array('provider' => $provider))
            . $redirectArg . '"><i class="icon-' . $provider . '"></i></a>';
    }
}