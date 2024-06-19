<!-- resources/views/emails/offline_devices.blade.php -->

<h1>Offline Devices</h1>

<p>The following devices are currently offline:</p>

<ul>
    @foreach ($devices as $device)
        <li>{{ $device->name }} - Last seen: {{ $device->updated_at }}</li>
    @endforeach
</ul>
