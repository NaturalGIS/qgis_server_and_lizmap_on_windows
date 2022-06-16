{ifuserconnected}
<h3>{@jmessenger~message.title@}</h3>

<p>{zone 'jmessenger~nbNewMessage'}</p>

<ul>
    <li><a href="{jurl 'jmessenger~jmessenger:inbox'}">{@jmessenger~message.msg.inbox@}</a></li>
    <li><a href="{jurl 'jmessenger~jmessenger:outbox'}">{@jmessenger~message.msg.outbox@}</a></li>
    <li><a href="{jurl 'jmessenger~jmessenger:precreate'}">{@jmessenger~message.title.new@}</a></li>
</ul>
{/ifuserconnected}