RedactorX.add('plugin', 'rte_definedlinks', {
    defaults: {
        items: null
    },
    init: function() {
        this.items = null;
    },
    subscribe: {
        'popup.open': function() {
            var name = this.app.popup.getName();
            if (name === 'link' || name === 'image-edit') {
                if (this.items === null) {
                    this._load();
                } else {
                    if (this.items.length === 0) return;
                    this._build();
                }
            }
        }
    },

    // private
    _load: function() {
        if (this.items !== null && this.items.length > 0) return;
        this.ajax.get({
            url: this.opts.definedlinks,
            success: function(response) {
                this.items = response;
                if (this.items.length === 0) return;
                this._build()
            }.bind(this)
        });
    },
    _build: function() {
        var $item = this.app.popup.getFormItem('link').find('input');
        if ($item.nodes.length == 0) {
            $item = this.app.popup.getFormItem('url').find('input');
        }
        var $box = this.dom('<div>').addClass(this.prefix + '-form-item').css('padding', '0px 0px 16px 0px');

        // select
        this.$select = this._create();

        $box.append(this.$select);
        $item.before($box);
        Dropdown.renderFields();
    },
    _create: function() {
        var $div = this.dom('<div>').addClass(this.prefix + '-form-div');
        var $choices = {};

        $.each(this.items, function(item, val) {
            val.label = val.text;
            val.value = val.href;
            $choices[item] = val;
        });

        var options = {
            name: this.prefix + '-form-dropdown-react',
            items: $choices,
            initialItems: $choices,
            disabled: false,
            tooMany: 8,
            limit: 100,
            emptyText: "Select a Page",
            conditionalRule: "rx-redactor-dropdown",
        };

        var dropdownReactAttr = btoa(JSON.stringify(options));
        $div.attr('data-input-value', this.prefix + '-form-dropdown-react');
        $div.attr('data-dropdown-react', dropdownReactAttr);

        return $div
    }
});