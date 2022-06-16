<?php
/**
 * @author      Laurent Jouanneau
 * @copyright   2021 Laurent Jouanneau
 *
 * @see         https://jelix.org
 * @licence     http://www.gnu.org/licenses/gpl.html GNU General Public Licence, see LICENCE file
 */

namespace Jelix\JCommunity\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DeleteUser extends \Jelix\Scripts\ModuleCommandAbstract
{
    protected function configure()
    {
        $this
            ->setName('jcommunity:user:delete')
            ->setDescription(\jLocale::get('jcommunity~register.cmdline.delete.help.description'))
            ->setHelp(\jLocale::get('jcommunity~register.cmdline.delete.help.text'))
            ->addArgument(
                'login',
                InputArgument::REQUIRED,
                \jLocale::get('jcommunity~register.cmdline.delete.help.parameter.login')
            )
        ;
    }

    protected function displayMessage(OutputInterface $output, $exitCode, $message = null)
    {
        if ($output->isVerbose() && $message) {
            $output->writeln($message);
        }
        return $exitCode;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $login = $input->getArgument('login');
        $code = 0;
        $message = '';

        $removed = \jAuth::removeUser($login);
        if (!$removed) {
            $code = 1;
        }
        return $this->displayMessage($output, $message, $code);
    }
}
