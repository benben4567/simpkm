@extends('errors::stisla')

@section('title', __('Forbidden'))
@section('code', '403')
@section('message', __($exception->getMessage() ?: 'Forbidden'))
@section('description', __('You do not have access to this page.'))
