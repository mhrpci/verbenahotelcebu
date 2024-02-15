@extends('core/base::layouts.master')

@section('head')
    {!! RvMedia::renderHeader() !!}
@endsection

@section('content')
    {!! RvMedia::renderContent() !!}
@endsection

@section('javascript')
    {!! RvMedia::renderFooter() !!}
@endsection