@extends('admin.layouts.app')

@section('breadcrumbs')
    {{ Breadcrumbs::render('admin.providers.index') }}
@endsection

@section('content')
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Поставщики</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <div class="form-group">
            <a href="{{route('admin.providers.create')}}" class="btn btn-success"><i class="fa fa-plus"></i> Добавить</a>
            </div>
            <table id="example1" class="table table-bordered">
            <thead>
            <tr>
                <th>ID</th>
                <th>Название</th>
                <th>Email</th>
                <th>Телефон</th>
                <th>Ссылка</th> 
                <th>XML</th>  
                <th>Условия</th>   
                <th>Коментарий</th>            
                <th>Действия</th>
            </tr>
            </thead>
            <tbody>
                @foreach($providers as $provider)
                    <tr>
                        <td>{{$provider->id}}</td>
                        <td><a href="{{ route('admin.providers.show', $provider) }}">{{$provider->name}}</a></td>
                        <td>{{$provider->email}}</td>
                        <td>{{$provider->phone}}</td>
                        <td><a target="_blank" href="{{$provider->url}}">{{$provider->url}}</a></td>
                        <td><a target="_blank" href="{{ $provider->xml_url }}"><i class="fa fa-file-text"></i></a></td>
                        <td>
                            @if(!empty($provider->conditions))
                                <div class="btn-group">
                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                        Просмотр <span class="caret"></span>
                                    </button>
                                    <div class="dropdown-menu pull-right dropdown-big padding-sm">
                                            <div class="box-header with-border">                                    
                                                <h4 class="box-title ">Условия</h4>
                                            </div>
                                            <div class="box-body">
                                                    {{$provider->conditions}}
                                            </div>
                                    </div>
                                </div>
                            @endif
                        </td>
                        <td>
                            @if(!empty($provider->comment))
                                <div class="btn-group">
                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                        Просмотр <span class="caret"></span>
                                    </button>
                                    <div class="dropdown-menu pull-right dropdown-middle padding-sm">
                                        {{$provider->comment}}
                                    </div>
                                </div>
                            @endif
                        </td>
                        <td>
                            <a href="{{route('admin.providers.edit', $provider)}}" class="fa fa-pencil"></a> 
                            <form class="inline-block" method="POST" action="{{route('admin.providers.destroy', $provider)}}">
                                @csrf
                                @method('delete')
                                <button type="submit" onclick="return confirm('Действительно удалить?')" class="delete-btn"><i class="fa fa-remove"></i></button>
                            </form>
                            
                        </td>
                    </tr>
                @endforeach
            </tfoot>
            </table>
            {!!$providers->render()!!}
        </div>  
        <!-- /.box-body -->
    </div>
@endsection