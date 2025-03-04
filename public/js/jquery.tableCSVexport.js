/*
   Based on the jQuery plugin found at http://www.kunalbabre.com/projects/TableCSVExport.php
   Re-worked by ZachWick for LectureTools Inc. Sept. 2011
   Copyright (c) 2011 LectureTools Inc.

   Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/
jQuery.fn.TableCSVExport = function(options) {
    var options = jQuery.extend({
        separator: ',',
        header: [],
        columns: [],
        extraHeader: "",
	extraData: [],
	insertBefore: "",
        delivery: 'popup' /* popup, value, download */
    },
    options);

    var csvData = [];
    var headerArr = [];
    var el = this;
    var basic = options.columns.length == 0 ? true : false;
    var columnNumbers = [];
    var columnCounter = 0;
    var insertBeforeNum = null;
    //header
    var numCols = options.header.length; 
    var tmpRow = []; // construct header avalible array
   
    if (numCols > 0) {
       if (basic) {
          for (var i = 0; i < numCols; i++) {
	      if (options.header[i] == options.insertBefore) {
		  tmpRow[tmpRow.length] = options.extraHeader;
		  insertBeforeNum = i;
	      }
             tmpRow[tmpRow.length] = formatData(options.header[i]);
          }
       } else if (!basic) {
          for (var o = 0; o < numCols; o++) {
             for (var i = 0; i < options.columns.length; i++) {
                if (options.columns[i] == options.header[o]) {
                   if (options.columns[i] == options.insertBefore) {
		      tmpRow[tmpRow.length] = options.extraHeader;
                      insertBeforeNum = o;
		   }
                   tmpRow[tmpRow.length] = formatData(options.header[o]);
		   columnNumbers[columnCounter] = o;
		   columnCounter++;
                }
             }
          }       
       }
    } else {
       jQuery(el).filter(':visible').find('th').each(function() {
          if (jQuery(this).css('display') != 'none') tmpRow[tmpRow.length] = formatData(jQuery(this).html());
       });
    }

    row2CSV(tmpRow);

    // actual data
    if (basic) {
       var trCounter = 0;
       jQuery(el).find('tr').each(function() {
           var tmpRow = [];
	   var extraDataCounter = 0;
           jQuery(this).filter(':visible').find('td').each(function() {
              if (extraDataCounter == insertBeforeNum) {
		  tmpRow[tmpRow.length] = jQuery.trim(options.extraData[trCounter-1]);
	      }
              if (jQuery(this).css('display') != 'none')
                     tmpRow[tmpRow.length] = jQuery.trim(formatData(jQuery(this).html()));

              extraDataCounter++;
           });
           row2CSV(tmpRow);
           trCounter++;
       });
    } else {
       var trCounter = 0;
       jQuery(el).find('tr').each(function() {
          var tmpRow = [];
          var columnCounter = 0;
	  var extraDataCounter = 0;
          jQuery(this).filter(':visible').find('td').each(function() {
	     if ((columnCounter in columnNumbers) && (extraDataCounter == insertBeforeNum)) {
		tmpRow[tmpRow.length] = jQuery.trim(options.extraData[trCounter - 1]); 
	     }
             if ((jQuery(this).css('display') != 'none') && (columnCounter in columnNumbers)) {
                tmpRow[tmpRow.length] = jQuery.trim(formatData(jQuery(this).html()));
             }
             columnCounter++;
	     extraDataCounter++;
          });
          row2CSV(tmpRow);
           trCounter++;
       });
    }
    if ((options.delivery == 'popup')||(options.delivery == 'download')) {
        var mydata = csvData.join('\n');
        return popup(mydata);
    } else {
        var mydata = csvData.join('\n');
        return mydata;
    }

    function row2CSV(tmpRow) {
        var tmp = tmpRow.join(''); // to remove any blank rows
        // alert(tmp);
        if (tmpRow.length > 0 && tmp != '') {
            var mystr = tmpRow.join(options.separator);
            csvData[csvData.length] = jQuery.trim(mystr);
        }
    }
    function formatData(input) {
        // replace " with '
        var regexp = new RegExp(/["]/g);
        var output = input.replace(regexp, "'");
        //HTML
        var regexp = new RegExp(/\<[^\<]+\>/g);
        var output = output.replace(regexp, "");
        if (output == "") return '';
        return '' + output + '';
    }
    function popup(data) {
	if (options.delivery == 'download') {
           window.location.url = 'data:text/csv;charset=utf8,' + encodeURIComponent(data);
           return true;
	} else {
           var generator = window.open('', 'csv', 'height=400,width=600');
           generator.document.write('<html><head><title>CSV</title>');
           generator.document.write('</head><body >');
           generator.document.write('<textArea cols=70 rows=15 wrap="off" >');
           generator.document.write(data);
           generator.document.write('</textArea>');
           generator.document.write('</body></html>');
           generator.document.close();
           return true;
	}
    }
};
/*
Update: When I tried to submit to the official jQuery Plugin list, I found that my original name (table2CSV) had already been taken, so I have changed every occurrence of 'table2CSV' to 'TableCSVExport'
This plugin converts any set of columns of a HTML table to a CSV file that can then be viewed in a popup window, as a data string, or downloaded as a file.

I had a need for such a utility at my day job (at LectureTools Inc.) and searching netted me only one interesting possibility - the initial plugin that I have built upon. It appears that development on this stopped around June 2009, so I decided to 'fork' it and enhance it. You can find my project here, on my Github account; It has the plugin and a HTML test page.

Dependencies:

This plugin was developed using jQuery 1.6.2 and is not tested with other versions.

Usage:

Include script file however you need to for your particular project.

HTML:[code]<script src='jquery.TableCSVExport.js'></script>[/code]
cakePHP 1.2: [code]echo $javascript->link("jquery.TableCSVExport.js");[/code]

cakePHP 1.3: [code]echo $html->script('jquery.TableCSVExport.js');[/code]

Then, simply execute the javascript line [code]jQuery('#table-id').TableCSVExport();[/code] to use the plugin with the default options.

Options:

separator (Default: ',')
The character that separates entries in the resulting CSV file.

 

delivery (Default: 'popup')

The manner in which to return the CSV data file. Options are:

'popup' - opens a new window and displays the CSV data in a box where it can be copied from.
'value' - returns the CSV data as a single long string
'download' - triggers a download of a file containing the CSV data
 

header (Default: [])

An array containing strings of the column headers

Example: [code] jQuery('#table-id').TableCSVExport({header:['Name','Mon','Tue','Wed','Thr','Fri']}) [/code]

 

columns (Default: [])

An array containing strings consisting of the headers for the columns to export. This MUST be a subset of the array passed to the header option.

Example: [code] jQuery('#table-id').TableCSVExport({header:['Name','Mon','Tue','Wed','Thr','Fri'], columns:['Name','Mon']}) [/code]

This will export only the 'Name' and 'Mon' columns as CSV data.

 

Important Notes:

1. When using the columns option to only export certain columns, you must also set the header array and the columns array must be a subset of the header array.

2. The javascript .trim() function is called on each table datum, so be aware that beginning or terminating whitespace will be lost.

3. jquery.TableCSVExport.js is released under the MIT License and LectureTools Inc. retains all copyrights.
*/