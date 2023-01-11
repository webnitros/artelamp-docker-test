
userlocation.tools.getMenu = function (actions, grid, selected) {
    var menu = [];
    var cls, icon, title, action = '';

    var has_delete = false;
    for (var i in actions) {
        if (!actions.hasOwnProperty(i)) {
            continue;
        }

        var a = actions[i];
        if (!a['menu']) {
            if (a == '-') {
                menu.push('-');
            }
            continue;
        } else if (menu.length > 0 && (/^sep/i.test(a['action']))) {
            menu.push('-');
            continue;
        }

        if (selected.length > 1) {
            if (!a['multiple']) {
                continue;
            } else if (typeof(a['multiple']) === 'string') {
                a['title'] = a['multiple'];
            }
        }

        cls = a['cls'] ? a['cls'] : '';
        icon = a['icon'] ? a['icon'] : '';
        title = a['title'] ? a['title'] : a['title'];
        action = a['action'] ? grid[a['action']] : '';

        menu.push({
            handler: action,
            text: String.format(
                '<span class="{0}"><i class="x-menu-item-icon {1}"></i>{2}</span>',
                cls, icon, title
            ),
            scope: grid
        });
    }

    return menu;
};


userlocation.tools.renderActions = function (value, props, row) {
    var res = [];
    var cls, icon, title, action, item = '';
    for (var i in row.data.actions) {
        if (!row.data.actions.hasOwnProperty(i)) {
            continue;
        }
        var a = row.data.actions[i];
        if (!a['button']) {
            continue;
        }

        cls = a['cls'] ? a['cls'] : '';
        icon = a['icon'] ? a['icon'] : '';
        action = a['action'] ? a['action'] : '';
        title = a['title'] ? a['title'] : '';

        item = String.format(
            '<li class="{0}"><button class="btn btn-default {1}" action="{2}" title="{3}"></button></li>',
            cls, icon, action, title
        );

        res.push(item);
    }

    return String.format(
        '<ul class="userlocation-row-actions">{0}</ul>',
        res.join('')
    );
};


userlocation.tools.formatDate = function(string) {
    if (string && string != '0000-00-00 00:00:00' && string != '-1-11-30 00:00:00' && string != 0) {
        var date = /^[0-9]+$/.test(string) ? new Date(string * 1000) : new Date(string.replace(/(\d+)-(\d+)-(\d+)/, '$2/$3/$1'));

        return date.strftime(MODx.config.repository_date_format);
    } else {
        return '&nbsp;';
    }
};

userlocation.tools.renderBoolean = function(value, props, row) {

    return value ? String.format('<span class="green">{0}</span>', _('yes')) : String.format('<span class="red">{0}</span>', _('no'));
};

userlocation.tools.handleChecked = function(checkbox) {
    var workCount = checkbox.workCount;
    if (!!!workCount) {
        workCount = 1;
    }
    var hideLabel = checkbox.hideLabel;
    if (!!!hideLabel) {
        hideLabel = false;
    }

    var checked = checkbox.getValue();
    var nextField = checkbox.nextSibling();

    for (var i = 0; i < workCount; i++) {
        if (checked) {
            nextField.show().enable();
        } else {
            nextField.hide().disable();
        }
        nextField.hideLabel = hideLabel;
        nextField = nextField.nextSibling();
    }
    return true;
};


userlocation.tools.renderColor = function(value, props, row) {
    return String.format('<span class="userlocation-grid-color" style="background: #{0}"></span>', value);
};


userlocation.tools.renderReplace = function(value, replace, color) {
    if (!value) {
        return '';
    } else if (!replace) {
        return value;
    }
    if (!color) {
        return String.format('<span>{0}</span>', replace);
    }
    return String.format('<span class="userlocation-render-color" style="color: #{1}">{0}</span>', replace, color);
};
