{if isset($sets.counter_class)}
 <span class="{$sets.counter_class}">
{$list_item.counter}{if isset($list_item.counter_new) and $list_item.counter_new ne 0}/+{$list_item.counter_new}{/if}
</span>
{else}
<span>
({$list_item.counter}{if isset($list_item.counter_new) and $list_item.counter_new ne 0}/+{$list_item.counter_new}{/if})
</span>
{/if}