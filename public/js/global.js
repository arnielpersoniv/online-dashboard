const app_href = window.location.href;
const app_host = window.location.origin;
const split_url = app_href.split("/");
const appname = split_url[3];

$(document).ready(function () {

  // === Tooltips === //
	$('.tip').tooltip();	
	$('.tip-left').tooltip({ placement: 'left' });	
	$('.tip-right').tooltip({ placement: 'right' });	
	$('.tip-top').tooltip({ placement: 'top' });	
	$('.tip-bottom').tooltip({ placement: 'bottom' });	

  toastr.options.closeButton = true;
  toastr.options.progressBar = true;
  toastr.options.hideEasing = 'linear';
  toastr.options.positionClass = "toast-bottom-right";

  let mybutton = document.getElementById("myBtn");

  // When the user scrolls down 20px from the top of the document, show the button
  window.onscroll = function () { scrollFunction() };

  function scrollFunction() {
    if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
      mybutton.style.display = "block";
    } else {
      mybutton.style.display = "none";
    }
  }

  // When the user clicks on the button, scroll to the top of the document
  $('#myBtn').on('click', function () {
    document.body.scrollTop = 0;
    document.documentElement.scrollTop = 0;
  })

  var IDLE_TIMEOUT = 600; //seconds
  var _idleSecondsTimer = null;
  var _idleSecondsCounter = 0;

  document.onclick = function () {
    _idleSecondsCounter = 0;
  };

  document.onmousemove = function () {
    _idleSecondsCounter = 0;
  };

  document.onkeypress = function () {
    _idleSecondsCounter = 0;
  };

  _idleSecondsTimer = window.setInterval(CheckIdleTime, 1000);

  function CheckIdleTime() {
    _idleSecondsCounter++;
    var oPanel = document.getElementById("SecondsUntilExpire");
    if (oPanel)
      oPanel.innerHTML = (IDLE_TIMEOUT - _idleSecondsCounter) + "";
    if (_idleSecondsCounter >= IDLE_TIMEOUT) {
      window.clearInterval(_idleSecondsTimer);
      cxDialog({
        info: 'Your idle for 5 mins. The page need to refresh.<br>Click Confirm to continue.</p>',
        ok: () => {
          location.reload();
        },
      });
    }
  }
})

$('#btn_logout').on('click', function () {
  cxDialog({
    info: 'Are you sure you want to leave?',
    ok: () => {
      $('#logout-form').submit();
    },
    no: () => { },
  });
})

$('#change_profile').on('click', function () {
  $('#myModal').modal('show')
})

$(".editimgload").on('change', function () {
  if (this.files && this.files[0]) {
    var reader = new FileReader();
    reader.onload = function (e) {
      $('#editimgshow').attr('src', e.target.result);
    }
    reader.readAsDataURL(this.files[0]);
  }
});

$('#btn_upload').on('click', function () {
  cxDialog({
    info: 'Are you sure you want to upload?',
    ok: () => {
      $('#form_upload').submit();
    },
    no: () => { },
  });
})

function datatables(datableid) {
  var table = $('#' + datableid).dataTable({
    "bJQueryUI": true,
    //"scrollX": true,
    "sDom": 'Blfrtip',
    "buttons": [{
      extend: 'excel',
      text: "<button class='btn btn-success btn-sm tip-top' data-original-title='Click to download'><i class='fa fa-file-excel-o'></i> Export</button>",
    }]
  });
  $("#searchbox").keyup(function () {
    table.fnFilter(this.value);
  });
}


var tablesToExcel = (function () {
  var uri = 'data:application/vnd.ms-excel;base64,',
    tmplWorkbookXML = '<?xml version="1.0"?><?mso-application progid="Excel.Sheet"?><Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet" xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet">' +
      '<DocumentProperties xmlns="urn:schemas-microsoft-com:office:office"><Author>Axel Richter</Author><Created>{created}</Created></DocumentProperties>' +
      '<Styles>' +
      '<Style ss:ID="Currency"><NumberFormat ss:Format="Currency"></NumberFormat></Style>' +
      '<Style ss:ID="Date"><NumberFormat ss:Format="Medium Date"></NumberFormat></Style>' +
      '</Styles>' +
      '{worksheets}</Workbook>',
    tmplWorksheetXML = '<Worksheet ss:Name="{nameWS}"><Table>{rows}</Table></Worksheet>',
    tmplCellXML = '<Cell{attributeStyleID}{attributeFormula}><Data ss:Type="{nameType}">{data}</Data></Cell>',
    base64 = function (s) {
      return window.btoa(unescape(encodeURIComponent(s)))
    },
    format = function (s, c) {
      return s.replace(/{(\w+)}/g, function (m, p) {
        return c[p];
      })
    }
  return function (tables, wsnames, wbname, appname) {
    var ctx = "";
    var workbookXML = "";
    var worksheetsXML = "";
    var rowsXML = "";

    for (var i = 0; i < tables.length; i++) {
      if (!tables[i].nodeType) tables[i] = document.getElementById(tables[i]);
      for (var j = 0; j < tables[i].rows.length; j++) {
        rowsXML += '<Row>'
        for (var k = 0; k < tables[i].rows[j].cells.length; k++) {
          var dataType = tables[i].rows[j].cells[k].getAttribute("data-type");
          var dataStyle = tables[i].rows[j].cells[k].getAttribute("data-style");
          var dataValue = tables[i].rows[j].cells[k].getAttribute("data-value");
          dataValue = (dataValue) ? dataValue : tables[i].rows[j].cells[k].innerText;
          var dataFormula = tables[i].rows[j].cells[k].getAttribute("data-formula");
          dataFormula = (dataFormula) ? dataFormula : (appname == 'Calc' && dataType == 'DateTime') ? dataValue : null;
          ctx = {
            attributeStyleID: (dataStyle == 'Currency' || dataStyle == 'Date') ? ' ss:StyleID="' + dataStyle + '"' : '',
            nameType: (dataType == 'Number' || dataType == 'DateTime' || dataType == 'Boolean' || dataType == 'Error') ? dataType : 'String',
            data: (dataFormula) ? '' : dataValue,
            attributeFormula: (dataFormula) ? ' ss:Formula="' + dataFormula + '"' : ''
          };
          rowsXML += format(tmplCellXML, ctx);
        }
        rowsXML += '</Row>'
      }
      ctx = {
        rows: rowsXML,
        nameWS: wsnames[i] || 'Sheet' + i
      };
      worksheetsXML += format(tmplWorksheetXML, ctx);
      rowsXML = "";
    }

    ctx = {
      created: (new Date()).getTime(),
      worksheets: worksheetsXML
    };
    workbookXML = format(tmplWorkbookXML, ctx);

    var link = document.createElement("A");

    // IE 11
    if (window.navigator.msSaveBlob) {
      var blob = new Blob([workbookXML], {
        type: "application/csv;charset=utf-8;"
      });
      navigator.msSaveBlob(blob, 'test.xls');
    }
    // Chrome and other browsers
    else {
      link.href = uri + base64(workbookXML);
    }

    link.download = wbname || 'Workbook.xls';
    link.target = '_blank';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
  }
})();
