# NTheme — Template Resolver for Nette

Theme management with active/fallback resolution chain. Resolves Latte templates and asset paths.

## Installation

```bash
composer require jansuchanek/ntheme
```

## Usage

```php
use NTheme\ThemeResolver;

$resolver = new ThemeResolver(
    activeTheme: 'modern',
    fallbackTheme: 'default',
    themesDir: __DIR__ . '/themes',
);

// Get template paths (active first, then fallback)
$paths = $resolver->getTemplatePaths('Product', 'detail');
// ['/themes/modern/Product/detail.latte', '/themes/default/Product/detail.latte']

// Resolve first existing template
$file = $resolver->resolveTemplate('Product', 'detail');

// Layout resolution
$layout = $resolver->resolveLayout();

// Asset base URL
$css = $resolver->getAssetBaseUrl() . '/css/style.css';
// '/themes/modern/css/style.css'

// List available themes
$themes = $resolver->getAvailableThemes();
// ['default', 'modern', 'dark']

// Switch theme at runtime
$resolver->setActiveTheme('dark');
```

## Directory Structure

```
themes/
├── default/
│   ├── @layout.latte
│   └── Product/
│       └── detail.latte
└── modern/
    ├── @layout.latte
    └── Product/
        └── detail.latte
```

## Requirements

- PHP >= 8.1
