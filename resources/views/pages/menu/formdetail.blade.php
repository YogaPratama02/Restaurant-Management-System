<style>
    .screenshot{
        padding: 10px 5px;
        width: 70%;
        height: 50%;
    }
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css">
<div class="modal-body">
    <div class="form-group">
        <a href="#" data-lightbox="roadtrip">
            <img src="{{asset($model->image)}}" alt="{{asset($model->image)}}" class="screenshot">
        </a>
    </div>
    <div class="form-group">
        <h5>{!!$model->description!!}</h5>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal" id="modal-close">Close</button>
</div>

{{-- @push('after-script') --}}
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script> --}}
{{-- @endpush --}}
