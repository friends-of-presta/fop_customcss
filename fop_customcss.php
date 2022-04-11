<?php

/**
 * 2007-2020 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 *  @author    PrestaShop SA <contact@prestashop.com>
 *  @copyright 2007-2020 PrestaShop SA
 *  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

class Fop_customcss extends Module
{
    private $hooks = [
        'header',
        'backOfficeHeader',
    ];

    protected $css_file = 'views/css/fop_customcss.css';

    protected $config_form = false;


    public function __construct()
    {
        $this->name = 'fop_customcss';
        $this->tab = 'administration';
        $this->version = '1.1.1';
        $this->author = 'Friends of Presta';
        $this->need_instance = 0;

        /**
         * Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
         */
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('FOP - Custom CSS');
        $this->description = $this->l('Add some custom css for your template');

        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
    }

    /**
     * Don't forget to create update methods if needed:
     * http://doc.prestashop.com/display/PS16/Enabling+the+Auto-Update
     */
    public function install()
    {
        return parent::install() && $this->registerHook($this->hooks);
    }

    /**
     * Load the configuration form
     */
    public function getContent()
    {
        $notifcation = null;

        if (((bool) Tools::isSubmit('submitFop_customcssModule')) === true) {
            if ($this->postProcess()) {
                $notifcation =$this->displayConfirmation($this->l('Custom CSS saved !'));
            } else {
                $notifcation = $this->displayError($this->l('An error was occured during save.'));
            }
        }

        return $notifcation . $this->renderForm();
    }

    /**
     * Create the form that will be displayed in the configuration of your module.
     */
    protected function renderForm()
    {
        $helper = new HelperForm();

        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitFop_customcssModule';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            . '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFormValues(), /* Add values for your inputs */
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm(array($this->getConfigForm()));
    }

    /**
     * Create the structure of your form.
     *
     * @return array
     */
    protected function getConfigForm()
    {
        return array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Settings'),
                    'icon' => 'icon-cogs',
                ),
                'input' => array(

                    array(
                        'type' => 'textarea',
                        'id' => 'css_editor',
                        'prefix' => '<i class="icon icon-code"></i>',
                        'desc' => $this->l('Custom CSS'),
                        'name' => 'FOP_CUSTOMCSS_',
                        'label' => $this->l('Css'),
                    ),
                    array(
                        'type' => 'html',
                        'name' => '<textarea style="display:none" name="css_real_value">' . Tools::file_get_contents($this->getCssFile()) . '</textarea>',
                    ),

                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
            ),
        );
    }

    /**
     * Set values for the inputs.
     *
     * @return array
     */
    protected function getConfigFormValues()
    {
        return array(
            'FOP_CUSTOMCSS_' => Tools::file_get_contents($this->getCssFile()),
        );
    }

    /**
     * Save form data
     *
     * @return bool
     */
    protected function postProcess()
    {
        $result = false;
        $compiledCSS = Tools::getValue('css_real_value');
        $cssFilePath = $this->getCssFile();
        $version = (int) Configuration::get('PS_CCCCSS_VERSION');

        // Write CSS in file
        if (false !== file_put_contents($cssFilePath, $compiledCSS)) {
            $result = true;
        }

        //Update cache version of ccccss & return
        return $result && Configuration::updateValue('PS_CCCCSS_VERSION', ++$version);
    }

    /**
     * Add the CSS & JavaScript files you want to be loaded in the BO.
     */
    public function hookBackOfficeHeader()
    {
        if (Tools::getValue('configure') == $this->name) {
            $this->context->controller->addJquery();
            $this->context->controller->addJS([
                'https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.3/ace.js',
                $this->_path . 'views/js/back.js',
            ]);
        }
    }

    /**
     * Add the CSS & JavaScript files you want to be added on the FO.
     */
    public function hookHeader()
    {
        if (file_exists($this->getCssFile())) {
            $this->context->controller->addCSS($this->_path . $this->css_file);
        }
    }

    /**
     * Return CSS file path
     *
     * @return string
     */
    private function getCssFile()
    {
        return dirname(__FILE__) . DIRECTORY_SEPARATOR . $this->css_file;
    }
}
