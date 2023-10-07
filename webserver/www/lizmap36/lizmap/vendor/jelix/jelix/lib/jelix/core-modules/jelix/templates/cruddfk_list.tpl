<h1>{@jelix~crud.title.list@}</h1>

<table class="records-list">
<thead>
<tr>
    {foreach $properties as $propname}
    <th>
        {if array_key_exists($propname, $propertiesForListOrder)}<a href="{jurl '#~#', array($offsetParameterName=>$page, 'listorder'=>$propname)}" class="view-order{if isset($sessionForListOrder[$propname]) && $sessionForListOrder[$propname] == 'asc'} order-asc{elseif isset($sessionForListOrder[$propname]) && $sessionForListOrder[$propname] == 'desc'} order-desc{/if}">{/if}
        {if isset($controls[$propname])}{$controls[$propname]->label|eschtml}{else}{$propname|eschtml}{/if}
        {if array_key_exists($propname, $propertiesForListOrder)}</a>{/if}
    </th>
    {/foreach}
    <th>&nbsp;</th>
</tr>
</thead>
<tbody>
{foreach $list as $record}
<tr class="{cycle array('odd','even')}">
    {foreach $properties as $propname}
    <td>{$record->$propname|eschtml}</td>
    {/foreach}
    <td>
        <a href="{jurl $viewAction,array($spkName=>$spk, $dpkName=>$record->$dpkName, $offsetParameterName=>$page)}">{@jelix~crud.link.view.record@}</a>
    </td>
</tr>
{/foreach}
</tbody>
</table>
{if $recordCount > $listPageSize}
<p class="crud-pages">Pages : {pagelinks $listAction, array($spkName=>$spk),  $recordCount, $page, $listPageSize, $offsetParameterName }</p>
{/if}
<p><a href="{jurl $createAction, array($spkName=>$spk)}" class="crud-link">{@jelix~crud.link.create.record@}</a>.</p>

