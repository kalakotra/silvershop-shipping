<?php

namespace SilverShop\Shipping\Tasks;

use SilverStripe\Dev\BuildTask;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\Core\Manifest\ModuleResourceLoader;
use SilverStripe\Dev\YamlFixture;
use SilverShop\Shipping\Model\ZonedShippingMethod;
use SilverStripe\Dev\FixtureFactory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use SilverStripe\PolyExecution\PolyOutput;

/**
 * @package silvershop-shipping
 */
class PopulateZonedShippingTask extends BuildTask
{
    protected $title = "Populate Zoned Shipping Methods";

    protected $description = 'If no zoned shipping methods exist, it creates some.';

    public function populateIfMissing(): bool
    {
        if (ZonedShippingMethod::get()->exists()) {
            return false;
        }

        $factory = Injector::inst()->create(FixtureFactory::class);
        $fixture = YamlFixture::create(
            ModuleResourceLoader::singleton()
                ->resolvePath('silvershop/shipping:tests/ZonedShippingMethod.yml')
        );
        $fixture->writeInto($factory);

        return true;
    }

    protected function execute(InputInterface $input, PolyOutput $output): int
    {
        if ($this->populateIfMissing()) {
            $output->writeln('<info>Created zoned shipping methods</info>');
        } else {
            $output->writeln('<comment>Some zoned shipping methods already exist. None were created.</comment>');
        }

        return Command::SUCCESS;
    }
}
