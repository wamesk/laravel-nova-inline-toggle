# Laravel Nova Inline Toggle

A Laravel Nova field that renders a toggle/switch with **inline editing** on the index page. Click the toggle directly in the resource table to update the value — no need to open the edit form.

## Features

- Toggle switch on **index** page with inline update (no page reload)
- Configurable **on/off colors**
- Read-only badge or inline toggle on **detail** page
- Standard checkbox on **form** page
- Customizable **success/error messages** (per state or general)
- Works with any boolean/tinyint column
- Compatible with `->sortable()` and `->filterable()`

## Requirements

- PHP 8.2+
- Laravel Nova 4.x
- Laravel 11.x

## Installation

```bash
composer require wamesk/laravel-nova-inline-toggle
```

The service provider is auto-discovered — no manual registration needed. Pre-built assets are included in the package.

## Usage

### Basic

```php
use Wame\InlineToggle\InlineToggle;

public function fields(NovaRequest $request): array
{
    return [
        // ...

        InlineToggle::make(__('Active'), 'is_active'),
    ];
}
```

This renders a green/gray toggle switch on the index page and a read-only badge on the detail page.

### Colors

Customize the toggle colors using hex values:

```php
InlineToggle::make(__('Reminders Paused'), 'reminders_paused')
    ->onColor('#FDE047')   // yellow when ON
    ->offColor('#9ca3af')  // gray when OFF (default)
```

**Defaults:**
- `onColor`: `#22c55e` (green)
- `offColor`: `#9ca3af` (gray)

The detail page badge automatically uses the configured color as its background and calculates a contrasting text color (dark or white).

### Inline Toggle on Detail Page

By default, the detail page shows a read-only ON/OFF badge. To enable the inline toggle on detail as well:

```php
InlineToggle::make(__('Active'), 'is_active')
    ->inlineOnDetail()
```

### Custom Messages

#### General message (shown for both ON and OFF)

```php
InlineToggle::make(__('Active'), 'is_active')
    ->successMessage(__('Status updated.'))
    ->errorMessage(__('Failed to update status.'))
```

#### Per-state messages

```php
InlineToggle::make(__('Reminders Paused'), 'reminders_paused')
    ->onMessage(__('Reminders have been paused.'))
    ->offMessage(__('Reminders have been resumed.'))
    ->errorMessage(__('Failed to update reminder status.'))
```

**Message priority:** `onMessage`/`offMessage` > `successMessage` > default English fallback.

### Sorting & Filtering

The field supports Nova's built-in sorting and filtering:

```php
InlineToggle::make(__('Active'), 'is_active')
    ->sortable()
    ->filterable()
```

### Full Example

```php
use Wame\InlineToggle\InlineToggle;

InlineToggle::make(__('invoice::invoice.field.reminders_paused'), 'reminders_paused')
    ->onColor('#FDE047')
    ->offColor('#9ca3af')
    ->inlineOnDetail()
    ->onMessage(__('invoice::invoice.toggle.reminders_paused_on'))
    ->offMessage(__('invoice::invoice.toggle.reminders_paused_off'))
    ->errorMessage(__('invoice::invoice.toggle.error'))
    ->sortable()
    ->filterable(),
```

## API Reference

| Method | Description | Default |
|---|---|---|
| `onColor(string $color)` | Hex color when toggle is ON | `#22c55e` |
| `offColor(string $color)` | Hex color when toggle is OFF | `#9ca3af` |
| `inlineOnDetail(bool $value = true)` | Enable inline toggle on detail page | `false` (read-only badge) |
| `successMessage(string $message)` | General success toast message | `"The field was updated successfully."` |
| `onMessage(string $message)` | Success toast when toggled ON | Falls back to `successMessage` |
| `offMessage(string $message)` | Success toast when toggled OFF | Falls back to `successMessage` |
| `errorMessage(string $message)` | Error toast message | `"There was a problem updating the field."` |

## How It Works

- **Index page:** Renders a CSS toggle switch. On click, sends a POST request to `/nova-vendor/inline-toggle/update/{resource}` which updates the model attribute directly.
- **Detail page:** Shows a colored ON/OFF badge by default. With `->inlineOnDetail()`, renders the same interactive toggle as on the index.
- **Form page:** Renders a standard checkbox.

