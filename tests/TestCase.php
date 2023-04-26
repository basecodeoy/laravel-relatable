<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Database\Schema\Blueprint;
use BombenProdukt\PackagePowerPack\TestBench\AbstractPackageTestCase;
use Tests\Fixtures\HasFruitAsRelatedContent;
use Tests\Fixtures\Lime;
use Tests\Fixtures\Strawberry;

/**
 * @internal
 */
abstract class TestCase extends AbstractPackageTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        tap($this->app['db']->connection()->getSchemaBuilder(), function ($schema): void {
            $schema->create('has_fruit_as_related_contents', function (Blueprint $table): void {
                $table->id();
                $table->timestamps();
            });

            $schema->create('limes', function (Blueprint $table): void {
                $table->id();
                $table->timestamps();
            });

            $schema->create('strawberries', function (Blueprint $table): void {
                $table->id();
                $table->timestamps();
            });

            $schema->create('relatables', function (Blueprint $table): void {
                $table->id();
                $table->morphs('source');
                $table->morphs('related');
                $table->timestamps();

                $table->unique(['source_id', 'source_type', 'related_id', 'related_type']);
            });
        });

        HasFruitAsRelatedContent::create(['id' => 1]);
        Lime::create(['id' => 1]);
        Strawberry::create(['id' => 1]);
    }

    protected function getServiceProviderClass(): string
    {
        return \BombenProdukt\Relatable\ServiceProvider::class;
    }
}
