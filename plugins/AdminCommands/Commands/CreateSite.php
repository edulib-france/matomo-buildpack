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
use Piwik\Plugins\SitesManager\API as APISitesManager;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Command to selectively delete visits.
 */
class CreateSite extends ConsoleCommand
{
    protected function configure()
    {
        $this->setName('admin:create-site');
        $this->setDescription('Create a site this instance of matomo');
        $this->addRequiredValueOption('name', null, 'Name of the site');
        $this->addRequiredValueOption('url', null, 'URL to the website');
        $this->addOptionalValueOption('ecommerce', null, 'If the site is an ecommerce website');
    }

    protected function doExecute(): int
    {
        $input = $this->getInput();
        $output = $this->getOutput();
        $name = $input->getOption('name');
        $url = $input->getOption('url');
        $ecommerce = $input->getOption('ecommerce');

        try {
            $result = Access::doAsSuperUser(function () use ($name, $url, $ecommerce) {
                return APISitesManager::getInstance()->addSite($name, $url, $ecommerce);
            });
        } catch (\Exception $ex) {
            $output->writeln("");

            return self::FAILURE;
        }

        $this->writeSuccessMessage($output, array("Successfully created site " . $name . " (" . $url . ")"));
        $this->writeSuccessMessage($output, array("Think to add " . $url . " hostname to trusted hosts."));

        return self::SUCCESS;
    }
}
