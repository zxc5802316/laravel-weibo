@extends('layouts.app')
@section('title','个人资料')
@section('content')
    <div class="offset-md-2 col-md-8">
        <div class="col-md-12">
            <div class="offset-md-2 col-md-8">
                <section class="user_info">
                    @include('shared._user_info', ['user' => $user])
                </section>
            </div>
        </div>
    </div>
    </div>
@stop