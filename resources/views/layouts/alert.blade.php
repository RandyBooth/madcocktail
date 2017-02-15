<div class="container">
    <div class="row">
        <div class="col-12">
            <div id="alert-wrapper" class="alert-wrapper">
                <?php if (Session::has('success') || Session::has('warning')) : ?>
                    <?php
                        if (Session::has('success')) {
                            $message = Session::get('success');
                            $message_type = 'success';
                        } elseif (Session::has('warning')) {
                            $message = Session::get('warning');
                            $message_type = 'warning';
                        }
                    ?>
                <div class="alert alert-{{ $message_type }} alert-dismissible fade show" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    {{ $message }}
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
