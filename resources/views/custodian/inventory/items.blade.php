<x-main-layout>
    <div class="page-heading">
        <h1 class="page-title">Basic Form</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="index.html"><i class="la la-home font-20"></i></a>
            </li>
            <li class="breadcrumb-item">Basic Form</li>
        </ol>
    </div>
        <div class="table-responsive">   
            <div class="page-content fade-in-up">
                <div class="row">
                    <div class="col-md-6">
                        <div class="ibox">
                            <div class="ibox-head">
                                <div class="ibox-title">Basic form</div>
                                <div class="ibox-tools">
                                    <a class="ibox-collapse"><i class="fa fa-minus"></i></a>
                                    
                                </div>
                            </div>
                            <div class="ibox-body">
                                <form>
                                    <div class="row">
                                        <div class="col-sm-6 form-group">
                                            <label>First Name</label>
                                            <input class="form-control" type="text" placeholder="First Name">
                                        </div>
                                        <div class="col-sm-6 form-group">
                                            <label>Last Name</label>
                                            <input class="form-control" type="text" placeholder="First Name">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input class="form-control" type="text" placeholder="Email address">
                                    </div>
                                    <div class="form-group">
                                        <label>Password</label>
                                        <input class="form-control" type="password" placeholder="Password">
                                    </div>
                                    <div class="form-group">
                                        <button class="btn btn-default" type="submit">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

        </div>
    </div>
   
    
</x-main-layout>



{{-- 

<div class="ibox-body">
    <form id="qrForm" method="POST" action="{{ route('qr.generate') }}">
        @csrf
        <div class="row">
            <div class="col-sm-6 form-group">
                <label>First Name</label>
                <input class="form-control" type="text" placeholder="First Name">
            </div>
            <div class="col-sm-6 form-group">
                <label>Last Name</label>
                <input class="form-control" type="text" placeholder="First Name">
            </div>
        </div>
        
        <div class="form-group">
            <label>QR Code Data</label>
            <input class="form-control" type="text" name="text" placeholder="Enter text for QR code">
        </div>
        
        <div class="form-group">
            <label>Generated QR Code</label>
            <div id="qrCodeDisplay"></div>
        </div>
        
        <div class="form-group">
            <button class="btn btn-default" type="submit">Submit</button>
        </div>
    </form>
</div> --}}