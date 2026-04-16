<?php

namespace SilverShop\Shipping\Tasks;

use SilverShop\Shipping\Model\TableShippingMethod;
use SilverStripe\Core\Manifest\ModuleResourceLoader;
use SilverStripe\Dev\BuildTask;
use SilverStripe\Dev\FixtureFactory;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\Dev\YamlFixture;
use SilverStripe\ORM\DB;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use SilverStripe\PolyExecution\PolyOutput;

/**
 * @package silvershop-shipping
 */
class PopulateTableShippingTask extends BuildTask
{
    protected $title = "Populate Table Shipping Methods";

    protected $description = 'If no table shipping methods exist, it creates multiple different setups of table shipping.';

    public function run($request = null): void
    {
        if (!TableShippingMethod::get()->first()) {
            $factory = Injector::inst()->create(FixtureFactory::class);
            $fixture = YamlFixture::create(
                ModuleResourceLoader::singleton()
                    ->resolvePath('silvershop/shipping:tests/TableShippingMethod.yml')
            );
            $fixture->writeInto($factory);
            DB::alteration_message('Created table shipping methods', 'created');
        } else {
            DB::alteration_message('Some table shipping methods already exist. None were created.');
        }
    }

    protected function execute(InputInterface $input, PolyOutput $output): int
    {
        if (!TableShippingMethod::get()->first()) {
            $factory = Injector::inst()->create(FixtureFactory::class);
            $fixture = YamlFixture::create(
                ModuleResourceLoader::singleton()
                    ->resolvePath('silvershop/shipping:tests/TableShippingMethod.yml')
            );
            $fixture->writeInto($factory);
            $output->writeln('<info>Created table shipping methods</info>');
        } else {
            $output->writeln('<comment>Some table shipping methods already exist. None were created.</comment>');
        }
        return Command::SUCCESS;
}
