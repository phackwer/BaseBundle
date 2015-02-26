# README

This Bundle for Symfony solves some of the most basic issues on web development at SanSIS. Currently, there are over 15 projects using it directly on the brazilian government.

A responsive interface, an easy to use way to create cruds (automation on the way), user administration, and the mini data-mining bundle plus the messaging bundle provides eveything small enterprises look for when trying to reduce costs on their development.

Serving as base for the development, it forces the programmer to adopt a lot of good practices and to follow standards for their codind.

## Requires

```json
        "friendsofsymfony/jsrouting-bundle" : "1.4.*@dev",
        "braincrafted/bootstrap-bundle" : "2.0.*",
        "twitter/bootstrap" : "3.1.*",
        "knplabs/knp-menu-bundle" : "1.1.2",
        "knplabs/knp-paginator-bundle" : "~2.4",
        "psliwa/pdf-bundle" : "dev-master"
```

## Use it in another project

composer.json:
```json
    [...]
    "require" : {
        [...]
        "sansis/basebundle" : "dev-master"
    },
    "repositories" : [{
        "type" : "vcs",
        "url" : "https://github.com/phackwer/BaseBundle.git"
    }],
    [...]
```

## Add to AppKernel

```php
         //SanSIS Core Production Bundles
         new SanSIS\Core\BaseBundle\SanSISCoreBaseBundle(),
```

## Add to routing.yml

```yml
san_sis_core_base:
    resource: "@SanSISCoreBaseBundle/Resources/config/routing.yml"
    prefix:   /
```

## Extend your twigs

Use {% extends "SanSISCoreBaseBundle::base.html.twig" %} on your twig templates to work ok
Remember you can change a lot of blocks. Check the resource and modify the blocks you want.

## Extend your AppKernel

Change your kernel to extend from the one provided by BaseBundle.

Change:

```php
use Symfony\Component\HttpKernel\Kernel;
```

To:

```php
use SanSIS\Core\BaseBundle\Component\HttpKernel\Kernel;
```

## Customize your base.html.twig to extend from BaseBundle

This is a sample on how to create your own visual identity and the main blocks of code
for your page layout

{% extends "SanSISCoreBaseBundle::base.html.twig" %}

{% block css %}
<link type="text/css" rel="stylesheet" href="{{ asset('bundles/yourproject/css/project.css') }}" />
{% endblock %}

{% block title %}Company's name - System's name{% endblock %}

{% block sigla_sistema %}SYS{% endblock %}
{% block descricao_sistema %}System's name{% endblock %}

{% block entidade_sistema %}Entity's name{% endblock %}
{% block info_sessao %}Anything you want{% endblock %}

{% block page_menu %}{{ knp_menu_render('YourProjectBundle:Builder:menu', {'nav_type': 'navbar', 'template':'SanSISCoreBaseBundle:menu:sansis_menu.html.twig'}) }}{% endblock %}

{% block copyright_footer %}
&copy; Company's name
{% endblock %}

{% block javascript_libs %}
<script type="text/javascript" src="{{ asset('bundles/yourproject/js/functions.js') }}"></script>
{% endblock %}

## Use the CRUD structure of BaseBundle for powerfull and fast development!

The most amazing and useful function of BaseBundle is the Crud Infrastructure created for it.

You may use it for complex entities with a huge number of inner entities (that can have their own inner entity as well!).

For this, you must declare the @innerEntity annotation on top of vars that are ArrayCollections from Doctrine project.

And your form field's names must follow the object infrastructure.

Here are the objects you must extend from in order to get it working:

- Controllers: \SanSIS\Core\BaseBundle\Controller\ControllerCrudAbstract
- Services: \SanSIS\Core\BaseBundle\Service\EntityServiceAbstract
- Entities: \SanSIS\Core\BaseBundle\Entity\AbstractBase
- Repositories: \SanSIS\Core\BaseBundle\EntityRepository\AbstractBase