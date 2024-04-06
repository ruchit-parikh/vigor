## Introduction
This wordpress project contains a theme for landing page. Landing page includes banners, CTAs, user pfoiles, map with location pins, customized footers and woocommerce products. It also has a popup cart which shows cart items etc. Post meta are also defined in order to specify required text and images.

### Requirements
- WordPress 5.0 Minimum
- PHP 7.2 and MySQL
- Composer

### How to setup
- Clone this repository
- Got `https://api.wordpress.org/secret-key/1.1/salt` to get random keys generated that you can use for your system and copy it in **wp-config.php** file.
- Goto `wp-content/themes/vigor` and run command `composer install` to download all dependencies
- Setup wordpress as you do by visting website on local server
- Once installed login to dashboard and activate **Vigor** them and all required plugins.
- Setup home page (front page) and define all settings and post meta for the page.
- Create some coaches through custom post type that appears on sidebar Coaches menu and select them to appear on front page.
- Create some products and select them to appear on front page.
- Make sure to create footer widgets that appear on footer
- Make sure to setup logos, locations, google map keys and other settings from theme settings on which appear on sidebar.
- Publish changes and visit the home page. ^_^
