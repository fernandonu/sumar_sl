<!--my css-->
<link href="styles_1.css" rel="stylesheet" type="text/css">


<!-- Latest compiled and minified JavaScript -->
<script src="https://code.jquery.com/jquery-1.11.3.min.js"   integrity="sha256-7LkWEzqTdpEfELxcZZlS6wAx5Ff13zZ83lYO2/ujj7g="   crossorigin="anonymous"></script>

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>

<!--JQuery-->
<script src="app.js" type="text/javascript" charset="utf-8"></script>

<!------------------------------------------------------------------------- -->
<link href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css" />


<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>
<script type="text/javascript">
        $(function () {
            $('.datetimepicker10').datepicker({
                viewMode: 'years',
                format: 'DD/MM/YYYY',
                autoclose: true,
            });
            $('.datetimepicker10').on('changeDate', function (ev) {
            (ev.viewMode=='days') ? $(this).datepicker('hide') : '';
            });
        });

</script>
  
  
  
 
  