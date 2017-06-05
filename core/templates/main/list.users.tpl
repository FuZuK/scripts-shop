{foreach from=$users item="us" name="foreach"}
{assign var="border" value=false}
{if isset($sets.hr) and $sets.hr eq true and $smarty.foreach.foreach.first ne true}<hr>{/if}
<div class="{if isset($sets.div)}{$sets.div}{else}content_list{/if}"{cycle values=", style='background-color: #EFFFE2;'"}>
<div class="left">
{$us.us -> ava_list()}
</div>
<div class="lst_h">
<div class="list_us_info">
{$us.us -> icon()}{$us.us -> login(1)}{if isset($sets.rating)}{$us.us -> rating()}{/if}<br />
</div>
{if isset($us.info)}
<hr class="custom">
<!-- user "{$us.us -> login}" -->
<div class="mess_list">
{$us.info}
</div>
{/if}
{if isset($us.actions) and count($us.actions)}
<hr class="custom">
<div class="mess_mod">
{foreach from=$us.actions item="action"}
{if $border == true} - {/if}<a href="{$action.link}">{$action.name}</a>
{assign var="border" value=true}
{/foreach}
</div>
{/if}
</div>
<div class="clear"></div>
</div>
<!-- end of block -->
{/foreach}