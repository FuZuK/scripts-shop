{* вывод комментариев через foreach *}
{foreach from=$posts item="post"}
{* для вывода перегородки *}
{assign var="border" value=false}
<div class="content_mess"{cycle values=", style='background-color: #EFFFE2;'"}>
{if isset($post.show_ava) and $post.show_ava == true or !isset($post.show_ava)}
<div class="left">
{$post.us -> ava_list()}
</div>
{/if}
<div class="lst_h">
<div class="list_us_info">
{$post.us -> icon()}{$post.us -> login(1)} <span class="time_show">({$post.time_form})</span><br />
</div>
<hr class="custom">
<div class="mess_list">
{if isset($post.data -> hidden) and $post.data -> hidden}<span class="red">Скрыл{$post.hus -> pw(array('a', ''))} {$post.hus -> login}</span><br />{/if}
{if isset($post.reply_us) and $post.reply_us -> id}<b>{$post.reply_us -> login}</b>, {/if}{$post.msg_form}
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
<div class="clear"></div>
</div>
<!-- end of block -->
{foreachelse}
<div class="content" style="margin: -4px; background: transparent;">
<div class="error_outline">
<div class="error_inline">
Комментарии отсутствуют
</div>
</div>
</div>
<!-- end of block -->
{/foreach}