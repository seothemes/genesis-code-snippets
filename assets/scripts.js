jQuery(document).ready(function ($) {

    if ($('#genesis-code-snippets-php').length) {
        var phpSettings = wp.codeEditor.defaultSettings ? _.clone(wp.codeEditor.defaultSettings) : {};
        phpSettings.codemirror = _.extend(
            {},
            phpSettings.codemirror,
            {
                mode: {
                    name: 'php',
                    startOpen: true
                }
            }
        );
        wp.codeEditor.initialize($('#genesis-code-snippets-php'), phpSettings);
    }

    if ($('#genesis-code-snippets-css').length) {
        var cssSettings = wp.codeEditor.defaultSettings ? _.clone(wp.codeEditor.defaultSettings) : {};
        cssSettings.codemirror = _.extend(
            {},
            cssSettings.codemirror,
            {
                mode: 'css'
            }
        );
        wp.codeEditor.initialize($('#genesis-code-snippets-css'), cssSettings);
    }

    if ($('#genesis-code-snippets-js').length) {
        var jsSettings = wp.codeEditor.defaultSettings ? _.clone(wp.codeEditor.defaultSettings) : {};
        jsSettings.codemirror = _.extend(
            {},
            jsSettings.codemirror,
            {
                mode: 'javascript'
            }
        );
        wp.codeEditor.initialize($('#genesis-code-snippets-js'), jsSettings);
    }

});
