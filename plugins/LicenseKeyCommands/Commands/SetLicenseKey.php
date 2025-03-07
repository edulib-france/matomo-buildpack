<?php
namespace Piwik\Plugins\LicenseKeyCommands\Commands;
/**
 * Piwik - free/libre analytics platform
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */

use Piwik\Common;
use Piwik\Plugin\ConsoleCommand;
use Piwik\Plugins\Marketplace\LicenseKey;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Command to selectively delete visits.
 */
class SetLicenseKey extends ConsoleCommand
{
    protected function configure()
    {
        $this->setName('license:set');
        $this->setDescription('Set license key');
        $this->addRequiredValueOption('licenseKey', null, 'License key');
    }

    protected function doExecute(): int
    {
        $input = $this->getInput();
        $output = $this->getOutput();
        $licenseKey = trim($input->getOption('licenseKey'));

        try {
            $key = new LicenseKey();
            $key->set($licenseKey);
        } catch (\Exception $ex) {
            $output->writeln("");
            return self::FAILURE;
        }

        $this->writeSuccessMessage($output, array("Successfully set license key"));
        return self::SUCCESS;
    }
}
