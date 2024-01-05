<ul class="arborescence">
    @foreach ($contents as $key => $value)
        <li class="cat">
            @if (is_array($value))
                @if(isset($value['content']))

                    <i class="fas fa-folder-plus create-folder" data-folder-id="{{ $value['id'] }}"></i>
                    <span class="folder" data-folder-id="{{ $value['id'] }}">{{ $key }}</span>
                @else
                   <a href="{{route("note_view",$value["id"])}}"> <span class="file" data-file-id="{{ $value['id'] }}">{{ $value['file'] }}</span></a>
                @endif




                <ul class="nested">
                    @if(!isset($value['content']))
                        <span class="file" data-file-id="{{ $value['id'] }}">{{ $value['file'] }}</span>
                    @endif
                    @if(isset($value['content']))
                            @include('arbo.folder', ['contents' => $value['content']])
                        @endif
                </ul>

            @endif
        </li>
    @endforeach
</ul>
