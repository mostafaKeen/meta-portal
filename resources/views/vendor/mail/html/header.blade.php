@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
<img src="{{ asset('favicon.ico') }}" class="logo" alt="{{ config('app.name') }} Logo" style="height: 50px; width: auto; max-height: 50px; margin-bottom: 0;">
<span style="display: block; font-size: 19px; font-weight: bold; margin-top: 10px;">{{ config('app.name', 'Meta Portal') }}</span>
</a>
</td>
</tr>
