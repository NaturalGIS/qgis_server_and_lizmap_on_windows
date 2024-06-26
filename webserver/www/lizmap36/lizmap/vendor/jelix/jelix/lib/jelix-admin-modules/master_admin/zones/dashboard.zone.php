<?php
/**
 * @package   jelix_admin_modules
 * @subpackage master_admin
 *
 * @author    Laurent Jouanneau
 * @copyright 2008 Laurent Jouanneau
 *
 * @see      http://jelix.org
 * @licence  http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public Licence, see LICENCE file
 */
class masterAdminDashboardWidget
{
    public $title = '';
    public $content = '';
    public $order = 0;
}

function masterAdminDashboardWidgetSort($itemA, $itemB)
{
    if ($itemA->order == $itemB->order) {
        return 0;
    }

    return ($itemA->order < $itemB->order) ? -1 : 1;
}

class dashboardZone extends jZone
{
    protected $_tplname = 'zone_dashboard';

    protected function _prepareTpl()
    {
        $this->_tpl->assignIfNone('foo', 'bar');

        $widgets = jEvent::notify('masterAdminGetDashboardWidget')->getResponse();
        usort($widgets, 'masterAdminDashboardWidgetSort');
        $this->_tpl->assign('widgets', $widgets);
    }
}
