<?php

namespace SanSIS\Core\BaseBundle\Controller;

class UserController extends BaseController
{
    protected $service          = 'user.service';
    
    protected $indexView        = 'SanSISCoreBaseBundle:User:index.html.twig';
    
    protected $createView       = 'SanSISCoreBaseBundle:User:form.html.twig';
    protected $createRoute      = 'user_create';
    
    protected $editView         = 'SanSISCoreBaseBundle:User:form.html.twig';
    protected $editRoute        = 'user_edit';
    
    protected $saveSuccessRoute = 'user_index';
    
    protected $viewView         = 'SanSISCoreBaseBundle:User:formView.html.twig';
    protected $viewRoute        = 'user_view';
    
    protected $deleteRoute      = 'user_delete';
}
