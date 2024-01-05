<!-- resources/views/file.blade.php -->

@foreach ($files as $file)
    <li class="file">{{ basename($file) }}</li>
@endforeach
