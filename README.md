# Genesis Code Snippets

Makes adding custom code snippets to Genesis powered site easy.

## Usage

### PHP

The PHP code is hooked to `after_setup_theme` so all `genesis` functions will work.

### CSS

Enqueued as a static file.

### JS

JS is wrapped in the document ready function so jQuery works without needing to load it.

## Screenshots

![Screenshot](https://seothemes.com/wp-content/uploads/2019/05/genesis-code-snippets.png)

## Contributing

Run the following command to generate a POT file:

```shell
wp i18n make-pot ./ ./assets/genesis-code-snippets.pot
```
