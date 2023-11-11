# Bowe Coupon URL Plugin

## Description
Bowe Coupon URL is a WooCommerce plugin that allows you to apply coupons to customer carts using a URL. This plugin is particularly useful for marketing campaigns, where you can share a link with a coupon code embedded, making it easier for customers to enjoy discounts without manually entering the code.

## Features
- **URL-Based Coupon Application**: Automatically applies a coupon when a customer visits a specific URL.
- **Dynamic Coupon Validation**: Validates coupons in real-time, ensuring only valid discounts are applied.
- **Product and Category Specific Discounts**: Checks if the coupon is applicable to the products in the cart, respecting WooCommerce's coupon rules for product and category inclusions/exclusions.
- **Meta Box for Easy URL Generation**: Adds a meta box to the coupon edit page in WooCommerce, allowing for easy generation and copying of the coupon URL.

## Installation
1. Download the plugin files.
2. Upload the plugin files to the `/wp-content/plugins/` directory, or install the plugin through the WordPress plugins screen directly.
3. Activate the plugin through the 'Plugins' screen in WordPress.

## Usage
1. **Creating a Coupon**: Go to WooCommerce > Coupons. Create a new coupon or edit an existing one.
2. **Generating the URL**: In the coupon edit screen, you will see a new meta box titled 'Coupon URL'. This box contains a URL that you can copy and share with your customers.
3. **Customer Usage**: When a customer visits the URL, the coupon is automatically applied to their cart if it is valid.

## Important Notes
- **Cart Initialization**: The plugin ensures a WooCommerce cart session is started when the coupon URL is accessed. This is crucial for the functionality, especially for guests or when the cart is initially empty.
- **Coupon Validation**: The plugin checks if the coupon is valid before applying it. It respects WooCommerce's native coupon rules, including usage restrictions and expiration.
- **Product Eligibility**: The discount display on product pages is dynamically adjusted based on the coupon's applicability to the product, considering product and category restrictions set in the coupon settings.

## Frequently Asked Questions
**Q: Can I use this plugin for percentage-based discounts?**  
A: Yes, the plugin supports percentage-based discounts. The discounted price will be dynamically shown on the product pages if applicable.

**Q: What happens if a customer tries to use an expired or invalid coupon via URL?**  
A: The plugin validates the coupon before applying it. If the coupon is invalid or expired, it will not be applied, and the customer will not see any discount.

**Q: Can I customize the URL parameter for applying coupons?**  
A: Currently, the URL parameter is set as `apply_coupon`. Customizing this parameter would require modifications to the plugin code.

## Support
For support, please contact [Andreas Pedersen](https://bo-we.dk).

## License
This plugin is licensed under the GPL v2 or later.
