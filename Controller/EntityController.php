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
