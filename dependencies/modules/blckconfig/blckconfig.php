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
            Configuration::updateValue('BLCKCONFIG_SOCIAL_BG', '#000000');
            Configuration::updateValue('BLCKCONFIG_SOCIAL_BG_HOVER', '#fba000');
            Configuration::updateValue('BLCKCONFIG_CATEGORY_BG_ENABLED', 0);
            Configuration::updateValue('BLCKCONFIG_NAV_LINK_LABEL', '');
            Configuration::updateValue('BLCKCONFIG_NAV_LINK_URL', '');
            Configuration::updateValue('BLCKCONFIG_NAV_LINK_BLANK', 0);
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
            Configuration::updateValue('BLCKCONFIG_SOCIAL_BG', Tools::getValue('BLCKCONFIG_SOCIAL_BG', '#000000'));
            Configuration::updateValue('BLCKCONFIG_SOCIAL_BG_HOVER', Tools::getValue('BLCKCONFIG_SOCIAL_BG_HOVER', '#fba000'));
            Configuration::updateValue('BLCKCONFIG_CATEGORY_BG_ENABLED', (int) Tools::getValue('BLCKCONFIG_CATEGORY_BG_ENABLED', 0));
            Configuration::updateValue('BLCKCONFIG_NAV_LINK_LABEL', Tools::getValue('BLCKCONFIG_NAV_LINK_LABEL', ''));
            Configuration::updateValue('BLCKCONFIG_NAV_LINK_URL', Tools::getValue('BLCKCONFIG_NAV_LINK_URL', ''));
            Configuration::updateValue('BLCKCONFIG_NAV_LINK_BLANK', (int) Tools::getValue('BLCKCONFIG_NAV_LINK_BLANK', 0));
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

        // Passer les configurations aux templates
        $this->context->smarty->assign(array(
            'blck_show_category_bg' => (bool) Configuration::get('BLCKCONFIG_CATEGORY_BG_ENABLED', 0),
            'blck_nav_link_label' => Configuration::get('BLCKCONFIG_NAV_LINK_LABEL', ''),
            'blck_nav_link_url' => Configuration::get('BLCKCONFIG_NAV_LINK_URL', ''),
            'blck_nav_link_blank' => (bool) Configuration::get('BLCKCONFIG_NAV_LINK_BLANK', 0),
        ));
    }

    private function renderForm()
    {
        $default_lang = (int)Configuration::get('PS_LANG_DEFAULT');
        $system_fonts = [
            // System fonts
            ['id' => 'Arial', 'name' => 'Arial'],
            ['id' => 'Verdana', 'name' => 'Verdana'],
            ['id' => 'Tahoma', 'name' => 'Tahoma'],
            ['id' => 'Trebuchet MS', 'name' => 'Trebuchet MS'],
            ['id' => 'Times New Roman', 'name' => 'Times New Roman'],
            ['id' => 'Georgia', 'name' => 'Georgia'],
            ['id' => 'Garamond', 'name' => 'Garamond'],
            ['id' => 'Courier New', 'name' => 'Courier New'],
            ['id' => 'Brush Script MT', 'name' => 'Brush Script MT'],
            // Google Fonts
            ['id' => 'Bitter', 'name' => 'Bitter'],
            ['id' => 'Quicksand', 'name' => 'Quicksand'],
            ['id' => 'Roboto', 'name' => 'Roboto'],
            ['id' => 'Open Sans', 'name' => 'Open Sans'],
            ['id' => 'Lato', 'name' => 'Lato'],
            ['id' => 'Montserrat', 'name' => 'Montserrat'],
            ['id' => 'Poppins', 'name' => 'Poppins'],
            ['id' => 'Raleway', 'name' => 'Raleway'],
            ['id' => 'Playfair Display', 'name' => 'Playfair Display'],
            ['id' => 'Merriweather', 'name' => 'Merriweather'],
            ['id' => 'Nunito', 'name' => 'Nunito'],
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
                        'class' => 'chosen',
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
                        'class' => 'chosen',
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
                    [
                        'type' => 'html',
                        'name' => 'blckconfig_social_title',
                        'html_content' => '<div style="font-weight:bold;font-size:1.1em;margin-top:1.5em;margin-bottom:0.5em;">'.$this->l('Social buttons').'</div>',
                    ],
                    [
                        'type' => 'color',
                        'label' => $this->l('Social buttons background'),
                        'name' => 'BLCKCONFIG_SOCIAL_BG',
                    ],
                    [
                        'type' => 'color',
                        'label' => $this->l('Social buttons background (hover)'),
                        'name' => 'BLCKCONFIG_SOCIAL_BG_HOVER',
                    ],
                    [
                        'type' => 'html',
                        'name' => 'blckconfig_category_title',
                        'html_content' => '<div style="font-weight:bold;font-size:1.1em;margin-top:1.5em;margin-bottom:0.5em;">'.$this->l('Category header').'</div>',
                    ],
                    [
                        'type' => 'switch',
                        'label' => $this->l('Use category image as header background'),
                        'name' => 'BLCKCONFIG_CATEGORY_BG_ENABLED',
                        'is_bool' => true,
                        'values' => [
                            [
                                'id' => 'blckconfig_category_bg_on',
                                'value' => 1,
                                'label' => $this->l('Yes'),
                            ],
                            [
                                'id' => 'blckconfig_category_bg_off',
                                'value' => 0,
                                'label' => $this->l('No'),
                            ],
                        ],
                    ],
                    [
                        'type' => 'html',
                        'name' => 'blckconfig_nav_title',
                        'html_content' => '<div style="font-weight:bold;font-size:1.1em;margin-top:1.5em;margin-bottom:0.5em;">'.$this->l('Navigation link').'</div>',
                    ],
                    [
                        'type' => 'text',
                        'label' => $this->l('Nav link label'),
                        'name' => 'BLCKCONFIG_NAV_LINK_LABEL',
                    ],
                    [
                        'type' => 'text',
                        'label' => $this->l('Nav link URL'),
                        'name' => 'BLCKCONFIG_NAV_LINK_URL',
                        'desc' => $this->l('Absolute or relative URL for the nav link.'),
                    ],
                    [
                        'type' => 'switch',
                        'label' => $this->l('Open nav link in a new tab'),
                        'name' => 'BLCKCONFIG_NAV_LINK_BLANK',
                        'is_bool' => true,
                        'values' => [
                            [
                                'id' => 'blckconfig_nav_link_blank_on',
                                'value' => 1,
                                'label' => $this->l('Yes'),
                            ],
                            [
                                'id' => 'blckconfig_nav_link_blank_off',
                                'value' => 0,
                                'label' => $this->l('No'),
                            ],
                        ],
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
            'BLCKCONFIG_SOCIAL_BG' => Configuration::get('BLCKCONFIG_SOCIAL_BG', '#000000'),
            'BLCKCONFIG_SOCIAL_BG_HOVER' => Configuration::get('BLCKCONFIG_SOCIAL_BG_HOVER', '#fba000'),
            'BLCKCONFIG_CATEGORY_BG_ENABLED' => (int) Configuration::get('BLCKCONFIG_CATEGORY_BG_ENABLED', 0),
            'BLCKCONFIG_NAV_LINK_LABEL' => Configuration::get('BLCKCONFIG_NAV_LINK_LABEL', ''),
            'BLCKCONFIG_NAV_LINK_URL' => Configuration::get('BLCKCONFIG_NAV_LINK_URL', ''),
            'BLCKCONFIG_NAV_LINK_BLANK' => (int) Configuration::get('BLCKCONFIG_NAV_LINK_BLANK', 0),
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
        $social_bg = Configuration::get('BLCKCONFIG_SOCIAL_BG', '#000000');
        $social_bg_hover = Configuration::get('BLCKCONFIG_SOCIAL_BG_HOVER', '#fba000');

        // Import Google Fonts when needed
        $imports = '';
        if ($title_font === 'Bitter' || $text_font === 'Bitter') {
            $imports .= "@import url('https://fonts.googleapis.com/css2?family=Bitter:wght@400;700&display=swap');\n";
        }
        if ($title_font === 'Quicksand' || $text_font === 'Quicksand') {
            $imports .= "@import url('https://fonts.googleapis.com/css2?family=Quicksand:wght@400;600;700&display=swap');\n";
        }
        if ($title_font === 'Roboto' || $text_font === 'Roboto') {
            $imports .= "@import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap');\n";
        }
        if ($title_font === 'Open Sans' || $text_font === 'Open Sans') {
            $imports .= "@import url('https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&display=swap');\n";
        }
        if ($title_font === 'Lato' || $text_font === 'Lato') {
            $imports .= "@import url('https://fonts.googleapis.com/css2?family=Lato:wght@400;700&display=swap');\n";
        }
        if ($title_font === 'Montserrat' || $text_font === 'Montserrat') {
            $imports .= "@import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap');\n";
        }
        if ($title_font === 'Poppins' || $text_font === 'Poppins') {
            $imports .= "@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap');\n";
        }
        if ($title_font === 'Raleway' || $text_font === 'Raleway') {
            $imports .= "@import url('https://fonts.googleapis.com/css2?family=Raleway:wght@400;600;700&display=swap');\n";
        }
        if ($title_font === 'Playfair Display' || $text_font === 'Playfair Display') {
            $imports .= "@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&display=swap');\n";
        }
        if ($title_font === 'Merriweather' || $text_font === 'Merriweather') {
            $imports .= "@import url('https://fonts.googleapis.com/css2?family=Merriweather:wght@400;700&display=swap');\n";
        }
        if ($title_font === 'Nunito' || $text_font === 'Nunito') {
            $imports .= "@import url('https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap');\n";
        }

        $css = $imports;
        $css .= ":root {\n";
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
        $css .= "  --blck-social-bg: $social_bg;\n";
        $css .= "  --blck-social-bg-hover: $social_bg_hover;\n";
        $css .= "}\n";
        // Apply fonts to body and headings
        $css .= "body{font-family: var(--blck-text-font);}\n";
        $css .= "h1,h2,h3,h4,h5,h6{font-family: var(--blck-title-font);}\n";
        // Social buttons
        $css .= ".block-social ul li{background-color: var(--blck-social-bg);}\n";
        $css .= ".block-social ul li:hover{background-color: var(--blck-social-bg-hover);}\n";
        file_put_contents(_PS_MODULE_DIR_.$this->name.'/blck.css', $css);
    }
} 