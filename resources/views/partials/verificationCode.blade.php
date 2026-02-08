<div class="d-flex">
    <input type="tel" name="code[]" maxlength="1" pattern="[0-9]" placeholder="*" class="form-control form--control" autocomplete="off" required>
    <input type="tel" name="code[]" maxlength="1" pattern="[0-9]" placeholder="*" class="form-control form--control" autocomplete="off" required>
    <input type="tel" name="code[]" maxlength="1" pattern="[0-9]" placeholder="*" class="form-control form--control" autocomplete="off" required>
    <input type="tel" name="code[]" maxlength="1" pattern="[0-9]" placeholder="*" class="form-control form--control" autocomplete="off" required>
    <input type="tel" name="code[]" maxlength="1" pattern="[0-9]" placeholder="*" class="form-control form--control" autocomplete="off" required>
    <input type="tel" name="code[]" maxlength="1" pattern="[0-9]" placeholder="*" class="form-control form--control" autocomplete="off" required>
</div>

@push('page-style-lib')
    <link rel="stylesheet" href="{{ asset('assets/universal/css/verificationCode.css') }}">
@endpush

@push('page-script-lib')
    <script src="{{ asset('assets/universal/js/verificationCode.js') }}"></script>
@endpush
