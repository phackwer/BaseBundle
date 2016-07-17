<?php

namespace SanSIS\Core\BaseBundle\Controller;

class ProfileController extends BaseController
{
    protected $service          = 'profile.service';
    
    protected $indexView        = 'SanSISCoreBaseBundle:Profile:index.html.twig';
    
    protected $createView       = 'SanSISCoreBaseBundle:Profile:form.html.twig';
    protected $createRoute      = 'profile_create';
    
    protected $editView         = 'SanSISCoreBaseBundle:Profile:form.html.twig';
    protected $editRoute        = 'profile_edit';
    
    protected $saveSuccessRoute = 'profile_index';
    
    protected $viewView         = 'SanSISCoreBaseBundle:Profile:formView.html.twig';
    protected $viewRoute        = 'profile_view';
    
    protected $deleteRoute      = 'profile_delete';
}
