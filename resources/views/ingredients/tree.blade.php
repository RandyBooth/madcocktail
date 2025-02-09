@extends('layouts.master')

@section('title', 'Tree - Ingredients')

@section('content')

    <style>
        .tree, .tree ul {
            font-size: 14px;
        }
        .tree, .tree ul, .tree li {
             position: relative;
        }

        .tree ul {
            list-style: none;
            padding-left: 32px;
        }

        .tree li::before, .tree li::after {
            content: "";
            position: absolute;
            left: -12px;
        }

        .tree li::before {
            border-top: 1px solid #000;
            top: 9px;
            width: 8px;
            height: 0;
        }

        .tree li::after {
            border-left: 1px solid #000;
            height: 100%;
            width: 0px;
            top: 2px;
        }
        
        .tree ul > li:last-child::after {
            height: 8px;
        }
    </style>

    @if (!empty($tree))
        <div class="tree">
        {!! $tree !!}
        </div>
    @endif
@stop