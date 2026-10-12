<?php
if (!defined('_PS_VERSION_')) {
    exit;
}

require_once dirname(__FILE__).'/sanitize.php';

class BlckPaid extends Module
{
    public function __construct()
    {
        $this->name = 'blckpaid';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Latoutfrancais - Arnaud Merigeau';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = array('min' => '1.7.0.0', 'max' => '9.99.99');
        $this->bootstrap = true;
        parent::__construct();
        $this->displayName = $this->l('BLCK Paid');
        $this->description = $this->l('Paid options for the BLCK theme. Colors and fonts stay in BLCK Config.');
    }

    public function install()
    {
        return parent::install()
            && $this->registerHook('actionFrontControllerSetMedia')
            && $this->registerHook('displayBanner')
            && $this->registerHook('displayBeforeBodyClosingTag')
            && Configuration::updateValue('BLCKPAID_BAR_ENABLED', 0)
            && Configuration::updateValue('BLCKPAID_BAR_TEXT', '')
            && Configuration::updateValue('BLCKPAID_BAR_URL', '')
            && Configuration::updateValue('BLCKPAID_BAR_BLANK', 0)
            && Configuration::updateValue('BLCKPAID_BAR_BG', '#111111')
            && Configuration::updateValue('BLCKPAID_BAR_COLOR', '#FFFFFF')
            && Configuration::updateValue('BLCKPAID_HOVER', 1)
            && Configuration::updateValue('BLCKPAID_STICKY', 1)
            && Configuration::updateValue('BLCKPAID_OPC', 1)
            && $this->generateCssFile();
    }

    public function uninstall()
    {
        foreach (array(
            'BLCKPAID_BAR_ENABLED',
            'BLCKPAID_BAR_TEXT',
            'BLCKPAID_BAR_URL',
            'BLCKPAID_BAR_BLANK',
            'BLCKPAID_BAR_BG',
            'BLCKPAID_BAR_COLOR',
            'BLCKPAID_HOVER',
            'BLCKPAID_STICKY',
            'BLCKPAID_OPC',
        ) as $key) {
            Configuration::deleteByName($key);
        }

        return parent::uninstall();
    }

    public function getContent()
    {
        $saved = false;
        $error = '';
        if (Tools::isSubmit('submitBlckpaid')) {
            $error = $this->saveForm();
            $saved = ($error === '');
        }

        $this->context->controller->addCSS($this->_path.'views/css/admin.css');
        $this->context->controller->addJS($this->_path.'views/js/admin.js');

        $configLink = $this->context->link->getAdminLink('AdminModules').'&configure=blckconfig';
        $this->context->smarty->assign(array(
            'blckpaid' => $this->settings(),
            'blckpaid_action' => $this->context->link->getAdminLink('AdminModules').'&configure='.$this->name,
            'blckpaid_saved' => $saved,
            'blckpaid_error' => $error,
            'blckpaid_config_link' => Module::isInstalled('blckconfig') ? $configLink : '',
            'blckpaid_labels' => array(
                'title' => $this->l('BLCK Paid'),
                'intro' => $this->l('Paid options for the BLCK theme. Colors and fonts stay in BLCK Config.'),
                'config_link' => $this->l('Open BLCK Config'),
                'bar' => $this->l('Announcement bar'),
                'bar_help' => $this->l('A line above the header. Leave it off until the sentence is ready.'),
                'show_bar' => $this->l('Show the bar'),
                'sentence' => $this->l('Sentence'),
                'link' => $this->l('Link'),
                'blank' => $this->l('Open in a new tab'),
                'background' => $this->l('Background'),
                'text' => $this->l('Text'),
                'preview_off' => $this->l('The bar stays hidden until you turn it on.'),
                'preview_empty' => $this->l('Write the sentence to see the bar.'),
                'contrast' => $this->l('This pair is hard to read.'),
                'hover' => $this->l('Second image'),
                'hover_help' => $this->l('On a category or the home page, the next photo replaces the cover while the pointer is over the product.'),
                'hover_on' => $this->l('Enable the hover image'),
                'sticky' => $this->l('Sticky add to cart'),
                'sticky_help' => $this->l('On a phone, the price and the add button stay at the bottom of the product page, and hide while the real button is on screen.'),
                'sticky_on' => $this->l('Enable the sticky bar'),
                'checkout' => $this->l('Checkout'),
                'checkout_help' => $this->l('When PrestaShop 9.2 one-page checkout is on, its buttons and focus rings use the BLCK button colors.'),
                'checkout_on' => $this->l('Use BLCK colors on the checkout'),
                'save' => $this->l('Save'),
                'saved' => $this->l('Saved.'),
            ),
        ));

        return $this->display(__FILE__, 'admin/configure.tpl');
    }

    public function hookActionFrontControllerSetMedia()
    {
        $this->context->controller->registerStylesheet(
            'blckpaid-front',
            'modules/'.$this->name.'/views/css/front.css',
            array('media' => 'all', 'priority' => 160)
        );
        if (file_exists(_PS_MODULE_DIR_.$this->name.'/blckpaid.css')) {
            $this->context->controller->registerStylesheet(
                'blckpaid-generated',
                'modules/'.$this->name.'/blckpaid.css',
                array('media' => 'all', 'priority' => 161)
            );
        }
        $settings = $this->settings();
        $this->context->smarty->assign(array(
            'blck_paid_hover' => (bool) $settings['hover'],
        ));
    }

    public function hookDisplayBanner()
    {
        $settings = $this->settings();
        if (!$settings['bar_enabled'] || $settings['bar_text'] === '') {
            return '';
        }
        $this->context->smarty->assign(array(
            'blckpaid_bar' => $settings,
            'blckpaid_bar_key' => substr(md5($settings['bar_text'].$settings['bar_url']), 0, 12),
        ));

        return $this->fetch('module:blckpaid/views/templates/hook/announcement.tpl');
    }

    public function hookDisplayBeforeBodyClosingTag()
    {
        if (!$this->settings()['sticky']) {
            return '';
        }
        if (!isset($this->context->controller->php_self) || $this->context->controller->php_self !== 'product') {
            return '';
        }

        return $this->fetch('module:blckpaid/views/templates/hook/sticky.tpl');
    }

    private function saveForm()
    {
        $bg = Tools::getValue('BLCKPAID_BAR_BG');
        $color = Tools::getValue('BLCKPAID_BAR_COLOR');
        if (!preg_match('/^#[0-9A-Fa-f]{6}$/', trim((string) $bg)) || !preg_match('/^#[0-9A-Fa-f]{6}$/', trim((string) $color))) {
            return $this->l('Check the colors. Each one needs a full hex value, like #111111.');
        }

        Configuration::updateValue('BLCKPAID_BAR_ENABLED', (int) Tools::getValue('BLCKPAID_BAR_ENABLED') ? 1 : 0);
        Configuration::updateValue('BLCKPAID_BAR_TEXT', blckpaid_text(Tools::getValue('BLCKPAID_BAR_TEXT')));
        Configuration::updateValue('BLCKPAID_BAR_URL', blckpaid_url(Tools::getValue('BLCKPAID_BAR_URL')));
        Configuration::updateValue('BLCKPAID_BAR_BLANK', (int) Tools::getValue('BLCKPAID_BAR_BLANK') ? 1 : 0);
        Configuration::updateValue('BLCKPAID_BAR_BG', blckpaid_hex($bg, '#111111'));
        Configuration::updateValue('BLCKPAID_BAR_COLOR', blckpaid_hex($color, '#FFFFFF'));
        Configuration::updateValue('BLCKPAID_HOVER', (int) Tools::getValue('BLCKPAID_HOVER') ? 1 : 0);
        Configuration::updateValue('BLCKPAID_STICKY', (int) Tools::getValue('BLCKPAID_STICKY') ? 1 : 0);
        Configuration::updateValue('BLCKPAID_OPC', (int) Tools::getValue('BLCKPAID_OPC') ? 1 : 0);
        $this->generateCssFile();

        return '';
    }

    private function settings()
    {
        return array(
            'bar_enabled' => (int) Configuration::get('BLCKPAID_BAR_ENABLED'),
            'bar_text' => (string) Configuration::get('BLCKPAID_BAR_TEXT'),
            'bar_url' => (string) Configuration::get('BLCKPAID_BAR_URL'),
            'bar_blank' => (int) Configuration::get('BLCKPAID_BAR_BLANK'),
            'bar_bg' => blckpaid_hex(Configuration::get('BLCKPAID_BAR_BG'), '#111111'),
            'bar_color' => blckpaid_hex(Configuration::get('BLCKPAID_BAR_COLOR'), '#FFFFFF'),
            'hover' => (int) Configuration::get('BLCKPAID_HOVER'),
            'sticky' => (int) Configuration::get('BLCKPAID_STICKY'),
            'opc' => (int) Configuration::get('BLCKPAID_OPC'),
        );
    }

    private function generateCssFile()
    {
        $settings = $this->settings();
        $css = ".blck-announce{background:".$settings['bar_bg'].";color:".$settings['bar_color'].";}\n";
        $css .= ".blck-announce a,.blck-announce button{color:inherit;}\n";
        if ($settings['opc']) {
            $css .= ".one-page-checkout #opc-pay-button,.one-page-checkout .btn-primary{background:var(--blck-btn-primary-bg,#111);color:var(--blck-btn-primary-text,#fff);border-color:transparent;}\n";
            $css .= ".one-page-checkout #opc-pay-button:hover,.one-page-checkout .btn-primary:hover{background:var(--blck-btn-primary-bg-hover,#fba000);color:var(--blck-btn-primary-text-hover,#fff);}\n";
            $css .= ".one-page-checkout input:focus,.one-page-checkout select:focus,.one-page-checkout textarea:focus{outline:2px solid var(--blck-main-color,#111);outline-offset:1px;}\n";
            $css .= ".one-page-checkout a{color:var(--blck-main-color,#111);}\n";
        }
        file_put_contents(_PS_MODULE_DIR_.$this->name.'/blckpaid.css', $css);

        return true;
    }
}
