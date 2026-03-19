<?php
declare(strict_types=1);
namespace NTheme\Tests;
use NTheme\ThemeResolver;
use Tester\Assert;
use Tester\TestCase;
require __DIR__ . '/../vendor/autoload.php';
\Tester\Environment::setup();

class ThemeResolverTest extends TestCase
{
	public function testDefaultTheme(): void
	{
		$tr = new ThemeResolver();
		Assert::same('default', $tr->getActiveTheme());
		Assert::same('default', $tr->getFallbackTheme());
	}

	public function testSetActiveTheme(): void
	{
		$tr = new ThemeResolver(activeTheme: 'modern');
		Assert::same('modern', $tr->getActiveTheme());
		$tr->setActiveTheme('dark');
		Assert::same('dark', $tr->getActiveTheme());
	}

	public function testLayoutPathsSameTheme(): void
	{
		$tr = new ThemeResolver(activeTheme: 'default', themesDir: '/themes');
		$paths = $tr->getLayoutPaths();
		Assert::count(1, $paths); // same active + fallback = 1 path
		Assert::same('/themes/default/@layout.latte', $paths[0]);
	}

	public function testLayoutPathsDifferentTheme(): void
	{
		$tr = new ThemeResolver(activeTheme: 'modern', fallbackTheme: 'default', themesDir: '/themes');
		$paths = $tr->getLayoutPaths();
		Assert::count(2, $paths);
		Assert::same('/themes/modern/@layout.latte', $paths[0]);
		Assert::same('/themes/default/@layout.latte', $paths[1]);
	}

	public function testTemplatePaths(): void
	{
		$tr = new ThemeResolver(activeTheme: 'modern', fallbackTheme: 'default', themesDir: '/themes');
		$paths = $tr->getTemplatePaths('Product', 'detail');
		Assert::count(2, $paths);
		Assert::same('/themes/modern/Product/detail.latte', $paths[0]);
		Assert::same('/themes/default/Product/detail.latte', $paths[1]);
	}

	public function testAssetBaseUrl(): void
	{
		$tr = new ThemeResolver(activeTheme: 'modern');
		Assert::same('/themes/modern', $tr->getAssetBaseUrl());
	}

	public function testResolveTemplateReturnsNull(): void
	{
		$tr = new ThemeResolver(themesDir: '/nonexistent');
		Assert::null($tr->resolveTemplate('Product', 'detail'));
	}

	public function testResolveLayoutReturnsNull(): void
	{
		$tr = new ThemeResolver(themesDir: '/nonexistent');
		Assert::null($tr->resolveLayout());
	}
}
(new ThemeResolverTest())->run();
