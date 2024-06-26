<?php

/**
 * @package     jelix_admin_modules
 * @subpackage  jpref_admin
 *
 * @author    Florian Lonqueu-Brochard
 * @contributor Laurent Jouanneau
 * @copyright 2012 Florian Lonqueu-Brochard, 2012-2022 Laurent Jouanneau
 *
 * @see      http://jelix.org
 * @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
 */
class prefsCtrl extends jController
{
    public $pluginParams = array(
        '*' => array('jacl2.right' => 'jprefs.prefs.list'),
    );

    public function index()
    {
        $rep = $this->getResponse('html');

        $tpl = new jTpl();
        $tpl->assign('prefs', jPrefManager::getAllPreferences());
        $rep->body->assign('MAIN', $tpl->fetch('prefs_index'));
        $rep->body->assign('selectedMenuItem', 'pref');

        return $rep;
    }

    public function edit()
    {
        $rep = $this->getResponse('html');
        $id = $this->param('id', 0);

        $pref = jPrefManager::getPref($id);

        if (!$pref) {

            return $this->redirect('jpref_admin~prefs:index');
        }

        if (!$pref->isWritable()) {
            jMessage::add(jLocale::get('jacl2~errors.action.right.needed'), 'error');

            return $this->redirect('jpref_admin~prefs:index');
        }

        $form = jForms::create('jpref_admin~pref', $id);

        $ctrls = array('integer', 'string', 'boolean', 'decimal');
        foreach ($ctrls as $c) {
            $form->deactivate($c);
        }
        $form->deactivate($pref->type, false);

        $label = !empty($pref->locale) ? jLocale::get($pref->locale) : $pref->id;
        $control = $form->getControl($pref->type);
        $control->label = $label;
        if ($pref->type != 'boolean') {
            $control->help = jLocale::get('jpref_admin~admin.help.' . $pref->type);
        }
        if (!empty($pref->value)) {
            $form->setData($pref->type, $pref->value);
        }

        $tpl = new jTpl();
        $tpl->assign('form', $form);
        $tpl->assign('title', jLocale::get('jpref_admin~admin.pref.edit', array($label)));
        $tpl->assign('id', $id);
        $tpl->assign('field', $pref->type);
        $rep->body->assign('MAIN', $tpl->fetch('pref_edit'));
        $rep->body->assign('selectedMenuItem', 'pref');

        return $rep;
    }

    public function saveedit()
    {
        $id = $this->param('id', 0);
        $field = $this->param('field');

        $form = jForms::fill('jpref_admin~pref', $id);
        if (!$form || !$id || !$field) {
            return $this->redirect('jpref_admin~prefs:index');
        }

        if (!$form->check()) {
            $form->setErrorOn($field, 'jpref_admin~admin.field.error');

            return $this->redirect('jpref_admin~prefs:edit', array('id' => $id));
        }

        $data = $form->getData($field);
        if ($field == 'boolean') {
            if ($data == 'false') {
                $data = false;
            } else {
                $data = true;
            }
        } elseif ($field == 'integer') {
            $data = (int) $data;
        } elseif ($field == 'decimal') {
            $data = (float) $data;
        }

        jPref::set($id, $data);

        jMessage::add(jLocale::get('jpref_admin~admin.message.pref.updated'), 'notice');

        return $this->redirect('jpref_admin~prefs:index');
    }

    public function reset()
    {
        $id = $this->param('id', 0);

        $pref = jPrefManager::getPref($id);
        if (!$id || !$pref || (empty($pref->default_value) && $pref->type != 'boolean')) {
            return $this->redirect('jpref_admin~prefs:index');
        }

        if (!$pref->isWritable()) {
            jMessage::add(jLocale::get('jacl2~errors.action.right.needed'), 'error');

            return $this->redirect('jpref_admin~prefs:index');
        }

        $dvalue = $pref->default_value;
        if ($pref->type == 'integer') {
            $dvalue = (int) $dvalue;
        } elseif ($pref->type == 'decimal') {
            $dvalue = (float) $dvalue;
        } elseif ($pref->type == 'boolean') {
            if ($dvalue == 'false') {
                $dvalue = false;
            } elseif ($dvalue == 'true') {
                $dvalue = true;
            }
        }

        jPref::set($pref->id, $dvalue);

        jMessage::add(jLocale::get('jpref_admin~admin.message.pref.reseted'), 'notice');

        return $this->redirect('jpref_admin~prefs:index');
    }
}
