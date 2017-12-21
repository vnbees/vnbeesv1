@extends('templates.master')

@section('css')
<link rel="stylesheet" type="text/css" href="public/plugins/daterangepicker/daterangepicker-bs3.css">
<style>
  .color-palette {
    height: 35px;
    line-height: 35px;
    text-align: center;
  }
  .color-palette-set {
    margin-bottom: 15px;
  }
  .color-palette span {
    display: none;
    font-size: 12px;
  }
  .color-palette:hover span {
    display: block;
  }
  .color-palette-box h4 {
    position: absolute;
    top: 100%;
    left: 25px;
    margin-top: -40px;
    color: rgba(255, 255, 255, 0.8);
    font-size: 12px;
    display: block;
    z-index: 7;
  }
</style>
@stop
@section('content')
    <section class="content">

      <!-- Default box -->
      <div class="box color-palette-box">
        <div class="box-header with-border">
          <h3 class="box-title" style="margin-right: 20px;">Chart</h3>
          <button id="daterange-btn" class="btn btn-default">
            <i class="fa fa-calendar"></i> Filter
            <i class="fa fa-caret-down"></i>
          </button>
          <div class="box-tools pull-right">
            <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
            <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
          </div>
        </div>
        <div class="box-body chart-responsive">
          <div id="revenue-chart" class="chart" style="height: 300px;"></div>
        </div><!-- /.box-body -->
        <div class="box-footer">
          <div class="row">
            <div class="col-sm-4 col-md-2">
              <h4 class="text-center">UTM</h4>
              <div class="color-palette-set">
                <div class="bg-yellow color-palette"><span>Active</span></div>
              </div>
            </div><!-- /.col -->
            <div class="col-sm-4 col-md-2">
              <h4 class="text-center">Direct</h4>
              <div class="color-palette-set">
                <div class="bg-aqua-active color-palette"><span>Active</span></div>
              </div>
            </div><!-- /.col -->
            <div class="col-sm-4 col-md-2">
              <h4 class="text-center">Organic Search</h4>
              <div class="color-palette-set">
                <div class="bg-green-active color-palette"><span>Active</span></div>
              </div>
            </div><!-- /.col -->
          </div><!-- /.row -->
        </div><!-- /.box-footer-->
      </div><!-- /.box -->

    </section><!-- /.content -->
@stop
@section('js')
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js"></script>
  <script type="text/javascript" src="public/plugins/daterangepicker/daterangepicker.js"></script>
  <script type="text/javascript">
    function renderChar(url = 'store?id='+user.id+'&dateFrom='+moment().format("MM/DD/YYYY")+'&dateTo='+moment().format("MM/DD/YYYY")) {
        $.get(url, function(data, status){
             // AREA CHART
             var area = new Morris.Area({
               element: 'revenue-chart',
               resize: true,
               data: JSON.parse(data),
               xkey: 'd',
               ykeys: ['item1', 'item2', 'item3'],
               labels: ['UTM', 'Direct' , 'Organic Search'],
               lineColors: ['#f39c12', '#00a7d0', '#dd4b39'],
               hideHover: 'auto'
             });
          });
    }
    $(function () {
      renderChar();
       //Date range as a button
      $('#daterange-btn').daterangepicker(
          {
            ranges: {
              'Hôm nay': [moment(), moment()],
              'Hôm qua': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
              '7 ngày trước': [moment().subtract(6, 'days'), moment()],
              '30 ngày trước': [moment().subtract(29, 'days'), moment()],
              'Tháng này': [moment().startOf('month'), moment().endOf('month')],
              'Tháng trước': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            },
            // startDate: $('#dateStartActive').val(),
            // endDate: $('#dateEndActive').val()
          },
        function (start, end) {
            var dateFrom = $('input[name="daterangepicker_start"]').val();
            var dateTo = $('input[name="daterangepicker_end"]').val();
            renderChar('store?id='+user.id+'&dateFrom='+dateFrom+'&dateTo='+dateTo);
            // window.location = '/?dateFrom='+dateFrom+'&dateTo='+dateTo+'&titleFilter='+$('.ranges .active').html();
        }
      );
    })
  </script>
@stop