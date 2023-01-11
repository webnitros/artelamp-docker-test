{if $subject}Быстрый заказ на сайте{/if}
{if $body}
    Был оформлен быстрый заказ на сайте: <br><br>
    Товар: <a href="[[+site_url]]index.php?id=[[+product_id]]">[[+product.pagetitle]]</a><br>
    Стоимость: [[+price]]<br>
    Количество товаров: [[+count]]<br>
    ФИО: [[+receiver]]<br>
    Телефон: [[+phone]]<br>
    E-mail: [[+email]]<br>
    Город: [[+city]]<br>
{/if}
