/**
 * Classe criada para complementar a navegação entre abas através de botões.
 * A navegação entre abas é feita pelo bootstrap( http://getbootstrap.com/javascript/#tabs )
 *
 * ############# ATENÇÃO ##############
 * ####################################
 * Para funcionar corretamente o as <div class="tab-pane ... do arquivo form.html.twig
 * devem ter ids iguais aos dos métodos:
 *
 * $item = $menu->addChild('Dados Iniciais', array(
        'uri' => '#tab1',
        'linkAttributes' => array(
            'id' => 'idT1',
            'data-toggle' => 'tab',
        )
   ));
 *
 * que ficam em Menu/TabsCadastroProfissional.php
 *
 */
var nav = {

    activeTab:        '',
    activeTabContent: '',
    style:            { 'margin': '0px 200px' },
    validation:       function() { return true; },

    init: function() {
        $('.navigation a').css(this.style);

        this.activeTab        = $('ul.nav-tabs li.active');
        this.activeTabContent = $('.tab-content div.active');

        this.handleButtons();
        this.events();
    },

    events: function() {
        $('.navigation a').click(this.handleButtonNavigation);
        $('.navbar-nav li').click(this.handleTabsNavigation);
    },

    handleTabsNavigation: function() {
        nav.setTabAndContentActive(true);
    },

    handleButtonNavigation: function() {
        if($(this).attr('id') == 'next'){
            nav.next();
        }else{
            nav.prev();
        }
    },

    removeActiveClass: function() {
        nav.activeTab.removeClass('active');
        nav.activeTabContent.removeClass('active');
    },

    next: function() {
        if (nav.validation()) {
            nav.removeActiveClass();
            nav.activeTab        = nav.activeTab.next().addClass('active');
            nav.activeTabContent = nav.activeTabContent.next().addClass('active');
            nav.handleButtons();
        }
    },

    prev: function() {
        nav.removeActiveClass();
        nav.activeTab        = nav.activeTab.prev().addClass('active');
        nav.activeTabContent = nav.activeTabContent.prev().addClass('active');
        nav.handleButtons();
    },

    setTabAndContentActive: function(clickFromTab) {

        if(clickFromTab) {
            $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                var tabId  = $(e.target).prop('id');  // pego o ID da aba que foi clicada
                var target = tabId.match(/[\d]+/);    // retiro o que não é número do ID
                var tabContentId = 'tab' + target[0]; // agora tenho o ID do conteúdo relacionado à aba

                nav.activeTab        = $('ul.nav-tabs li a#' + tabId).parent();
                nav.activeTabContent = $('div.tab-content div#' + tabContentId);

                nav.handleButtons();
            });
        }
    },

    handleButtons: function() {
        var showAll = true;
        if(nav.activeTab.hasClass('first')){
            $('#prev').hide();
            $('#next').show();
            showAll = false;
        }
        if(nav.activeTab.hasClass('last')){
            $('#next').hide();
            $('#prev').show();
            showAll = false;
        }

        if(showAll){
            $('#prev').show();
            $('#next').show();
        }
    },

    setValidation: function(metodo) {
        this.validation = metodo;
    }
}