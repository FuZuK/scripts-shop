{* вывод товаров через foreach *}
{foreach from=$goods item="good"}
{* для вывода перегородки *}
{assign var="border" value=false}
<div class="content_mess"{cycle values=", style='background-color: #EFFFE2;'"}>
{if isset($good.shows.preview) and $good.shows.preview eq true}
<div class="left">
{php}
{Doc::showImage($good -> getMainPreview() -> preview_list, array('class' => 'main', 'height' => PREVIEW_LIST_WH, 'width' => PREVIEW_LIST_WH))}
{/php}
</div>
{/if}
<div class="lst_h">
{if isset($good.shows.name) and $good.shows.name eq true}
<div class="list_us_info">
<a href="/shop/good/{$good.data -> id}" >{TextUtils::escape($good.data -> name)}</a><br />
</div>
{if isset($good.shows) and count($good.shows)}
{if isset($good.shows.lines) and $good.shows.lines eq true}<hr class="custom">{/if}
<div class="mess_list">
{/if}
{if isset($good.shows.price) and $good.shows.price eq true}<span class="form_q">Цена:</span> <span class="form_a wmr_blue">{$good.data -> price} WMR</span><br />{/if}
{if isset($good.shows.seller) and $good.shows.seller eq true}<span class="form_q">Продавец:</span> {$good.seller -> login(1)}<br />{/if}
{if isset($good.shows.block_us) and $good.shows.block_us eq true and isset($good.block_us)}<span class="form_q">Заблокировал{$good.block_us -> pw(array('a', ''))}:</span> {$good.block_us -> login(1)} <span class="time_show">({TimeUtils::show($good.data -> block_time)})</span><br />{/if}
{if isset($good.shows.delete_us) and $good.shows.delete_us eq true and isset($good.delete_us)}<span class="form_q">Удалил{$good.delete_us -> pw(array('a', ''))}:</span> {$good.delete_us -> login(1)} <span class="time_show">({TimeUtils::show($good.data -> delete_time)})</span><br />{/if}
{if isset($good.shows.block_msg) and $good.shows.block_msg eq true and isset($good.block_us)}<span class="form_q">Причина:</span> {TextUtils::show($good.data -> block_msg, $good.block_us -> id)}<br />{/if}
{/if}
</div>
{if isset($good.actions) and count($good.actions)}
{if isset($good.shows.lines) and $good.shows.lines eq true}<hr class="custom">{/if}
<div class="mess_mod">
{foreach from=$good.actions item="action"}
{if $border == true} - {/if}<a href="{$action.link}">{$action.name}</a>
{assign var="border" value=true}
{/foreach}
</div>
{/if}
</div>
<div class="clear"></div>
</div>
{foreachelse}
{if (!isset($sets.show_el) or $sets.show_el eq true)}
<div class="content_mess">
Нет товаров
</div>
{/if}
<!-- end of block -->
{/foreach}