@extends('errors::minimal')

@section('title', __('Bad request'))
@section('code', '400')
@section('message', __($exception->getMessage() ?: 'Bad request'))
