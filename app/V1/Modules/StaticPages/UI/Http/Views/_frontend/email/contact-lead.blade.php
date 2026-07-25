<p>New Zlecero contact lead.</p>
<ul>
    <li>Name: {{ $lead['name'] }}</li>
    <li>Company: {{ $lead['company'] }}</li>
    <li>Email: {{ $lead['email'] }}</li>
    <li>Phone: {{ $lead['phone'] ?? '-' }}</li>
    <li>Subject: {{ $lead['subject'] }}</li>
</ul>
<p>{!! nl2br(e($lead['message'])) !!}</p>
