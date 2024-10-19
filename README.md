# Pressbooks Audio Export AI

This Network plugin adds an export option to Pressbooks that allows you to export Pressbooks books as audio files. It uses OpenAI to generate the audio.

## Requirements
- [OpenAI API key](https://platform.openai.com/api-keys)
- [Pressbooks](https://github.com/pressbooks/pressbooks) >= 6.5

## Installation
1. Clone this repository into your `wp-content/plugins` directory or the folder where you have your Pressbooks plugins.
2. Run `composer install` in the plugin directory.


## Usage
1. Activate the plugin in your Pressbooks network plugin section.
2. Set up your OpenAI API key in the Pressbooks network settings or in the book > OpenAI Settings page. The key defined in the network settings will be used by default if present.
3. You can pick one of the voices available in OpenAI in the OpenAI Settings page of your book.
4. Go to the export page of your book.
5. Select the "MP3 Audio (AI)" option.
6. Click the "Export Your Book" button.
7. Wait for the audio to be generated.

## License
This plugin is licensed under the GPLv2 or later.
