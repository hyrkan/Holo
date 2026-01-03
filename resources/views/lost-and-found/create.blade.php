@extends('layouts.landing')

@section('content')

<section class="pt-120 pb-120">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="contact-bg02" style="padding: 40px; background: #fff; box-shadow: 0 10px 30px rgba(0,0,0,0.05); border-radius: 10px;">
                    <div class="section-title mb-40">
                        <span>New Report</span>
                        <h2>Item Details</h2>
                    </div>
                    
                    <form action="{{ route('lost-and-found.store') }}" method="POST" enctype="multipart/form-data" class="contact-form">
                        @csrf
                        <div class="row">
                            <div class="col-lg-12 mb-20">
                                <label class="mb-10 d-block">Report Type</label>
                                <div class="btn-group btn-group-toggle d-flex" data-toggle="buttons">
                                    <label class="btn btn-outline flex-fill active" id="type-lost-label">
                                        <input type="radio" name="type" id="type-lost" value="lost" checked> Lost Item
                                    </label>
                                    <label class="btn btn-outline flex-fill" id="type-found-label">
                                        <input type="radio" name="type" id="type-found" value="found"> Found Item
                                    </label>
                                </div>
                                @error('type') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-lg-12">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="contact-field p-relative c-name mb-20">                                    
                                            <input type="text" name="reporter_name" placeholder="Reporter Name (Optional)" value="{{ old('reporter_name') }}">
                                            @error('reporter_name') <span class="text-danger small">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="contact-field p-relative c-name mb-20">                                    
                                            <input type="text" name="owner_name" placeholder="Owner Name (If known)" value="{{ old('owner_name') }}">
                                            @error('owner_name') <span class="text-danger small">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="contact-field p-relative c-name mb-20">                                    
                                    <input type="text" name="item_name" placeholder="Item Name (e.g. Blue Wallet, iPhone 13)" required value="{{ old('item_name') }}">
                                    @error('item_name') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>                               
                            </div>

                            <div class="col-lg-12">
                                <div class="contact-field p-relative c-subject mb-20">                                   
                                    <input type="text" name="location" placeholder="Where was it lost/found? (e.g. Room 302, Canteen)" required value="{{ old('location') }}">
                                    @error('location') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                            </div>		

                            <div class="col-lg-12">
                                <div class="contact-field p-relative c-message mb-30">                                  
                                    <textarea name="description" id="message" cols="30" rows="10" placeholder="Describe the item (color, brand, unique features)..." required>{{ old('description') }}</textarea>
                                    @error('description') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="contact-field p-relative c-subject mb-30">
                                    <label class="mb-10 d-block">Item Image (Optional)</label>
                                    <input type="file" name="image" class="form-control-file" style="padding: 10px; border: 1px solid #f4f2f9; background: #f4f2f9; width: 100%;">
                                    @error('image') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="contact-field p-relative c-subject mb-30">
                                    <label class="mb-10 d-block">Contact Information</label>
                                    <input type="text" name="contact_info" placeholder="Email or Phone Number" value="{{ old('contact_info') }}">
                                    <small class="text-muted">How can people reach you regarding this item?</small>
                                    @error('contact_info') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="col-lg-12 mb-30">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" name="is_anonymous" value="1" class="custom-control-input" id="is_anonymous" {{ old('is_anonymous') ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="is_anonymous">Report Anonymously</label>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="slider-btn">                                          
                                    <button class="btn ss-btn" data-animation="fadeInRight" data-delay=".8s" style="width: 100%;">Submit Report</button>				
                                </div>                             
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('css')
<style>
    .btn-outline {
        background: transparent;
        border: 1px solid #4700c8;
        color: #4700c8;
        margin-bottom: 0 !important;
    }
    .btn-outline:hover, .btn-outline.active {
        background: #4700c8 !important;
        color: white !important;
    }
    .contact-field input, .contact-field textarea {
        width: 100%;
        background: #f4f2f9;
        border: 1px solid #f4f2f9;
        padding: 15px 30px;
        border-radius: 5px;
    }
    .contact-field input:focus, .contact-field textarea:focus {
        border-color: #4700c8;
        outline: none;
    }
</style>
@endpush

@push('js')
<script>
    $(document).ready(function() {
        $('input[name="type"]').change(function() {
            $('.btn-group-toggle label').removeClass('active');
            $(this).parent().addClass('active');
        });
    });
</script>
@endpush
