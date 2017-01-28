@if(Session::has('success') || Session::has('warning'))
<?php $message = ''; $message_type = ''; ?>
<div style="margin: 20px 0;" class="row">
    @if(Session::has('success'))
        <?php $message = Session::get('success'); ?>
        <?php $message_type = 'success'; ?>
    @elseif(Session::has('warning'))
        <?php $message = Session::get('warning'); ?>
        <?php $message_type = 'warning'; ?>
    @endif
    <div style="" class="flash flash-{{ $message_type }}">{{ $message }}</div>
</div>
@endif