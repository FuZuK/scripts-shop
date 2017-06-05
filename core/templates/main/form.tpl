<form method="{$method|default:"post"}" action="{$action|default:null}"{if isset($files)} enctype="multipart/form-data"{/if}{if isset($form_sets.class) and $form_sets.class} class="{$form_sets.class}"{/if}>
{section name=sect loop=$el}
{if isset($el[sect].type)}
{if $el[sect].type eq 'title'}
<span{if isset($form_sets.title_class) and $form_sets.title_class} class="{$form_sets.title_class}"{/if}>{$el[sect].value}</span>
{elseif $el[sect].type eq 'radio'}
{html_radios name=$el[sect].name options=$el[sect].options checked=$el[sect].checked|default:null separator=$el[sect].separator|default:"<br />" labels=$el[sect].labels|default:true}
{elseif $el[sect].type eq 'checkbox'}
{if isset($el[sect].labels)}<label for="{if isset($el[sect].id)}{$el[sect].id}{else}id_{$el[sect].name}{/if}">{/if}<input type="checkbox" name="{$el[sect].name}" id="{if isset($el[sect].id)}{$el[sect].id}{else}id_{$el[sect].name}{/if}" value="{$el[sect].value}"{if isset($el[sect].checked) and $el[sect].checked eq true} checked="checked"{/if} /> {$el[sect].text}{if isset($el[sect].labels)}</label>{/if}
{elseif $el[sect].type eq 'select'}
<select name="{$el[sect].name|default:null}"{if isset($form_sets.select_class) and $form_sets.select_class} class="{$form_sets.select_class}"{/if}{if isset($el[sect].id)} id="{$el[sect].id}"{/if}>
{html_options options=$el[sect].options selected=$el[sect].selected|default:null}
</select>
{elseif $el[sect].type eq 'text'}
<input type="text"{if isset($el[sect].size)} size="{$el[sect].size}"{/if}{if isset($el[sect].disabled) and $el[sect].disabled eq true} disabled="disabled"{/if} name="{$el[sect].name|default:null}" value="{$el[sect].value|default:null}"{if isset($el[sect].maxlength)} maxlength="{$el[sect].maxlength}"{/if}{if isset($form_sets.text_class) and $form_sets.text_class} class="{$form_sets.text_class}"{/if}{if isset($el[sect].id)} id="{$el[sect].id}"{/if} />
{elseif $el[sect].type eq 'hidden'}
<input type="hidden" name="{$el[sect].name|default:null}" value="{$el[sect].value|default:null}"{if isset($el[sect].id)} id="{$el[sect].id}"{/if} />
{elseif $el[sect].type eq 'password'}
<input type="password"{if isset($el[sect].size)} size="{$el[sect].size}"{/if} name="{$el[sect].name|default:null}" value="{$el[sect].value|default:null}"{if isset($el[sect].maxlength)} maxlength="{$el[sect].maxlength}"{/if}{if isset($form_sets.text_class) and $form_sets.text_class} class="{$form_sets.text_class}"{/if}{if isset($el[sect].id)} id="{$el[sect].id}"{/if} />
{elseif $el[sect].type eq 'textarea'}
<textarea{if isset($el[sect].disabled) and $el[sect].disabled eq true} disabled="disabled"{/if} name="{$el[sect].name|default:null}"{if isset($form_sets.text_class) and $form_sets.text_class} class="{$form_sets.text_class}"{/if}{if isset($el[sect].cols)} cols="{$el[sect].cols}"{/if}{if isset($el[sect].rows)} rows="{$el[sect].rows}"{/if}{if isset($el[sect].id)} id="{$el[sect].id}"{/if}{if isset($fastSend)} {literal}onkeypress="if(event.keyCode==10||(event.ctrlKey && event.keyCode==13))fastSendButton.click();"{/literal}{/if}>{$el[sect].value|default:null}</textarea>
{elseif $el[sect].type eq 'submit'}
<input type="submit" name="{$el[sect].name|default:null}"{if isset($el[sect].id)} id="{$el[sect].id}"{/if} value="{$el[sect].value|default:null}"{if isset($form_sets.submit_class) and $form_sets.submit_class} class="{$form_sets.submit_class}"{/if}{if isset($fastSend)} id="fastSendButton"{/if} />
{elseif $el[sect].type eq 'file'}
<input type="file" name="{$el[sect].name|default:null}"{if isset($form_sets.text_class) and $form_sets.text_class} class="{$form_sets.text_class}"{/if}{if isset($el[sect].accept)}accept="{$el[sect].accept}"{/if}{if isset($el[sect].id)} id="{$el[sect].id}"{/if} />
{elseif $el[sect].type eq 'captcha'}
<span{if isset($form_sets.title_class) and $form_sets.title_class} class="{$form_sets.title_class}"{/if}>Код с картинки:</span><br />
<img src="{Captcha::getCaptchaImageSource()}"{if isset($el[sect].idImg)} id="{$el[sect].idImg}"{/if} alt=""><br />
<input type="text" name="captcha"{if isset($el[sect].idInput)} id="{$el[sect].idInput}"{/if} value="" class="{$form_sets.text_class|default:null}">
{elseif $el[sect].type eq 'select_date'}
{html_select_date start_year=$el[sect].start_year end_year=$el[sect].end_year prefix=$el[sect].prefix|default:null set_day=$el[sect].selected_day set_month=$el[sect].selected_month set_year=$el[sect].selected_year field_order=$el[sect].order|default:"DMY" class=$form_sets.select_class}
{elseif $el[sect].type eq 'ussec'}
<input type="hidden" name="ussec" value="{if isset($u)}{$u -> getSecCode()}{/if}"{if isset($el[sect].id)} id="{$el[sect].id}"{/if} />
{elseif $el[sect].type eq 'hp_smiles'}
<a href="/adds/smiles" class="hp_bb">Смайлы</a>
{elseif $el[sect].type eq 'hp_tags'}
<a href="/adds/tags" class="hp_bb">Теги</a>
{/if}
{/if}
{if isset($el[sect].br)}<br />
{/if}
{if isset($el[sect].alert)}
<span class="alert{if isset($el[sect].warning) and $el[sect].warning eq true} warning{/if}">{$el[sect].alert}<br /></span>
{/if}
{/section}
</form>
<!-- end of block -->
