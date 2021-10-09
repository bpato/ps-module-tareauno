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

class AdminTareaunoController extends ModuleAdminController
{
    /** @var Tareauno $module */
    public $module;

    public function __construct()
    {
        $this->bootstrap = true;
        $this->table = 'configuration';
        $this->className = 'Configuration';

        parent::__construct();

        $this->fields_options = [];
        $this->fields_options[0] = [
            'title' => $this->trans('Modulo Tarea Uno', [], 'Modules.Tareauno.Admin'),
            'icon' => 'icon-cogs',
            'fields' => [
                $this->module::CONF_KEY_HOME => [
                    'title' => $this->trans('Frase de bienvenida', [], 'Modules.Tareauno.Admin'),
                    'desc' => $this->trans('Frase que se mostrara en la home.', [], 'Modules.Tareauno.Admin'),
                    'lang' => true,
                    'type' => 'textareaLang',
                    'validation' => 'isCleanHtml',
                ],
                $this->module::CONF_KEY_FOOTER => [
                    'title' => $this->trans('Frase de bienvenida', [], 'Modules.Tareauno.Admin'),
                    'desc' => $this->trans('Frase que se mostrara en la home antes del footer.', [], 'Modules.Tareauno.Admin'),
                    'lang' => true,
                    'type' => 'textareaLang',
                    'validation' => 'isCleanHtml',
                ],
            ],
            'submit' => ['title' => $this->trans('Save', [], 'Admin.Actions')],
        ];
    }
}