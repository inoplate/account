@extends('inoplate-adminutes::layouts.auth')

@section('page-title')
    {{ $title or '' }} | {{ strip_tags(config('inoplate.foundation.site.name')) }}
@overwrite

@section('site-title')
    <b>{!! config('inoplate.foundation.site.name') !!}</b>
@overwrite