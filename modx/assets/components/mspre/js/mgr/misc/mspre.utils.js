mspre.utils.getMenu = function(actions, grid, selected) {
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
            } else if (typeof(a['multiple']) == 'string') {
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
            )
        });
    }

    menu.push({
        text: _('mspe_quick_update')
        ,type: 'resource'
        ,handler: function(itm,e) {
            this.quickUpdate(itm,e,itm.type);
        }
    });

    return menu;
};

mspre.utils.renderActions = function(value, props, row) {

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
        if ('showMenu' !== a['action']) {
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
        '<ul class="mspre-row-actions">{0}</ul>',
        res.join('')
    );
};

mspre.utils.handleChecked = function(checkbox) {
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

mspre.utils.objectlink = function(val,cell,row) {
  if (!val) {return '';}
  else if (!row.data['link']) {return val;}

  var url = row.data['link'];
  return '<a href="' + url + '" target="_blank" class="ms2-link">' + val + '</a>'
};

mspre.utils.renderReplace = function(value, replace, color) {
    if (!value) {
        return '';
    } else if (!replace) {
        return value;
    }
    if (!color) {
        return String.format('<span>{0}</span>', replace);
    }
    return String.format('<span class="mspre-render-color" style="color: #{1}">{0}</span>', replace, color);
};

mspre.utils.renderBoolean = function (value, props, row) {

  return value
    ? String.format('<span class="green">{0}</span>', _('yes'))
    : String.format('<span class="red">{0}</span>', _('no'));
}

mspre.utils.formatDate = function(string) {
  if (string && string != '0000-00-00 00:00:00' && string != 0) {
    var date = /^[0-9]+$/.test(string)
      ? new Date(string * 1000)
      : new Date(string.replace(/(\d+)-(\d+)-(\d+)/, '$2/$3/$1'));
    return date.strftime(MODx.config.ms2_date_format);
  }
  else {
    return '&nbsp;';
  }
};

mspre.utils.renderImage = function (value) {
  if (Ext.isEmpty(value)) {
    return ''
  }
  else {
    if (!/\/\//.test(value)) {
      if (!/^\//.test(value)) {
        value = '/' + value;
      }
    }
  }

  return String.format('<img src="{0}" />', value);
};

mspre.utils.productLink = function (value, id, blank) {
  if (!value) {
    return '';
  }
  else if (!id) {
    return value;
  }

  return String.format(
    '<a href="index.php?a=resource/update&id={0}" class="mspre-link" target="{1}">{2}</a>',
    id,
    (blank ? '_blank' : '_self'),
    value
  );
};


mspre.utils.renderVendor = function (value, cell, row) {

    console.log(row.data)
  return row.data['vendor_name'];
},
mspre.utils.renderPagetitle = function (value, cell, row) {
  var link = mspre.utils.productLink(value, row['data']['id']);
  if (!row.data['category_name']) {
    return String.format(
      '<div class="native-product"><span class="id">({0})</span>{1}</div>',
      row['data']['id'],
      link
    );
  }
  else {
    var category_link = mspre.utils.productLink(row.data['category_name'], row.data['parent']);
    return String.format(
      '<div class="nested-product">\
          <span class="id">({0})</span>{1}\
          <div class="product-category">{2}</div>\
      </div>',
      row['data']['id'],
      link,
      category_link
    );
  }
},


mspre.utils.renderParentPagetitle = function (value, cell, row) {
    return String.format(
      '<div class="nested-product">{0}</div>',
        row.data['category_name']
    );
},


mspre.utils.getMenus = function(actions, grid, selected, ids) {
  var menu = [];
  var cls, icon, title, action, field_name, field_value, combo_id, field_params = '';

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
      } else if (typeof(a['multiple']) == 'string') {
        a['title'] = a['multiple'];
      }
    }
    cls = a['cls'] ? a['cls'] : '';
    icon = a['icon'] ? a['icon'] : '';
    title = a['title'] ? a['title'] : a['title'];
    action = a['action'] ? grid[a['action']] : '';
    field_name = a['field_name'] ? a['field_name'] : '';
    field_value = a['field_value'] ? a['field_value'] : '';
    combo_id = a['combo_id'] ? a['combo_id'] : '';
    field_params = a['field_params'] ? a['field_params'] : '';

    var row = {
      //disabled: !ids.length > 0,
      field_name: field_name,
      field_value: field_value,
      combo_id: combo_id,
      field_params: field_params,
      text: String.format(
        '<span class="{0}"><i class="x-menu-item-icon {1}"></i>{2}</span>',
        cls, icon, title
      )
    }

    if (action) {
      row.handler = action
    } else {
      row.listeners = {
        click: function() {
          return false
        }
      }
    }

    if (a['menu']) {
      if (a['menu'].length > 0) {
        var menus = mspre.utils.getMenus(a['menu'], grid, []);
        row.menu = menus
      }
    }
    menu.push(row);
  }

  return menu;
};

