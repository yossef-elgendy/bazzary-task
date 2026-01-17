# Vendor_SpecialOffers Module

A Magento 2 module for managing and displaying special offers with an admin grid interface and frontend slider widget.

## Features

- **Admin Grid Management**: Full CRUD operations for special offers
- **Image Upload**: Support for offer images (jpg, jpeg, png, gif, webp)
- **URL Validation**: Validates offer URLs before saving
- **Frontend Slider Widget**: Responsive slider displaying active offers
- **Homepage Integration**: Automatically displays on homepage via layout XML
- **Mass Actions**: Bulk enable/disable/delete offers

## Requirements

- Magento 2.4.x
- PHP 8.1+

## Installation

### 1. Copy module files

Place the module in `app/code/Vendor/SpecialOffers/`

### 2. Create required media directories

```bash
bin/cli mkdir -p /var/www/html/pub/media/specialoffers/tmp
bin/cli chmod -R 777 /var/www/html/pub/media/specialoffers
```

### 3. Enable the module

```bash
bin/magento module:enable Vendor_SpecialOffers
bin/magento setup:upgrade
bin/magento setup:di:compile
bin/magento cache:flush
```

## Usage

### Admin Panel

Navigate to **Content > Special Offers > Manage Offers** to:

- Add new offers
- Edit existing offers
- Delete offers
- Enable/disable offers via mass actions

### Offer Fields

| Field | Type | Required | Validation |
|-------|------|----------|------------|
| Offer Title | Text | Yes | Max 255 characters |
| Offer Description | Textarea | No | - |
| Offer Image | Image Upload | No | jpg, jpeg, png, gif, webp (max 4MB) |
| Offer URL | URL | No | Valid URL format |
| Active Status | Dropdown | Yes | Yes/No |

### Frontend Widget

The module includes a widget that can be added to any CMS page or block:

1. Go to **Content > Pages** or **Content > Blocks**
2. Edit the page/block content
3. Click **Insert Widget**
4. Select **Special Offers Slider**
5. Configure options:
   - **Slider Title**: Title displayed above the slider
   - **Number of Offers**: Maximum offers to display
   - **Items Per Row**: 2, 3, 4, 5, or 6 items per row

### Homepage Display

The slider automatically appears on the homepage via `cms_index_index.xml` layout.

To disable automatic homepage display, remove or override the layout file:
```
view/frontend/layout/cms_index_index.xml
```

## Module Structure

```
Vendor/SpecialOffers/
├── Api/
│   └── Data/
│       └── SpecialOfferInterface.php      # Data interface
├── Block/
│   ├── Adminhtml/
│   │   └── Offer/
│   │       └── Edit/
│   │           ├── GenericButton.php      # Base button class
│   │           ├── BackButton.php         # Back button
│   │           ├── DeleteButton.php       # Delete button
│   │           └── SaveButton.php         # Save button
│   └── Widget/
│       └── SpecialOffersSlider.php        # Frontend widget block
├── Controller/
│   └── Adminhtml/
│       └── Offer/
│           ├── Index.php                  # Grid listing
│           ├── NewAction.php              # New offer form
│           ├── Edit.php                   # Edit offer form
│           ├── Save.php                   # Save offer
│           ├── Delete.php                 # Delete offer
│           ├── MassDelete.php             # Mass delete
│           ├── MassStatus.php             # Mass status change
│           └── Upload.php                 # Image upload handler
├── Model/
│   ├── SpecialOffer.php                   # Main model
│   ├── ResourceModel/
│   │   ├── SpecialOffer.php               # Resource model
│   │   └── SpecialOffer/
│   │       └── Collection.php             # Collection
│   ├── SpecialOffer/
│   │   └── DataProvider.php               # Form data provider
│   └── Source/
│       └── IsActive.php                   # Status options
├── Ui/
│   └── Component/
│       └── Listing/
│           └── Column/
│               ├── Actions.php            # Grid actions column
│               └── Image.php              # Grid image column
├── etc/
│   ├── module.xml                         # Module declaration
│   ├── di.xml                             # Dependency injection
│   ├── acl.xml                            # Access control
│   ├── db_schema.xml                      # Database schema
│   ├── db_schema_whitelist.json           # Schema whitelist
│   ├── widget.xml                         # Widget declaration
│   └── adminhtml/
│       ├── routes.xml                     # Admin routes
│       ├── menu.xml                       # Admin menu
│       └── di.xml                         # Admin DI config
├── view/
│   ├── adminhtml/
│   │   ├── layout/
│   │   │   ├── specialoffers_offer_index.xml
│   │   │   ├── specialoffers_offer_edit.xml
│   │   │   └── specialoffers_offer_new.xml
│   │   └── ui_component/
│   │       ├── vendor_specialoffers_listing.xml   # Admin grid
│   │       └── vendor_specialoffers_form.xml      # Admin form
│   └── frontend/
│       ├── layout/
│       │   └── cms_index_index.xml        # Homepage layout
│       └── templates/
│           └── widget/
│               └── slider.phtml           # Slider template
└── registration.php                       # Module registration
```

## Database

### Table: `vendor_special_offers`

| Column | Type | Description |
|--------|------|-------------|
| offer_id | int (PK, AI) | Unique identifier |
| title | varchar(255) | Offer title |
| description | text | Offer description |
| image | varchar(255) | Image filename |
| url | varchar(255) | Offer URL |
| is_active | smallint | Active status (0/1) |
| created_at | timestamp | Creation date |
| updated_at | timestamp | Last update date |

## ACL Resources

- `Vendor_SpecialOffers::special_offers` - Access to Special Offers section
- `Vendor_SpecialOffers::offer_manage` - Manage offers (view, create, edit, delete)

## Customization

### Override Slider Template

Create in your theme:
```
app/design/frontend/[Vendor]/[Theme]/Vendor_SpecialOffers/templates/widget/slider.phtml
```

### Modify Slider Styles

The slider includes inline CSS. To customize:
1. Override the template
2. Or add custom CSS in your theme

### Add Custom Fields

1. Update `etc/db_schema.xml` with new columns
2. Update `Api/Data/SpecialOfferInterface.php`
3. Update `Model/SpecialOffer.php` with getters/setters
4. Update `view/adminhtml/ui_component/vendor_specialoffers_form.xml`
5. Run `bin/magento setup:upgrade`

## Troubleshooting

### Image Upload Error

If you see "The file doesn't exist" error:
```bash
bin/cli mkdir -p /var/www/html/pub/media/specialoffers/tmp
bin/cli chmod -R 777 /var/www/html/pub/media/specialoffers
```

### Menu Not Visible

1. Clear cache: `bin/magento cache:flush`
2. Recompile: `bin/magento setup:di:compile`
3. Check admin role has full permissions

### Slider Not Showing

1. Ensure there are active offers in the database
2. Clear full page cache: `bin/magento cache:flush`
3. Check browser console for JavaScript errors

## Uninstall

```bash
bin/magento module:disable Vendor_SpecialOffers
bin/magento setup:upgrade
```

To remove database table:
```sql
DROP TABLE IF EXISTS vendor_special_offers;
```

## License

MIT License
