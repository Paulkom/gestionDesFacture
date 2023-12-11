<?php

namespace App\Twig\Extensions;

use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;
use Twig\Extension\AbstractExtension;

use Twig\TwigFunction;

class AjaxifySearchTwigExtension extends AbstractExtension
{
    /**
     * @var RouterInterface $router
     */
    protected $router;

    /**
     * @var Environment $twig
     */
    private $twig;

    public function __construct(RouterInterface $router, Environment $twig)
    {
        $this->router = $router;
        $this->twig = $twig;
    }

    public function getFunctions()
    {
        return array(
            'init_ajaxify_search'  => new TwigFunction(
                'init_ajaxify_search',
                array($this, 'initAjaxifySearch'),
                array('is_safe' => array('html'))
            ),
        );
    }

    public function initAjaxifySearch()
    {
        $template =  '<script>AjaxifySearch.URL = {';
        $template .= 'count: "{{ count_url }}",';
        $template .= 'search: "{{ search_url }}"';
        $template .= '}';
        $template .= '</script>';
        //dd($this->router->generate('ajaxify_search_count_all'), $this->router->generate('ajaxify_search_search')); 
        dd($this->twig->createTemplate($template)->render(array(
            'count_url'     => "gestionFacture/public/ajaxify-search/count", //$this->router->generate('ajaxify_search_count_all'),
            'search_url'    => "gestionFacture/public/ajaxify-search/search", //$this->router->generate('ajaxify_search_search'),
        )));
        return $this->twig->createTemplate($template)->render(array(
            'count_url'     => "gestionFacture/public/ajaxify-search/count", //$this->router->generate('ajaxify_search_count_all'),
            'search_url'    => "gestionFacture/public/ajaxify-search/search", //$this->router->generate('ajaxify_search_search'),
        ));
    }

    public function getName()
    {
        return 'ajaxify_search';
    }
}