<?php
namespace Piwik\Plugins\AdminCommands\Commands;
/**
 * Piwik - free/libre analytics platform
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */

use Piwik\Access;
use Piwik\Common;
use Piwik\Plugin\ConsoleCommand;
use Piwik\Plugins\UsersManager\API as APIUsersManager;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Command to selectively delete visits.
 */
class CreateSuperuser extends ConsoleCommand
{
    protected function configure()
    {
        $this->setName('admin:create-superuser');
        $this->setDescription('Create a superuser for this instance of matomo');
        $this->addRequiredValueOption('login', null, 'Login for new superuser');
        $this->addRequiredValueOption('email', null, 'Email for new superuser');
        $this->addRequiredValueOption('password', null, 'Password for new superuser');
    }

    protected function doExecute(): int
    {
        $input = $this->getInput();
        $output = $this->getOutput();
        $login = $input->getOption('login');
        $email = $input->getOption('email');
        $password = $input->getOption('password');

        try {
            Access::doAsSuperUser(function () use ($login, $email, $password) {
                $api = APIUsersManager::getInstance();
                $api->addUser($login, $password, $email);
                $api->setSuperUserAccess($login, true);
            });
        } catch (\Exception $ex) {
            $output->writeln("");

            return self::FAILURE;
        }

        $this->writeSuccessMessage($output, array("Successfully created superuser " . $login . " (" . $email . ")"));

        return self::SUCCESS;
    }
}
