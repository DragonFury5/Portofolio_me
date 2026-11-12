@props(['template' => 'ledger'])

<div class="mini-tpl mini-tpl--{{ $template }}" aria-hidden="true">
    @if ($template === 'ledger')
        <div class="mini-tpl__top"><span>Mara Ellison</span><span>Work / About</span></div>
        <div class="mini-tpl__title">Printed matter<br>for people who<br><em>still read.</em></div>
        <div class="mini-tpl__row"><i>01</i><b>Harbor identity</b><span>2025</span></div>
        <div class="mini-tpl__row"><i>02</i><b>Silt specimen</b><span>2024</span></div>
        <div class="mini-tpl__row"><i>03</i><b>Civic type</b><span>2024</span></div>
    @elseif ($template === 'atlas')
        <div class="mini-tpl__top"><span>Kaito Mori</span><span>Photography · Osaka</span></div>
        <div class="mini-tpl__hero">Light,<br>mostly.</div>
        <div class="mini-tpl__plates"><div></div><div></div></div>
    @elseif ($template === 'signal')
        <div class="mini-tpl__rail"><b>Priya Nair</b>Front-end engineer<br><br>Work<br><br>About<br><br>Contact</div>
        <div class="mini-tpl__main">
            <h4>Interfaces that hold up.</h4>
            <span>Atlas design system</span>
            <span>Tidepool CLI</span>
            <span>Reading room</span>
        </div>
    @else
        <div class="mini-tpl__eyebrow">Illustration · Lisbon</div>
        <div class="mini-tpl__title">Pictures for<br>slow mornings.</div>
        <div class="mini-tpl__rail">
            <div class="mini-tpl__card"><div></div><span>Quay study</span></div>
            <div class="mini-tpl__card"><div></div><span>Paper no. 3</span></div>
        </div>
    @endif
</div>
