{meta_html assets 'jacl2_admin'}

<h1>{@jacl2db_admin~acl2.group.rights.label@} {$group->name}</h1>

{if $group->id_aclgrp == '__anonymous'}
    <p>{@jacl2db_admin~acl2.anonymous.group.help@}</p>

{/if}


<form id="rights-edit" action="{formurl 'jacl2db_admin~groups:saverights', array('group'=>$group->id_aclgrp)}" method="post">
<div>{formurlparam 'jacl2db_admin~groups:saverights', array('group'=>$group->id_aclgrp)}</div>
<table class="records-list jacl2-list" id="rights-list">
<thead>
    <tr>
        <th >{@jacl2db_admin~acl2.table.th.rights@}</th>
        <th>{$group->name}</th>
    </tr>
</thead>
    <tfoot>
    <tr>
        <td>{@jacl2db_admin~acl2.group.rightres.title@}</td>
        <th><a href="{jurl 'jacl2db_admin~groups:rightres',array('group'=>$group->id_aclgrp)}">{@jacl2db_admin~acl2.special.rights@}</a></th>
    </tr>
    </tfoot>
<tbody>
{assign $currentsbjgroup = '---'}
{foreach $rights as $subject=>$right}
{if $rightsProperties[$subject]['grp'] && $currentsbjgroup != $rightsProperties[$subject]['grp']}
<tr class="{cycle array('odd','even')}">
    <th colspan="2"><h3>{$rightsGroupsLabels[$rightsProperties[$subject]['grp']]}</h3></th>
</tr>{assign $currentsbjgroup = $rightsProperties[$subject]['grp']}
{/if}
<tr class="{cycle array('odd','even')}">
    <th title="{$subject}">{$rightsProperties[$subject]['label']|eschtml}</th>
    <td>
        {if $group->id_aclgrp == '__anonymous' && (substr($subject, 0 , 4) == 'acl.' || in_array($subject, array('auth.user.change.password', 'auth.users.create', 'auth.users.delete', 'auth.users.modify', 'auth.user.modify', 'auth.users.change.password'))) }
            <span class="right-no">{@jacl2db_admin~acl2.group.rights.no@}</span>
        {else}
        <select name="rights[{$subject}]" id="{$subject|eschtml}" class="right">
            <option value=""  {if $rights[$subject] == ''}selected="selected"{/if}>{@jacl2db_admin~acl2.group.rights.no@}</option>
            <option value="y" {if $rights[$subject] == 'y'}selected="selected"{/if}>{@jacl2db_admin~acl2.group.rights.yes@}</option>
            <option value="n" {if $rights[$subject] == 'n'}selected="selected"{/if}>{@jacl2db_admin~acl2.group.rights.forbidden@}</option>
        </select>
        {/if}
    </td>
</tr>
{/foreach}
</tbody>
</table>
<div class="legend">
    <ul>
        <li><img src="{$j_jelixwww}/design/icons/accept.png" alt="yes" /> <span class="right-yes">{@jacl2db_admin~acl2.group.rights.yes@}</span> : {@jacl2db_admin~acl2.group.help.rights.yes@}</li>
        <li><span class="right-no">{@jacl2db_admin~acl2.group.rights.no@}</span>: {@jacl2db_admin~acl2.group.help.rights.no@}</li>
        <li><img src="{$j_jelixwww}/design/icons/cancel.png" alt="no" /> <span class="right-forbidden">{@jacl2db_admin~acl2.group.rights.forbidden@}</span>: {@jacl2db_admin~acl2.group.help.rights.forbidden@}</li>
    </ul>
</div>
<input name="group" value="{$groupId}" type="hidden"/>
<div><input type="submit" value="{@jelix~ui.buttons.save@}" />
    <br/>
    <a href="{jurl 'jacl2db_admin~groups:allrights'}">{@jacl2db_admin~acl2.groups.back.to.rights.list@}</a>
    <br/>
    <a href="{jurl 'jacl2db_admin~groups:index'}">{@jacl2db_admin~acl2.groups.back.to.list@}</a>

</div>
</form>

