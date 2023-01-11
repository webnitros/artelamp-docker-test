--------------------
msFavorites
--------------------
Author: Vgrish <vgrish@gmail.com>
--------------------

A basic Extra for MODx Revolution.

Feel free to suggest ideas/improvements/bugs on GitHub:
http://github.com/username/msFavorites/issues

--------------------
после установки
--------------------

указать id ресурса с фаворитным списком товаров (ресурсов)

--------------------
в шаблон товара(ресурса) добавить
--------------------

<!-- msfavorites -->

<div class="favorites favorites-default [[+msfavorites.ids.[[+id]]]]" data-id="[[+id]]">
    <a href="#" class="favorites-add favorites-link" data-text="[[%msfavorites_updating]]">[[%msfavorites_add_to_list]]</a>
    <a href="#" class="favorites-remove favorites-link" data-text="[[%msfavorites_updating]]">[[%msfavorites_remove_from_list]]</a>
    <a href="[[+msfavorites.link]]" class="favorites-go">[[%msfavorites_go_to_list]]</a>
    <span class="favorites-total">[[+msfavorites.total]]</span>
</div>

<!-- /msfavorites -->

--------------------
вывод фаворитного списка
--------------------

[[!+msfavorites.total:is=`0`:then=`список пуст`:else=`

<div class="msfavorites-list">
[[!msProducts?
&limit=`[[!+msfavorites.total]]`
&resources=`[[!+msfavorites.ids]]`
&parents=`0`
&tpl=`tpl.msFavorites.item`
]]
</div>
`]]