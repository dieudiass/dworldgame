<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/styles.css">
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <style>
        .nav-tabs {
            margin-bottom: 15px;
        }
        .sign-with {
            margin-top: 25px;
            padding: 20px;
        }
        div#OR {
            height: 30px;
            width: 30px;
            border: 1px solid #C2C2C2;
            border-radius: 50%;
            font-weight: bold;
            line-height: 28px;
            text-align: center;
            font-size: 12px;
            float: right;
            position: absolute;
            right: -16px;
            top: 40%;
            z-index: 1;
            background: #DFDFDF;
        }
    </style>
</head>
<body>
<div class="row">
<div class="col-md-8" style="border-right: 1px dotted #C2C2C2;padding-right: 30px;">
    <form role="form" class="form-horizontal">
        <div class="form-group">
            <label for="email" class="col-sm-2 control-label">
                Name</label>
            <div class="col-sm-10">
                <div class="row">
                    <div class="col-md-3">
                        <select class="form-control">
                            <option>Mr.</option>
                            <option>Ms.</option>
                            <option>Mrs.</option>
                        </select>
                    </div>
                    <div class="col-md-9">
                        <input type="text" class="form-control" placeholder="Name" />
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="email" class="col-sm-2 control-label">
                Email</label>
            <div class="col-sm-10">
                <input type="email" class="form-control" id="email" placeholder="Email" />
            </div>
        </div>
        <div class="form-group">
            <label for="mobile" class="col-sm-2 control-label">
                Mobile</label>
            <div class="col-sm-10">
                <input type="email" class="form-control" id="mobile" placeholder="Mobile" />
            </div>
        </div>
        <div class="form-group">
            <label for="password" class="col-sm-2 control-label">
                Password</label>
            <div class="col-sm-10">
                <input type="password" class="form-control" id="password" placeholder="Password" />
            </div>
        </div>
        <div class="row">
            <div class="col-sm-2">
            </div>
            <div class="col-sm-10">
                <button type="button" class="btn btn-primary btn-sm">
                    Save & Continue</button>
                <button type="button" class="btn btn-default btn-sm">
                    Cancel</button>
            </div>
        </div>
    </form>

    <div id="OR" class="hidden-xs">
        OR</div>
</div>
<div class="col-md-4">
    <div class="row text-center sign-with">
        <div class="col-md-12">
            <h3>
                Sign in with</h3>
        </div>
        <div class="col-md-12">
            <div class="btn-group btn-group-justified">
                <a href="#" class="btn btn-primary">Facebook</a> <a href="#" class="btn btn-danger">
                    Google</a>
            </div>
        </div>
    </div>
</div>
</div>
</body>
<script>
</script>
</html>