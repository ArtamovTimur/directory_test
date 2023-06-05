<!DOCTYPE html>
<html>
<head>
    <title>Laravel Category Treeview Example</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" />
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <link href="{{ asset('css/treeview.css') }}" rel="stylesheet">
</head>
<body>
<div class="container">
    <div class="panel panel-primary">
        <div class="panel-heading">Directory Tree</div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-6">
                    <h3>All</h3>
                    <form action="{{ route('search') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="exampleInputEmail1">Search</label>
                            <input type="text" class="form-control" id="exampleInputEmail1" name="search_word" placeholder="Code or name"><br>
                            <button class="btn btn-success">Search</button>
                        </div>

                    </form>
                    <ul id="tree1">
                        @foreach($directories as $directory)
                                <li style="font-size: 22px">

                                    <a href="{{ route('edit-directory', [$directory->id]) }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                                            <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>
                                        </svg>
                                    </a>
                                    <a href="{{ route('remove-directory', [$directory->id]) }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                                            <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8 2.146 2.854Z"/>
                                        </svg>
                                    </a>
                                    {{ $directory->code }}

                                    @if(count($directory->childs))
                                        +
                                        @include('directory.manageChilds',['childs' => $directory->childs])
                                    @endif
                                </li>
                        @endforeach
                    </ul>
                </div>
                <div class="col-md-6">
                    @if(isset($editDirectory))
                        <h3>Edit directory</h3>
                        <form action="{{ route('update-directory', $editDirectory->id) }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="exampleInputEmail1">Code</label>
                                <input type="text" class="form-control" id="exampleInputEmail1" name="code" value="{{ $editDirectory->code }}">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputPassword2">Name</label>
                                <input type="text" class="form-control" id="exampleInputPassword2" name="name" value="{{ $editDirectory->name }}">
                            </div>
                            <div class="form-group">
                                <label for="inputState">Parent directory</label>
                                <select id="inputState" class="form-control" name="parent_id">
                                    <option value="0">-</option>
                                    @foreach($optionDir as $directory)
                                        @if($directory->parent_id == $editDirectory->id)
                                            <option value="{{ $directory->id }}" selected>{{ $directory->code }}</option>
                                        @elseif($directory->id != $editDirectory->id)
                                            <option value="{{ $directory->id }}">{{ $directory->code }}</option>
                                        @endif

                                    @endforeach
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary">Save</button>
                        </form>
                    @else
                        <form action="{{ route('create-directory') }}" method="POST">
                            @csrf
                            <h3>Add directory</h3>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Code</label>
                                <input type="text" class="form-control" id="exampleInputEmail1" name="code">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputPassword2">Name</label>
                                <input type="text" class="form-control" id="exampleInputPassword2" name="name">
                            </div>
                            <div class="form-group">
                                <label for="inputState">Parent directory</label>
                                <select id="inputState" class="form-control" name="parent_id">
                                    <option value="0">-</option>
                                    @foreach($optionDir as $directory)
                                        <option value="{{ $directory->id }}">{{ $directory->code }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary">Save</button>
                        </form>
                    @endif

                    <br>
                    <div>
                        <h4>Import from file</h4>
                        <form action="{{ route('import') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <input type="file" class="form-control" id="exampleInputEmail1" name="file"> <br>
                                <button class="btn btn-danger">Import</button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>


        </div>
    </div>
</div>
<script src="{{ asset('js/treeview.js') }}"></script>
</body>
</html>
