{* вывод сообщений через foreach *}
{foreach from=$posts item="post"}
{* для вывода перегородки *}
{assign var="border" value=false}
<div class="content_mess"{cycle values=", style='background-color: #EFFFE2;'"}>
<div class="list_us_info">
{if $post.data -> type eq 'at'}<span class="red">Я</span> > {/if}{$post.us -> login(1, 0)}</a> <span class="time_show">({$post.time_form})</span><br />
</div>
<hr class="custom">
<div class="lst_h">
{$post.msg_form}
</div>
{if isset($post.actions) and count($post.actions)}
<hr class="custom">
<div class="mess_mod">
{foreach from=$post.actions item="action"}
{if $border == true} - {/if}<a href="{$action.link}">{$action.name}</a>
{assign var="border" value=true}
{/foreach}
</div>
{/if}
</div>
<!-- end of block -->
{foreachelse}
<div class="content" style="margin: -4px; background: transparent;">
<div class="error_outline">
<div class="error_inline">
Сообщения отсутствуют
</div>
</div>
</div>
<!-- end of block -->
{/foreach}