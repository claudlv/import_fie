@extends('layouts.layout')

@section('content')
    @if($errors->any())
        {!! implode('', $errors->all('<div>:message</div>')) !!}
    @endif

    @if ($message = Session::get('success'))

        <div class="alert alert-success alert-block">

            <button type="button" class="close" data-dismiss="alert">×</button>

            <strong>{{ $message }}</strong>

        </div>

    @endif

    @if ($message = Session::get('error'))

        <div class="alert alert-danger alert-block">

            <button type="button" class="close" data-dismiss="alert">×</button>

            <strong>{{ $message }}</strong>

        </div>

    @endif


    @if ($message = Session::get('warning'))

        <div class="alert alert-warning alert-block">

            <button type="button" class="close" data-dismiss="alert">×</button>

            <strong>{{ $message }}</strong>

        </div>

    @endif


    @if ($message = Session::get('info'))

        <div class="alert alert-info alert-block">

            <button type="button" class="close" data-dismiss="alert">×</button>

            <strong>{{ $message }}</strong>

        </div>

    @endif


    <div class="container">
        <form action="{{ route('product.import.post') }}" method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}
            Xml:
            <br/>
            <input type="file" name="file"/>
            <br/><br/>
            <input type="submit" value=" Save "/>
        </form>
    </div>
@endsection
