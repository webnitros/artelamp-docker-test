/**
 *
 * @param value
 * @param props
 * @param row
 * @returns {*}
 * @constructor
 */
msPromoCode2.renderer.Actions = function (value, props, row) {
    var res = [];
    var cls, icon, title, action, item;
    if (typeof(value) === 'object') {
        for (var i in value) {
            if (!value.hasOwnProperty(i)) {
                continue;
            }
            var a = value[i];
            if (!a['button']) {
                continue;
            }

            icon = a['icon'] ? a['icon'] : '';
            if (typeof(a['cls']) === 'object') {
                if (typeof(a['cls']['button']) !== 'undefined') {
                    icon += ' ' + a['cls']['button'];
                }
            } else {
                cls = a['cls'] ? a['cls'] : '';
            }
            action = a['action'] ? a['action'] : '';
            title = a['title'] ? a['title'] : '';

            item = String.format(
                '<li class="{0}"><button class="btn btn-default {1}" action="{2}" title="{3}"></button></li>',
                cls, icon, action, title
            );

            res.push(item);
        }
    }

    return String.format(
        '<ul class="mspc2-grid-col__actions">{0}</ul>',
        res.join('')
    );
};

/**
 *
 * @param string
 * @returns {string}
 * @constructor
 */
msPromoCode2.renderer.DateTime = function (string) {
    if (string && string !== '0000-00-00 00:00:00' && string !== '-1-11-30 00:00:00' && string != 0) {
        var date = /^[0-9]+$/.test(string)
            ? new Date(string * 1000)
            : new Date(string.replace(/(\d+)-(\d+)-(\d+)/, '$2/$3/$1'));
        var format = MODx.config['mspc2_backend_datetime_format'];
        if (!format) {
            format = '%d.%m.%Y <span class="action-gray">%H:%M</span>';
        }
        return strftime(format, date);
    }
    return '';
};

/**
 * @param val
 * @param props
 * @param row
 * @returns {string}
 * @constructor
 */
msPromoCode2.renderer.Value = function (val, props, row) {
    return String.format(
        '<div class="mspc2-grid-col__value">{0}</div>',
        val
    );
};

/**
 *
 * @param val
 * @returns {*}
 * @constructor
 */
msPromoCode2.renderer.Boolean = function (val) {
    return String.format(
        '<div class="mspc2-grid-col__boolean mspc2-grid-col__value {0}">{1}</div>',
        val ? 'green' : 'red',
        _(val ? 'yes' : 'no')
    );
};

// /**
//  *
//  * @param val
//  * @param props
//  * @param row
//  * @returns {*}
//  * @constructor
//  */
// msPromoCode2.renderer.CustomField = function (val, props, row) {
//     var rec = row['json'];
//     return String.format(
//         '<div class="mspc2-grid-col__customfield mspc2-grid-col__value">{0}</div>',
//         rec['customfield']
//     );
// };

/**
 *
 * @param val
 * @param props
 * @param row
 * @returns {*}
 * @constructor
 */
msPromoCode2.renderer.Clipboard = function (val, props, row) {
    var rec = row['json'];
    return String.format(
        '<div class="mspc2-grid-col__clipboard mspc2-grid-col__value">' +
            '<button class="mspc2-grid-col__clipboard-button icon icon-clipboard [ js-mspc2-coupon-copy ]"' +
                'data-clipboard-text="{0}"></button>' +
        '</div>',
        val
    );
};

/**
 *
 * @param val
 * @param props
 * @param row
 * @returns {*}
 * @constructor
 */
msPromoCode2.renderer.Code = function (val, props, row) {
    var rec = row['json'];
    return String.format(
        '<div class="mspc2-grid-col__coupon mspc2-grid-col__value {0}">' +
            '<div class="mspc2-grid-col__code">{2}</div>' +
            '<div class="mspc2-grid-col__description {1}">{3}</div>' +
        '</div>',
        (rec['count'] === '0' || rec['count'] === 0) ? 'is-empty' : '',
        (rec['description'] !== '') ? 'is-full' : '',
        rec['code'],
        rec['description']
    );
};

/**
 *
 * @param val
 * @param props
 * @param row
 * @returns {*}
 * @constructor
 */
msPromoCode2.renderer.Count = function (val, props, row) {
    var rec = row['json'];
    var value = rec['count'] === '' ? '&infin;' : rec['count'];
    return String.format(
        '<div class="mspc2-grid-col__count mspc2-grid-col__value {0}">{1}</div>',
        (rec['count'] === '0' || rec['count'] === 0) ? 'is-empty' : '',
        value
    );
};

/**
 *
 * @param val
 * @param props
 * @param row
 * @returns {*}
 * @constructor
 */
msPromoCode2.renderer.Orders = function (val, props, row) {
    var rec = row['json'];
    var value = rec['orders'] || 0;
    return String.format(
        '<div class="mspc2-grid-col__orders mspc2-grid-col__value">{0}</div>',
        value
    );
};

/**
 *
 * @param val
 * @param props
 * @param row
 * @returns {*}
 * @constructor
 */
msPromoCode2.renderer.Discount = function (val, props, row) {
    var rec = row['json'];
    var value = (rec['discount'] === '0' || rec['discount'] === '0%') ? '—' : rec['discount'];
    return String.format(
        '<div class="mspc2-grid-col__discount mspc2-grid-col__value">{0}</div>',
        value
    );
};

/**
 *
 * @param val
 * @param props
 * @param row
 * @returns {*}
 * @constructor
 */
msPromoCode2.renderer.Lifetime = function (val, props, row) {
    var rec = row['json'];
    var startedon = msPromoCode2.renderer.DateTime(rec['startedon']) || '';
    var stoppedon = msPromoCode2.renderer.DateTime(rec['stoppedon']) || '';
    var value = ''; // '... — ...';
    if (startedon !== '' && stoppedon !== '') {
        value = startedon + ' — ' + stoppedon;
    } else if (startedon !== '') {
        value = startedon + ' — ...';
    } else if (stoppedon !== '') {
        value = '... — ' + stoppedon;
    }
    return String.format(
        '<div class="mspc2-grid-col__lifetime mspc2-grid-col__value"><div>{0}</div></div>',
        value
    );
};

/**
 * @param val
 * @param props
 * @param row
 * @returns {string}
 * @constructor
 */
msPromoCode2.renderer.Pagetitle = function (val, props, row) {
    var rec = row['json'];

    var parents = '';
    rec.parents.forEach(function (parent) {
        parents += String.format('<nobr><small>{0} / </small></nobr>', parent['pagetitle']);
    });

    return String.format(
        '<div class="mspc2-grid-col__pagetitle mspc2-grid-col__value">' +
            '<div class="mspc2-grid-col__pagetitle-parents">{0}</div>' +
            '<div class="mspc2-grid-col__pagetitle-resource">' +
                '<!--<small>{1}</small> --><b>{2}</b>' +
            '</div>' +
        '</div>',
        parents,
        rec['resource'],
        rec['pagetitle']
    );
};