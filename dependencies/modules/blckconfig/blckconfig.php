<?php
if (!defined('_PS_VERSION_')) {
    exit;
}

class BlckConfig extends Module
{
    public function __construct()
    {
        $this->name = 'blckconfig';
        $this->tab = 'administration';
        $this->version = '1.0.0';
        $this->author = 'Latoutfrancais - Arnaud Merigeau';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = array('min' => '1.7.0.0', 'max' => _PS_VERSION_);
        $this->bootstrap = true;
        parent::__construct();
        $this->displayName = $this->l('BLCK config');
        $this->description = $this->l('Template configuration.');
    }

    public function install()
    {
        $ok = parent::install()
            && $this->registerHook('actionFrontControllerSetMedia');
        if ($ok) {
            Configuration::updateValue('BLCKCONFIG_TITLE_FONT', 'Arial');
            Configuration::updateValue('BLCKCONFIG_TEXT_FONT', 'Arial');
            Configuration::updateValue('BLCKCONFIG_MAIN_COLOR', '#000000');
            Configuration::updateValue('BLCKCONFIG_SECONDARY_COLOR', '#fba000');
            Configuration::updateValue('BLCKCONFIG_BG_COLOR', '#f6f6f6');
            Configuration::updateValue('BLCKCONFIG_BTN_PRIMARY_BG', '#000000');
            Configuration::updateValue('BLCKCONFIG_BTN_PRIMARY_TEXT', '#FFFFFF');
            Configuration::updateValue('BLCKCONFIG_BTN_SECONDARY_BG', '#F6F6F6');
            Configuration::updateValue('BLCKCONFIG_BTN_SECONDARY_TEXT', '#000000');
            Configuration::updateValue('BLCKCONFIG_BTN_PRIMARY_BG_HOVER', '#fba000');
            Configuration::updateValue('BLCKCONFIG_BTN_PRIMARY_TEXT_HOVER', '#FFFFFF');
            Configuration::updateValue('BLCKCONFIG_BTN_SECONDARY_BG_HOVER', '#dddddd');
            Configuration::updateValue('BLCKCONFIG_BTN_SECONDARY_TEXT_HOVER', '#000000');
            Configuration::updateValue('BLCKCONFIG_FLAG_BG', '#000000');
            Configuration::updateValue('BLCKCONFIG_FLAG_TEXT', '#FFFFFF');
        }
        return $ok;
    }

    public function uninstall()
    {
        return parent::uninstall();
    }

    public function getContent()
    {
        $output = '';
        if (Tools::isSubmit('submitBlckconfig')) {
            Configuration::updateValue('BLCKCONFIG_TITLE_FONT', Tools::getValue('BLCKCONFIG_TITLE_FONT', 'Arial'));
            Configuration::updateValue('BLCKCONFIG_TEXT_FONT', Tools::getValue('BLCKCONFIG_TEXT_FONT', 'Arial'));
            Configuration::updateValue('BLCKCONFIG_MAIN_COLOR', Tools::getValue('BLCKCONFIG_MAIN_COLOR', '#000000'));
            Configuration::updateValue('BLCKCONFIG_SECONDARY_COLOR', Tools::getValue('BLCKCONFIG_SECONDARY_COLOR', '#fba000'));
            Configuration::updateValue('BLCKCONFIG_BG_COLOR', Tools::getValue('BLCKCONFIG_BG_COLOR', '#f6f6f6'));
            Configuration::updateValue('BLCKCONFIG_BTN_PRIMARY_BG', Tools::getValue('BLCKCONFIG_BTN_PRIMARY_BG', '#000000'));
            Configuration::updateValue('BLCKCONFIG_BTN_PRIMARY_TEXT', Tools::getValue('BLCKCONFIG_BTN_PRIMARY_TEXT', '#FFFFFF'));
            Configuration::updateValue('BLCKCONFIG_BTN_SECONDARY_BG', Tools::getValue('BLCKCONFIG_BTN_SECONDARY_BG', '#F6F6F6'));
            Configuration::updateValue('BLCKCONFIG_BTN_SECONDARY_TEXT', Tools::getValue('BLCKCONFIG_BTN_SECONDARY_TEXT', '#000000'));
            Configuration::updateValue('BLCKCONFIG_BTN_PRIMARY_BG_HOVER', Tools::getValue('BLCKCONFIG_BTN_PRIMARY_BG_HOVER', '#fba000'));
            Configuration::updateValue('BLCKCONFIG_BTN_PRIMARY_TEXT_HOVER', Tools::getValue('BLCKCONFIG_BTN_PRIMARY_TEXT_HOVER', '#FFFFFF'));
            Configuration::updateValue('BLCKCONFIG_BTN_SECONDARY_BG_HOVER', Tools::getValue('BLCKCONFIG_BTN_SECONDARY_BG_HOVER', '#dddddd'));
            Configuration::updateValue('BLCKCONFIG_BTN_SECONDARY_TEXT_HOVER', Tools::getValue('BLCKCONFIG_BTN_SECONDARY_TEXT_HOVER', '#000000'));
            Configuration::updateValue('BLCKCONFIG_FLAG_BG', Tools::getValue('BLCKCONFIG_FLAG_BG', '#000000'));
            Configuration::updateValue('BLCKCONFIG_FLAG_TEXT', Tools::getValue('BLCKCONFIG_FLAG_TEXT', '#FFFFFF'));
            $output .= $this->displayConfirmation($this->l('Configuration enregistrée.'));
            $this->generateCssFile();
        }
        $output .= $this->renderForm();
        return $output;
    }

    public function hookActionFrontControllerSetMedia($params)
    {
        if (file_exists(_PS_MODULE_DIR_.$this->name.'/blck.css')) {
            $this->context->controller->registerStylesheet(
                'blck',
                'modules/'.$this->name.'/blck.css',
                [
                    'media' => 'all',
                    'priority' => 150
                ]
            );
        }
    }

    private function renderForm()
    {
        $default_lang = (int)Configuration::get('PS_LANG_DEFAULT');
        $system_fonts = [
            ['id' => 'Arial', 'name' => 'Arial'],
            ['id' => 'Verdana', 'name' => 'Verdana'],
            ['id' => 'Tahoma', 'name' => 'Tahoma'],
            ['id' => 'Trebuchet MS', 'name' => 'Trebuchet MS'],
            ['id' => 'Times New Roman', 'name' => 'Times New Roman'],
            ['id' => 'Georgia', 'name' => 'Georgia'],
            ['id' => 'Garamond', 'name' => 'Garamond'],
            ['id' => 'Courier New', 'name' => 'Courier New'],
            ['id' => 'Brush Script MT', 'name' => 'Brush Script MT'],
        ];
        $fields_form = [
            'form' => [
                'legend' => [
                    'title' => $this->l('BLCK Theme Configuration'),
                    'icon' => 'icon-cogs'
                ],
                'input' => [
                    [
                        'type' => 'html',
                        'name' => 'blckconfig_buttons_title',
                        'html_content' => '<div style="font-weight:bold;font-size:1.1em;margin-top:1.5em;margin-bottom:0.5em;">'.$this->l('Fonts').'</div>',
                    ],
                    [
                        'type' => 'select',
                        'label' => $this->l('Title font'),
                        'name' => 'BLCKCONFIG_TITLE_FONT',
                        'options' => [
                            'query' => $system_fonts,
                            'id' => 'id',
                            'name' => 'name',
                        ],
                    ],
                    [
                        'type' => 'select',
                        'label' => $this->l('Text font'),
                        'name' => 'BLCKCONFIG_TEXT_FONT',
                        'options' => [
                            'query' => $system_fonts,
                            'id' => 'id',
                            'name' => 'name',
                        ],
                    ],
                    [
                        'type' => 'color',
                        'label' => $this->l('Main color'),
                        'name' => 'BLCKCONFIG_MAIN_COLOR',
                    ],
                    [
                        'type' => 'color',
                        'label' => $this->l('Secondary color'),
                        'name' => 'BLCKCONFIG_SECONDARY_COLOR',
                    ],
                    [
                        'type' => 'color',
                        'label' => $this->l('Background color'),
                        'name' => 'BLCKCONFIG_BG_COLOR',
                    ],
                    [
                        'type' => 'html',
                        'name' => 'blckconfig_buttons_title',
                        'html_content' => '<div style="font-weight:bold;font-size:1.1em;margin-top:1.5em;margin-bottom:0.5em;">'.$this->l('Primary button').'</div>',
                    ],
                    [
                        'type' => 'color',
                        'label' => $this->l('Primary button background'),
                        'name' => 'BLCKCONFIG_BTN_PRIMARY_BG',
                    ],
                    [
                        'type' => 'color',
                        'label' => $this->l('Primary button text'),
                        'name' => 'BLCKCONFIG_BTN_PRIMARY_TEXT',
                    ],
                    [
                        'type' => 'color',
                        'label' => $this->l('Primary button background (hover)'),
                        'name' => 'BLCKCONFIG_BTN_PRIMARY_BG_HOVER',
                    ],
                    [
                        'type' => 'color',
                        'label' => $this->l('Primary button text (hover)'),
                        'name' => 'BLCKCONFIG_BTN_PRIMARY_TEXT_HOVER',
                    ],
                    [
                        'type' => 'html',
                        'name' => 'blckconfig_buttons_title',
                        'html_content' => '<div style="font-weight:bold;font-size:1.1em;margin-top:1.5em;margin-bottom:0.5em;">'.$this->l('Secondary button').'</div>',
                    ],
                    [
                        'type' => 'color',
                        'label' => $this->l('Secondary button background'),
                        'name' => 'BLCKCONFIG_BTN_SECONDARY_BG',
                    ],
                    [
                        'type' => 'color',
                        'label' => $this->l('Secondary button text'),
                        'name' => 'BLCKCONFIG_BTN_SECONDARY_TEXT',
                    ],
                    [
                        'type' => 'color',
                        'label' => $this->l('Secondary button background (hover)'),
                        'name' => 'BLCKCONFIG_BTN_SECONDARY_BG_HOVER',
                    ],
                    [
                        'type' => 'color',
                        'label' => $this->l('Secondary button text (hover)'),
                        'name' => 'BLCKCONFIG_BTN_SECONDARY_TEXT_HOVER',
                    ],
                    [
                        'type' => 'html',
                        'name' => 'blckconfig_buttons_title',
                        'html_content' => '<div style="font-weight:bold;font-size:1.1em;margin-top:1.5em;margin-bottom:0.5em;">'.$this->l('Flags').'</div>',
                    ],
                    [
                        'type' => 'color',
                        'label' => $this->l('Flag background'),
                        'name' => 'BLCKCONFIG_FLAG_BG',
                    ],
                    [
                        'type' => 'color',
                        'label' => $this->l('Flag text'),
                        'name' => 'BLCKCONFIG_FLAG_TEXT',
                    ],
                ],
                'submit' => [
                    'title' => $this->l('Save'),
                    'class' => 'btn btn-default pull-right'
                ]
            ]
        ];
        $helper = new HelperForm();
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
        $helper->default_form_language = $default_lang;
        $helper->allow_employee_form_lang = $default_lang;
        $helper->title = $this->displayName;
        $helper->show_toolbar = false;
        $helper->submit_action = 'submitBlckconfig';
        $helper->fields_value = [
            'BLCKCONFIG_TITLE_FONT' => Configuration::get('BLCKCONFIG_TITLE_FONT', 'Arial'),
            'BLCKCONFIG_TEXT_FONT' => Configuration::get('BLCKCONFIG_TEXT_FONT', 'Arial'),
            'BLCKCONFIG_MAIN_COLOR' => Configuration::get('BLCKCONFIG_MAIN_COLOR', '#000000'),
            'BLCKCONFIG_SECONDARY_COLOR' => Configuration::get('BLCKCONFIG_SECONDARY_COLOR', '#fba000'),
            'BLCKCONFIG_BG_COLOR' => Configuration::get('BLCKCONFIG_BG_COLOR', '#f6f6f6'),
            'BLCKCONFIG_BTN_PRIMARY_BG' => Configuration::get('BLCKCONFIG_BTN_PRIMARY_BG', '#000000'),
            'BLCKCONFIG_BTN_PRIMARY_TEXT' => Configuration::get('BLCKCONFIG_BTN_PRIMARY_TEXT', '#FFFFFF'),
            'BLCKCONFIG_BTN_PRIMARY_BG_HOVER' => Configuration::get('BLCKCONFIG_BTN_PRIMARY_BG_HOVER', '#fba000'),
            'BLCKCONFIG_BTN_PRIMARY_TEXT_HOVER' => Configuration::get('BLCKCONFIG_BTN_PRIMARY_TEXT_HOVER', '#FFFFFF'),
            'BLCKCONFIG_BTN_SECONDARY_BG' => Configuration::get('BLCKCONFIG_BTN_SECONDARY_BG', '#F6F6F6'),
            'BLCKCONFIG_BTN_SECONDARY_TEXT' => Configuration::get('BLCKCONFIG_BTN_SECONDARY_TEXT', '#000000'),
            'BLCKCONFIG_BTN_SECONDARY_BG_HOVER' => Configuration::get('BLCKCONFIG_BTN_SECONDARY_BG_HOVER', '#dddddd'),
            'BLCKCONFIG_BTN_SECONDARY_TEXT_HOVER' => Configuration::get('BLCKCONFIG_BTN_SECONDARY_TEXT_HOVER', '#000000'),
            'BLCKCONFIG_FLAG_BG' => Configuration::get('BLCKCONFIG_FLAG_BG', '#000000'),
            'BLCKCONFIG_FLAG_TEXT' => Configuration::get('BLCKCONFIG_FLAG_TEXT', '#FFFFFF'),
        ];
        return $helper->generateForm([$fields_form]);
    }

    private function generateCssFile()
    {
        $title_font = Configuration::get('BLCKCONFIG_TITLE_FONT', 'Arial');
        $text_font = Configuration::get('BLCKCONFIG_TEXT_FONT', 'Arial');
        $main_color = Configuration::get('BLCKCONFIG_MAIN_COLOR', '#000000');
        $secondary_color = Configuration::get('BLCKCONFIG_SECONDARY_COLOR', '#fba000');
        $bg_color = Configuration::get('BLCKCONFIG_BG_COLOR', '#f6f6f6');
        $btn_primary_bg = Configuration::get('BLCKCONFIG_BTN_PRIMARY_BG', '#000000');
        $btn_primary_text = Configuration::get('BLCKCONFIG_BTN_PRIMARY_TEXT', '#FFFFFF');
        $btn_primary_bg_hover = Configuration::get('BLCKCONFIG_BTN_PRIMARY_BG_HOVER', '#fba000');
        $btn_primary_text_hover = Configuration::get('BLCKCONFIG_BTN_PRIMARY_TEXT_HOVER', '#FFFFFF');
        $btn_secondary_bg = Configuration::get('BLCKCONFIG_BTN_SECONDARY_BG', '#F6F6F6');
        $btn_secondary_text = Configuration::get('BLCKCONFIG_BTN_SECONDARY_TEXT', '#000000');
        $btn_secondary_bg_hover = Configuration::get('BLCKCONFIG_BTN_SECONDARY_BG_HOVER', '#dddddd');
        $btn_secondary_text_hover = Configuration::get('BLCKCONFIG_BTN_SECONDARY_TEXT_HOVER', '#000000');
        $flag_bg = Configuration::get('BLCKCONFIG_FLAG_BG', '#000000');
        $flag_text = Configuration::get('BLCKCONFIG_FLAG_TEXT', '#FFFFFF');
        $css = ":root {\n";
        $css .= "  --blck-title-font: '$title_font', sans-serif;\n";
        $css .= "  --blck-text-font: '$text_font', sans-serif;\n";
        $css .= "  --blck-main-color: $main_color;\n";
        $css .= "  --blck-secondary-color: $secondary_color;\n";
        $css .= "  --blck-bg-color: $bg_color;\n";
        $css .= "  --blck-btn-primary-bg: $btn_primary_bg;\n";
        $css .= "  --blck-btn-primary-text: $btn_primary_text;\n";
        $css .= "  --blck-btn-primary-bg-hover: $btn_primary_bg_hover;\n";
        $css .= "  --blck-btn-primary-text-hover: $btn_primary_text_hover;\n";
        $css .= "  --blck-btn-secondary-bg: $btn_secondary_bg;\n";
        $css .= "  --blck-btn-secondary-text: $btn_secondary_text;\n";
        $css .= "  --blck-btn-secondary-bg-hover: $btn_secondary_bg_hover;\n";
        $css .= "  --blck-btn-secondary-text-hover: $btn_secondary_text_hover;\n";
        $css .= "  --blck-flag-bg: $flag_bg;\n";
        $css .= "  --blck-flag-text: $flag_text;\n";
        $css .= "}\n";
        file_put_contents(_PS_MODULE_DIR_.$this->name.'/blck.css', $css);
    }
} 