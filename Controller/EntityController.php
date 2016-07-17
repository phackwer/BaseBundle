<<<<<<< HEAD
<?php

namespace SanSIS\Core\BaseBundle\Controller;

class EntityController extends BaseController
{
    protected $service          = 'entity.service';
    
    protected $indexView        = 'SanSISCoreBaseBundle:Entity:index.html.twig';
    
    protected $createView       = 'SanSISCoreBaseBundle:Entity:form.html.twig';
    protected $createRoute      = 'entity_create';
    
    protected $editView         = 'SanSISCoreBaseBundle:Entity:form.html.twig';
    protected $editRoute        = 'entity_edit';
    
    protected $saveSuccessRoute = 'entity_index';
    
    protected $viewView         = 'SanSISCoreBaseBundle:Entity:formView.html.twig';
    protected $viewRoute        = 'entity_view';
    
    protected $deleteRoute      = 'entity_delete';
}
=======
<?php

namespace SanSIS\Core\BaseBundle\Controller;

class EntityController extends BaseController
{
    protected $service = 'entity.service';

    protected $indexView = 'SanSISCoreBaseBundle:Entity:index.html.twig';

    protected $createView  = 'SanSISCoreBaseBundle:Entity:form.html.twig';
    protected $createRoute = 'entity_create';

    protected $editView  = 'SanSISCoreBaseBundle:Entity:form.html.twig';
    protected $editRoute = 'entity_edit';

    protected $saveSuccessRoute = 'entity_index';

    protected $viewView  = 'SanSISCoreBaseBundle:Entity:formView.html.twig';
    protected $viewRoute = 'entity_view';

    protected $deleteRoute = 'entity_delete';
}
>>>>>>> 0567d9a7991222d74a0abbf1de0631919494d6f4
