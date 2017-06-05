{foreach from=$reviews item="rev"}
{* для вывода перегородки *}
{assign var="border" value=false}
<div class="content_mess"{cycle values=", style='background-color: #EFFFE2;'"}>
<div class="left">
{$rev.us -> ava_list()}
</div>
<div class="lst_h">
<div class="list_us_info">
{$rev.us -> icon()}{$rev.us -> login()} <span class="time_show">({TimeUtils::show($rev.data -> time)})</span><br />
</div>
{if isset($rev.good)}
<hr class="custom">
<div style="padding-left: 7px;">
<a href="/shop/good/{$rev.good -> id}">{TextUtils::escape($rev.good -> name)}</a>
</div>
{/if}
<hr class="custom">
<div class="mess_list">
<div>
{if $rev.data -> type eq 'bad'}
<span class="red">Отрицательный</span>
{else}
<span class="green">Положительный</span>
{/if}
<br />
</div>
{TextUtils::show($rev.data -> mess)}
</div>
{if isset($rev.actions) and count($rev.actions)}
<hr class="custom">
<div class="mess_mod">
{foreach from=$rev.actions item="action"}
{if $border == true} - {/if}<a href="{$action.link}">{$action.name}</a>
{assign var="border" value=true}
{/foreach}
</div>
{/if}
</div>
<div class="clear"></div>
</div>
{/foreach}