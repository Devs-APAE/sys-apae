<?php
/**
 * WelcomeView
 *
 * @version    8.0
 * @package    control
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    https://adiantiframework.com.br/license-template
 */
class WelcomeView extends TPage
{
    /**
     * Class constructor
     * Creates the page
     */
    function __construct()
    {
        parent::__construct();
        
        // create the HTML Renderer
        $this->html = new THtmlRenderer('app/resources/jumbotron.html');
        
        $ini = AdiantiApplicationConfig::get();
        
        $replaces = ['title' => 'Bem-vindo(a) ao sistema da APAE!',
                     'content' => 'Facilitando o cuidado com organização, inclusão e respeito. 💙'];
        
        // replace the main section variables
        $this->html->enableSection('main', $replaces);
        
        parent::add( $this->html );
    }
}
