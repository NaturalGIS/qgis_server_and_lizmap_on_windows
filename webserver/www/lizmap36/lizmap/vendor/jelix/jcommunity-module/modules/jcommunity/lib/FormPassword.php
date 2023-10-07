<?php
/**
 * @author       Laurent Jouanneau <laurent@jelix.org>
 * @copyright    2023 Laurent Jouanneau
 *
 * @link         https://jelix.org
 * @licence      http://www.gnu.org/licenses/gpl.html GNU General Public Licence, see LICENCE file
 */
namespace Jelix\JCommunity;

class FormPassword
{

    static function getFormAndWidget($formName, $passwdCtrlName)
    {
        $form = \jForms::get($formName);
        if($form == null){
            $form = \jForms::create($formName);
        }
        $widget = self::getWidget($form, $passwdCtrlName);
        return  [$form, $widget];
    }

    static function getWidget($form, $passwdCtrlName)
    {
        $useSecretEditor = self::canUseSecretEditor() ;
        if ($useSecretEditor) {
            $confirm = $form->getControl($passwdCtrlName.'_confirm');
            if ($confirm) {
                $confirm->deactivate(true);
            }
            return 'passwordeditor_html';
        }
        else {
            return 'secret_html';
        }
    }

    static function canUseSecretEditor()
    {
        return (class_exists('\jFramework') && version_compare(\jFramework::version(), "1.8.2", ">="));
    }


    static function checkPassword($password)
    {
        return (\jAuthPassword::checkPasswordStrength($password) > \jAuthPassword::STRENGTH_WEAK);
    }
}