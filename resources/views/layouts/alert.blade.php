<div class="container">
    <div class="row">
        <div class="col-12">
            <div id="alert-wrapper" class="alert-wrapper">
                <?php if (Session::has('success') || Session::has('info') || Session::has('warning') || Session::has('danger') || Session::has('status')) : ?>
                    <?php
                        if (Session::has('success')) {
                            $message = Session::get('success');
                            $message_type = 'success';
                        } elseif (Session::has('info')) {
                            $message = Session::get('info');
                            $message_type = 'info';
                        } elseif (Session::has('warning')) {
                            $message = Session::get('warning');
                            $message_type = 'warning';
                        } elseif (Session::has('danger')) {
                            $message = Session::get('danger');
                            $message_type = 'danger';
                        } elseif (Session::has('status')) {
                            $message = Session::get('status');
                            $message_type = 'success';
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
