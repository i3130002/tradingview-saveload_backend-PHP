# Charting Library Save/Load PHP Backend

This is a simplest way to impliment the backend for TradingVIew charting library in PHP.


## Requirements

PHP and file access


## How to start

1. Make sure co copy the project into your hosting
2. `charts.php` and `.htaccess` should be under `loadSave`
3. In case you have higher `.htaccess` you should exclude the subdirectory `loadSave` using below code

   ```
   RewriteRule ^sub-dir/ - [L]
   ```

   From [https://stackoverflow.com/a/26499949/1454173]()
4. Point your charting library to the api like this

   ```
   function initOnReady() {
   	var widget = window.tvWidget = new TradingView.widget({
   		...
   		charts_storage_url: window.location.href,
   		charts_storage_api_version: "loadSave",
   		...
   	});
   };
   ```


## Documentation

The Tradingview charting library **none official** documentation is located at
[https://github.com/trunknx/charting_library_wiki/wiki/Saving-and-Loading-Charts]()
