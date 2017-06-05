{foreach from=$list_items item="list_item" name="foreach"}
{assign var="border" value=false}
{if isset($sets.img)}{$list_item.img = $sets.img}{/if}
{if isset($sets.hr) and $sets.hr eq true and $smarty.foreach.foreach.first eq false}<hr>{/if}
<div class="{if isset($sets.div)}{$sets.div}{else}content_list{/if}"{cycle values=", style='background-color: #EFFFE2;'"}>
{if isset($list_item.img)}
{if isset($sets.img_left) and $sets.img_left eq true}<div class="left">{/if}
{$list_item.img}
{if isset($sets.img_left) and $sets.img_left eq true}</div>{/if}
{/if}
{if isset($list_item.name)}
<div class="list_us_info">
{if isset($list_item.link)}<a href="{$list_item.link}">{/if}{$list_item.name}{if isset($list_item.link)}</a>{/if}
{if isset($list_item.counter)}
{include file="list.counter.tpl"}{/if}
</div>
{/if}
{if isset($list_item.content)}
<div class="lst_h">
{$list_item.content}
</div>
{/if}
{if isset($list_item.actions) and count($list_item.actions)}
<hr class="custom">
<div class="mess_mod">
{foreach from=$list_item.actions item="action"}
{if $border == true} - {/if}<a href="{$action.link}">{$action.name}</a>
{assign var="border" value=true}
{/foreach}
</div>
{/if}
<div class="clear"></div>
</div>
<!-- end of block -->
{/foreach}