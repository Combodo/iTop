# selectize-plugin-a11y.js
Selectize-plugin-a11y is a plugin to make Selectize.js accessible as a Combobox.

## Selectize-plugin-a11y – Usage

```html
<script type="text/javascript" src="selectize.js"></script>
<script type="text/javascript" src="selectize-plugin-a11y.js"></script>
<script>
$(function() {
    $('select').selectize({
        plugins: ['selectize-plugin-a11y'],
        render: {
            option: function($item, escape) {
                // Every option must have a unique id
                return `<div class="option" role="option" id="${$item.text.replace(' ', '')}">${$item.text}</div>`
            }
        }
    });
</script>
```

## Pull requests are always welcome
Any pull request to improve the plugin will be appreciated 😉
