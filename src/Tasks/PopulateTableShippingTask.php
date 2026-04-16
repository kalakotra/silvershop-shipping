<?php

namespace SilverShop\Shipping\Tasks;

use SilverShop\Shipping\Model\TableShippingMethod;
use SilverStripe\Core\Manifest\ModuleResourceLoader;
use SilverStripe\Dev\BuildTask;
use SilverStripe\Dev\FixtureFactory;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\Dev\YamlFixture;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use SilverStripe\PolyExecution\PolyOutput;

/**
 * @package silvershop-shipping
 */
class PopulateTableShippingTask extends BuildTask
{
    protected string $title = "Populate Table Shipping Methods";

    protected string $description = 'If no table shipping methods exist, it creates multiple different setups of table shipping.';

    public function populateIfMissing(): bool
    {
        if (TableShippingMethod::get()->exists()) {
            return false;
        }

        $factory = Injector::inst()->create(FixtureFactory::class);
        $fixture = YamlFixture::create(
            ModuleResourceLoader::singleton()
                ->resolvePath('silvershop/shipping:tests/TableShippingMethod.yml')
        );
        $fixture->writeInto($factory);

        return true;
    }

    protected function execute(InputInterface $input, PolyOutput $output): int
    {
        if ($this->populateIfMissing()) {
            $output->writeln('<info>Created table shipping methods</info>');
        } else {
            $output->writeln('<comment>Some table shipping methods already exist. None were created.</comment>');
        }

        return Command::SUCCESS;
    }
}
