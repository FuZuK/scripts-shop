{foreach from=$tickets item="ticket"}
<div class="content_mess" style="overflow: hidden;{cycle values=", background-color: #EFFFE2;"}">
{if isset($ticket.us)}
<div class="wety">
{$ticket.us -> icon()}{$ticket.us -> login(1)}
<br />
</div>
{/if}
<div class="lst_h">
<a href="/support/ticket/{$ticket.data -> id}">{TextUtils::escape($ticket.data -> title)}</a> (<span class="time_show">{TimeUtils::show($ticket.data -> time)}</span>)
{if $ticket.data -> opened eq 1}
<span style="color: green;">(открыт)</span>
{else}
<span style="color: red;">(закрыт)</span>
{/if}
<br />
<span style="color: blue;">
{if $ticket.data -> type eq 0}
Администратору
{else}
Консультанту
{/if}
</span>
<br />
{TextUtils::show(TextUtils::cut($ticket.data -> msg, 300))}
<br />
</div>
{if $ticket.data -> id_user eq $u -> id || $ticket.data -> opened eq 0 or adminka::access('tickets_open_ticket') || $ticket -> opened eq 1 and adminka::access('tickets_close_ticket')}
<hr class="custom">
<div class="mess_mod">
{if $ticket.data -> opened eq 1}
<a href="/support/close_ticket/{$ticket.data -> id}">Закрыть</a>
{else}
<a href="/support/open_ticket/{$ticket.data -> id}">Открыть</a>
{/if}
</div>
{/if}
</div>
<!-- end of block -->
{/foreach}