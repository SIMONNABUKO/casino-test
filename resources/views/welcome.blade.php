@extends('layouts.app')
@section('content')
<casino-games game-categories="{{json_encode($game_categories)}}" casino-games="{{json_encode($games)}}"/>
@endsection