For the project to build, **these files must exist with exact filenames**:

* `includes/loader.js` is the JavaScript entry point.

You need to **put any JS and CSS files inside `includes`, `scripts`, and/or `styles`**, otherwise Webpack won’t see them.

## Available Scripts

In the project directory, you can run:

### `yarn start`

Builds the extension in the development mode. Open your WordPress site to view it in the browser. The page will reload if you make edits to JavaScript files. You will also see any lint errors in the console.

### `yarn build`

Builds the extension for production to the `build` folder. It correctly optimizes the build for the best performance.

### `yarn zip`

Runs `build` and then creates a production release zip file.

### `yarn eject`

**Note: this is a one-way operation. Once you `eject`, you can’t go back!**


### Shortcodes
[rideshare_uber_url destination="dest name" address="address" latitude="101.1243" longitude="123.456"]

[rideshare_lyft_url latitude="101.1243" longitude="123.456"]

[rideshare_uber_btn destination="dest name" address="address" latitude="101.1243" longitude="123.456"]

[rideshare_lyft_btn latitude="101.1243" longitude="123.456"]

[repeater affiliated_hospitals]
[related facility_name][field link]
[rideshare_btn]
hospital:[field acf_hospital_title],city:[field acf_city],state:[field acf_state],zipcode:[field acf_zip],lat:[field acf_lat],lng:[field acf_lng]
[/rideshare_btn]
[/related]
[/repeater]