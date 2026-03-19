<?php

declare(strict_types=1);

namespace NTheme;

/**
 * Theme resolution service for Nette applications.
 *
 * Manages active/fallback themes and provides ordered template/asset paths.
 * Themes live in `themes/{name}/` with Latte templates and public assets in `www/themes/{name}/`.
 */
class ThemeResolver
{
	public function __construct(
		private string $activeTheme = 'default',
		private string $fallbackTheme = 'default',
		private string $themesDir = '',
	) {}


	public function getActiveTheme(): string
	{
		return $this->activeTheme;
	}


	public function setActiveTheme(string $theme): void
	{
		$this->activeTheme = $theme;
	}


	public function getFallbackTheme(): string
	{
		return $this->fallbackTheme;
	}


	/**
	 * Get ordered layout paths (active first, then fallback).
	 *
	 * @return list<string>
	 */
	public function getLayoutPaths(): array
	{
		$paths = [];
		if ($this->activeTheme !== $this->fallbackTheme) {
			$paths[] = "{$this->themesDir}/{$this->activeTheme}/@layout.latte";
		}
		$paths[] = "{$this->themesDir}/{$this->fallbackTheme}/@layout.latte";
		return $paths;
	}


	/**
	 * Get ordered template paths for a presenter action.
	 *
	 * @return list<string>
	 */
	public function getTemplatePaths(string $presenterName, string $action): array
	{
		$paths = [];
		if ($this->activeTheme !== $this->fallbackTheme) {
			$paths[] = "{$this->themesDir}/{$this->activeTheme}/{$presenterName}/{$action}.latte";
		}
		$paths[] = "{$this->themesDir}/{$this->fallbackTheme}/{$presenterName}/{$action}.latte";
		return $paths;
	}


	/**
	 * Resolve a template file — returns first existing path.
	 */
	public function resolveTemplate(string $presenterName, string $action): ?string
	{
		foreach ($this->getTemplatePaths($presenterName, $action) as $path) {
			if (is_file($path)) {
				return $path;
			}
		}
		return null;
	}


	/**
	 * Resolve the layout file — returns first existing path.
	 */
	public function resolveLayout(): ?string
	{
		foreach ($this->getLayoutPaths() as $path) {
			if (is_file($path)) {
				return $path;
			}
		}
		return null;
	}


	/**
	 * Get the base URL path for active theme assets.
	 */
	public function getAssetBaseUrl(): string
	{
		return "/themes/{$this->activeTheme}";
	}


	/**
	 * Get all available themes (directories in themes dir).
	 *
	 * @return list<string>
	 */
	public function getAvailableThemes(): array
	{
		if (!$this->themesDir || !is_dir($this->themesDir)) {
			return [$this->activeTheme];
		}

		$themes = [];
		foreach (scandir($this->themesDir) as $item) {
			if ($item[0] !== '.' && is_dir("{$this->themesDir}/{$item}")) {
				$themes[] = $item;
			}
		}
		return $themes;
	}
}
