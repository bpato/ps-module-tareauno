<?php
/**
 * Copyright (C) 2020 Brais Pato
 *
 * NOTICE OF LICENSE
 *
 * This file is part of Simplerecaptcha <https://github.com/bpato/simplerecaptcha.git>.
 * 
 * Simplerecaptcha is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Simplerecaptcha is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Foobar. If not, see <https://www.gnu.org/licenses/>.
 *
 * @author    Brais Pato <patodevelop@gmail.com>
 * @copyright 2020 Brais Pato
 * @license   https://www.gnu.org/licenses/ GNU GPLv3
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

class Tareauno extends Module
{
    /** @var string Unique name */
    public $name = 'tareauno';

    /** @var string Version */
    public $version = '1.0.0';

    /** @var string author of the module */
    public $author = 'Brais Pato';

    /** @var int need_instance */
    public $need_instance = 0;

    /** @var string Admin tab corresponding to the module */
    public $tab = 'front_office_features';

    /** @var array filled with known compliant PS versions */
    public $ps_versions_compliancy = [
        'min' => '1.7.3.3',
        'max' => '1.7.9.99'
    ];

    /** @var array Hooks used */
    public $hooks = [
        'displayHome',
        'displayFooterBefore'
    ];

    /** Name of ModuleAdminController used for configuration */
    const MODULE_ADMIN_CONTROLLER = 'AdminTareauno';

    /** Configuration variable names */
    const CONF_KEY_HOME = 'TAR_FRASE_BIENVENIDA_HOME';
    const CONF_KEY_FOOTER = 'TAR_FRASE_BIENVENIDA_FOOTER';

    /**
     * Constructor of module
     */
    public function __construct()
    {
        $this->bootstrap = true;
        parent::__construct();

        $this->displayName = $this->trans('Modulo Tarea 1', [], 'Modules.Tareauno.Admin');
        $this->description = $this->trans('Crear un módulo que te permita desde backoffice configurar una frase de bienvenida para mostrar dos mensajes distintos', [], 'Modules.Tareauno.Admin');
        $this->confirmUninstall = $this->trans('¿Estás seguro de que quieres desinstalar el módulo?', array(), 'Modules.Tareauno.Admin');
    }

    /**
     * @return bool
     */
    public function install()
    {
        return parent::install() 
            && $this->registerHook($this->hooks)
            && $this->moveHookPosition($this->hooks[0], 0, 1)
            && $this->moveHookPosition($this->hooks[1], 0, 1)
            && $this->installTab();
    }

    /**
     * @return bool
     */
    public function installTab()
    {
        $tab = new Tab();
        
        $tab->class_name = static::MODULE_ADMIN_CONTROLLER;
        $tab->name = array_fill_keys(
            Language::getIDs(false),
            $this->name
        );
        $tab->active = false;
        $tab->id_parent = (int) Tab::getIdFromClassName('AdminModulesManage');
        $tab->module = $this->name;

        return $tab->add();
    }

    /**
     * @return bool
     */
    public function uninstall()
    {
        return parent::uninstall()
            && $this->uninstallTabs()
            && $this->uninstallConfiguration();
    }

    /**
     * @return bool
     */
    public function uninstallTabs()
    {
        $id_tab = (int) Tab::getIdFromClassName(static::MODULE_ADMIN_CONTROLLER);

        if ($id_tab) {
            $tab = new Tab($id_tab);

            return (bool) $tab->delete();
        }

        return true;
    }

    /**
     * @return bool
     */
    public function uninstallConfiguration()
    {
        return (bool) Configuration::deleteByName(static::CONF_KEY_HOME)
            && (bool) Configuration::deleteByName(static::CONF_KEY_FOOTER);
    }

    /**
     * @return null
     */
    public function getContent()
    {
        Tools::redirectAdmin(Context::getContext()->link->getAdminLink(self::MODULE_ADMIN_CONTROLLER));
        return null;
    }

    protected function fillTemplate($data, $hook_name)
    {
        $smarty_data = $this->context->smarty->createData();
        $smarty_data->assign('content', $data);
        return $this->fetch('module:tareauno/views/templates/hook/'.$hook_name.'.tpl', $smarty_data);
    }

    public function hookDisplayHome()
    {
        $idLang = Context::getContext()->language->id;
        return $this->fillTemplate(Configuration::get(static::CONF_KEY_HOME, $idLang), $this->hooks[0]);
    }

    public function hookDisplayFooterBefore()
    {
        $idLang = Context::getContext()->language->id;
        return $this->fillTemplate(Configuration::get(static::CONF_KEY_FOOTER, $idLang), $this->hooks[1]);
    }

    public function moveHookPosition($hook_name, $way, $position) {
        $id_hook = Hook::getIdByName($hook_name);
        return $this->updatePosition($id_hook, $way, $position);
    }
}