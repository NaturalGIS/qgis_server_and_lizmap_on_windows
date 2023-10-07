<h3>{$title}</h3>

<table class="forum">
    <thead>
        <tr>
            <th></th>
            <th>{@jmessenger~message.list.discussion@}</th>
				{if isset($send)}
                <th>{@jmessenger~message.list.for@}</th>
				{else}
				<th>{@jmessenger~message.list.from@}</th>
				{/if}
            <th>{@jmessenger~message.list.date@}</th>
            <th>{@jmessenger~message.list.actions@}</th>
        </tr>
    </thead>    
    <tbody>
{foreach $msg as $m}
    <tr id="{$m->id}" class="{if $m->isSeen == 0 && !isset($send)}new{/if}">
        <td><input class='msg_select' type='checkbox' value="{$m->id}" name='msg_select[]'/></td>
        <td><a href="{jurl 'jmessenger~jmessenger:view', array('id'=>$m->id)}">{$m->title}</a> {if $m->isSeen == 0 && !isset($send)}({@jmessenger~message.new@} !){/if}</td>
        <td>{if isset($send)}
	    <a href="{jurl 'jcommunity~account:show', array('user'=>$m->loginFor)}">{$m->nicknameFor}</a>
        {else}
	    <a href="{jurl 'jcommunity~account:show', array('user'=>$m->loginFrom)}">{$m->nicknameFrom}</a>
        {/if}
        </td>
        <td>
            Le {$m->date|jdatetime:'db_datetime':'lang_date'} à {$m->date|jdatetime:'db_datetime':'db_time'}
        </td>
        <td>
            {if !isset($send)}
            
            <a href="{jurl 'jmessenger~jmessenger:answer', array('id'=>$m->id)}"><img src="/css/icons/script_edit.png" title="{@message.answer@}" alt="{@message.answer@}" /></a>
            <!-- <a href="{jurl 'jmessenger~jmessenger:archive', array('id'=>$m->id)}"><img src="/css/icons/folder.png" title="{@message.archive@}" alt="{@message.archive@}" /></a> -->
            {/if}
            <a href="{jurl 'jmessenger~jmessenger:delete', array('id'=>$m->id)}"><img src="/css/icons/cross.png" title="{@message.delete@}" alt="{@message.delete@}" /></a>
        </td>
    </tr>
    
{/foreach}

</tbody>
</table>
